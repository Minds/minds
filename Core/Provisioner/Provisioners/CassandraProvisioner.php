<?php
namespace Minds\Core\Provisioner\Provisioners;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Exceptions\ProvisionException;

class CassandraProvisioner implements ProvisionerInterface
{
    protected $config;
    protected $db;

    public function __construct($config = null, $db = null, $client = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $cassandra = $this->config->get('cassandra');

        $this->db = $db ?: null; // Should be created on-the-fly at provision()
        $this->client = $client ?: null; // Should be created on-the-fly at provision()
    }

    public function provision()
    {
        $config = $this->config->get('cassandra');

        $db = $this->db ?: new Data\Call(
            null,
            $config->keyspace,
            $config->servers
        );

        if ($db->keyspaceExists()) {
            throw new ProvisionException('Cassandra storage is already provisioned');
        }

        $db->createKeyspace([
            'strategy_options' => [
                'replication_factor' => isset($config->replication_factor) ? $config->replication_factor : '3'
            ]
        ]);

        // CQL Tables

        $cqlTables = [
            'plugin' => $this->thriftLegacySchema(),
            'entities' => $this->thriftLegacySchema(),
            'entities_by_time' => $this->thriftLegacySchema(),
            'user_index_to_guid' => $this->thriftLegacySchema(),
            'session' => $this->thriftLegacySchema(),
            'friends' => $this->thriftLegacySchema(),
            'friendsof' => $this->thriftLegacySchema(),
            'relationships' => $this->thriftLegacySchema(),
            'counters' => [
                'schema' => [
                    'guid' => 'varchar',
                    'metric' => 'varchar',
                    'count'=>'counter',
                ],
                'primaryKeys' => ['guid', 'metric'],
            ],
            'notifications' => [
                'schema' => [
                    'owner_guid' => 'varint',
                    'guid' => 'varint',
                    'data' => 'text',
                    'type' => 'text',
                ],
                'primaryKeys' => ['owner_guid', 'guid'],
                'attributes' => [
                    'CLUSTERING ORDER BY (guid DESC)'
                ]
            ],
            'categories' => [
                'schema' => [
                    'type' => 'varchar',
                    'category' => 'varchar',
                    'filter' => 'varchar',
                    'guid' => 'varchar',
                ],
                'primaryKeys' => ['type', 'category', 'filter', 'guid'],
            ],
            'translations' => [
                'schema' => [
                    'guid' => 'varchar',
                    'field' => 'varchar',
                    'language' => 'varchar',
                    'source_language' => 'varchar',
                    'content' => 'text',
                ],
                'primaryKeys' => ['guid', 'field', 'language'],
            ],
            'monetization_ledger' => [
                'schema' => [
                    'guid' => 'varchar',
                    'type' => 'varchar',
                    'user_guid' => 'varchar',
                    'amount' => 'int',
                    'status' => 'varchar',
                    'ts' => 'int',
                    'service_id' => 'varchar',
                    'start' => 'int',
                    'end' => 'int',
                ],
                'primaryKeys' => [ 'guid' ]
            ],
            'plans' => [
                'schema' => [
                    'entity_guid' => 'text',
                    'plan' => 'text',
                    'user_guid' => 'text',
                    'amount' => 'int',
                    'expires' => 'int',
                    'status' => 'text',
                    'subscription_id' => 'text',
                ],
                'primaryKeys' => [
                    'entity_guid',
                    'plan',
                    'user_guid',
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (plan ASC, user_guid ASC)'
                ]
            ],
            'wire' => [
                'schema' => [
                    'receiver_guid' => 'varint',
                    'method' => 'text',
                    'timestamp' => 'timestamp',
                    'entity_guid' => 'varint',
                    'wire_guid' => 'varint',
                    'amount' => 'decimal',
                    'recurring' => 'boolean',
                    'sender_guid' => 'varint',
                    'status' => 'text'
                ],
                'primaryKeys' => [
                    'receiver_guid',
                    'method',
                    'timestamp',
                    'entity_guid',
                    'wire_guid',
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (method ASC, timestamp ASC, entity_guid ASC, wire_guid ASC)'
                ],
                'indexes' => [
                    [ 'alias' => 'wire_entity_guid_idx', 'expr' => 'entity_guid' ],
                    [ 'alias' => 'wire_sender_guid_idx', 'expr' => 'sender_guid' ]
                ]
            ],
            'blockchain_pending' => [
                'schema' => [
                    'type' => 'text',
                    'tx_id' => 'text',
                    'sender_guid' => 'varint',
                    'data' => 'text',
                ],
                'primaryKeys' => [
                    'type',
                    'tx_id'
                ],
                'attributes' => [
                    'compaction = {\'class\': \'org.apache.cassandra.db.compaction.LeveledCompactionStrategy\'}'
                ]
            ],
            'blockchain_transactions_mainnet' => [
                'schema' => [
                    'user_guid' => 'varint',
                    'timestamp' => 'timestamp',
                    'wallet_address' => 'text',
                    'tx' => 'text',
                    'ammount' => 'varint',
                    'completed' => 'boolean',
                    'failed' => 'boolean',
                    'contract' => 'text',
                    'data' => 'text',
                ],
                'primaryKeys' => [
                    'user_guid',
                    'timestamp',
                    'wallet_address',
                    'tx'
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (timestamp DESC, wallet_address ASC, tx ASC)',
                    'bloom_filter_fp_chance = 0.01',
                    'caching = {\'keys\': \'ALL\', \'rows_per_partition\': \'NONE\'}',
                    'comment = \'\'',
                    'compaction = {\'class\': \'org.apache.cassandra.db.compaction.SizeTieredCompactionStrategy\', \'max_threshold\': \'32\', \'min_threshold\': \'4\'}',
                    'compression = {\'chunk_length_in_kb\': \'64\', \'class\': \'org.apache.cassandra.io.compress.LZ4Compressor\'}',
                    'crc_check_chance = 1.0',
                    'dclocal_read_repair_chance = 0.1',
                    'default_time_to_live = 0',
                    'gc_grace_seconds = 864000',
                    'max_index_interval = 2048',
                    'memtable_flush_period_in_ms = 0',
                    'min_index_interval = 128',
                    'read_repair_chance = 0.0',
                    'speculative_retry = \'99PERCENTILE\'',
                ]
            ],
            'withdrawals' => [
                'schema' => [
                    'user_guid' => 'varint',
                    'timestamp' => 'timestamp',
                    'amount' => 'decimal',
                    'tx' => 'text',
                    'completed' => 'boolean'
                    ],
                'primaryKeys' => [
                    'user_guid',
                    'timestamp',
                    'tx'
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (timestamp DESC)'
                ]
            ],
            'reports' => [
                'schema' => [
                    'guid' => 'varint',
                    'entity_guid' => 'varint',
                    'time_created' => 'timestamp',
                    'reporter_guid' => 'varint',
                    'owner_guid' => 'varint',
                    'state' => 'text',
                    'action' => 'text',
                    'reason' => 'text',
                    'reason_note' => 'text',
                    'appeal_note' => 'text',
                ],
                'primaryKeys' => [
                    'guid'
                ]
            ],
            'boosts' => [
                'schema' => [
                    'type' => 'text',
                    'guid' => 'varint',
                    'owner_guid' => 'varint',
                    'destination_guid' => 'varint',
                    'mongo_id' => 'text',
                    'state' => 'text',
                    'data' => 'text',
                ],
                'primaryKeys' => [
                    'type',
                    'guid'
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (type ASC, guid ASC)'
                ]
            ],
            'trending' => [
                'schema' => [
                    'type' => 'text',
                    'place' => 'int',
                    'guid' => 'varint',
                ],
                'primaryKeys' => [
                    'type',
                    'place'
                ],
                'attributes' => [
                    'compaction = {\'class\': \'org.apache.cassandra.db.compaction.LeveledCompactionStrategy\'}',
                    'CLUSTERING ORDER BY (place ASC)'
                ]
            ],
            'subscriptions' => [
                'schema' => [
                    'plan_id' => 'text',
                    'payment_method' => 'text',
                    'entity_guid' => 'varint',
                    'user_guid' => 'varint',
                    'amount' => 'decimal',
                    'last_billing' => 'timestamp',
                    'next_billing' => 'timestamp',
                    'interval' => 'text',
                    'status' => 'text',
                    'subscription_id' => 'text',
                ],
                'primaryKeys' => [
                    'plan_id',
                    'payment_method',
                    'entity_guid',
                    'user_guid',
                    'subscription_id'
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (payment_method ASC, entity_guid ASC, user_guid ASC)'
                ]
            ],
            'payments' => [
                'schema' => [
                    'type' => 'text',
                    'user_guid' => 'varint',
                    'time_created' => 'timestamp',
                    'payment_id' => 'text',
                    'amount' => 'decimal',
                    'description' => 'text',
                    'payment_method' => 'text',
                    'status' => 'text',
                    'subscription_id' => 'text',
                ],
                'primaryKeys' => [
                    'type',
                    'user_guid',
                    'time_created',
                    'payment_id'
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (user_guid ASC, time_created DESC, payment_id ASC)'
                ]
            ],
            'locks' => [
                'schema' => [
                    'key' => 'text',
                ],
                'primaryKeys' => ['key']
            ]
        ];

        // CQL Materialized Views

        $cqlMaterializedViews = [
            'wire_by_sender' => [
                'from' => 'wire',
                'select' => [
                    'sender_guid',
                    'method',
                    'receiver_guid',
                    'timestamp',
                    'entity_guid',
                    'wire_guid',
                    'amount',
                ],
                'conditions' => [
                    'receiver_guid IS NOT NULL',
                    'method IS NOT NULL',
                    'timestamp IS NOT NULL',
                    'entity_guid IS NOT NULL',
                    'wire_guid IS NOT NULL',
                    'sender_guid IS NOT NULL',
                ],
                'primaryKeys' => [
                    'sender_guid',
                    'method',
                    'receiver_guid',
                    'timestamp',
                    'entity_guid',
                    'wire_guid',
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (method ASC, receiver_guid ASC, timestamp ASC, entity_guid ASC, wire_guid ASC)'
                ]
            ],
            'wire_by_entity' => [
                'from' => 'wire',
                'select' => [
                    'entity_guid',
                    'method',
                    'sender_guid',
                    'timestamp',
                    'receiver_guid',
                    'wire_guid',
                    'amount',
                ],
                'conditions' => [
                    'receiver_guid IS NOT NULL',
                    'method IS NOT NULL',
                    'timestamp IS NOT NULL',
                    'entity_guid IS NOT NULL',
                    'wire_guid IS NOT NULL',
                    'sender_guid IS NOT NULL',
                ],
                'primaryKeys' => [
                    'entity_guid',
                    'method',
                    'sender_guid',
                    'timestamp',
                    'receiver_guid',
                    'wire_guid',
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (method ASC, sender_guid ASC, timestamp ASC, receiver_guid ASC, wire_guid ASC)'
                ]
            ],
            'plans_by_subscription_id' => [
                'from' => 'plans',
                'select' => [
                    '*',
                ],
                'conditions' => [
                    'entity_guid IS NOT NULL',
                    'plan IS NOT NULL',
                    'user_guid IS NOT NULL',
                    'status IS NOT NULL',
                    'subscription_id IS NOT NULL',
                ],
                'primaryKeys' => [
                    'subscription_id',
                    'plan',
                    'user_guid',
                    'entity_guid',
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (plan ASC, user_guid ASC, entity_guid ASC)'
                ]
            ],
            'plans_by_user_guid' => [
                'from' => 'plans',
                'select' => [
                    '*',
                ],
                'conditions' => [
                    'entity_guid IS NOT NULL',
                    'plan IS NOT NULL',
                    'user_guid IS NOT NULL',
                    'status IS NOT NULL',
                ],
                'primaryKeys' => [
                    'user_guid',
                    'plan',
                    'entity_guid',
                    'status',
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (plan ASC, entity_guid ASC, status ASC)'
                ]
            ],
            'blockchain_transactions_by_address' => [
                'from' => 'blockchain_transactions_mainnet',
                'select' => [
                    'wallet_address',
                    'user_guid',
                    'timestamp',
                    'tx',
                    'amount',
                    'completed',
                    'failed',
                    'data',
                ],
                'conditions' => [
                    'user_guid IS NOT NULL',
                    'wallet_address IS NOT NULL',
                    'timestamp IS NOT NULL',
                    'tx IS NOT NULL'
                ],
                'primaryKeys' => [
                    'wallet_address',
                    'user_guid',
                    'timestamp',
                    'tx',
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (user_guid DESC, timestamp DESC, tx ASC)',
                    'bloom_filter_fp_chance = 0.01',
                    'caching = {\'keys\': \'ALL\', \'rows_per_partition\': \'NONE\'}',
                    'comment = \'\'',
                    'compaction = {\'class\': \'org.apache.cassandra.db.compaction.SizeTieredCompactionStrategy\', \'max_threshold\': \'32\', \'min_threshold\': \'4\'}',
                    'compression = {\'chunk_length_in_kb\': \'64\', \'class\': \'org.apache.cassandra.io.compress.LZ4Compressor\'}',
                    'crc_check_chance = 1.0',
                    'dclocal_read_repair_chance = 0.1',
                    'default_time_to_live = 0',
                    'gc_grace_seconds = 864000',
                    'max_index_interval = 2048',
                    'memtable_flush_period_in_ms = 0',
                    'min_index_interval = 128',
                    'read_repair_chance = 0.0',
                    'speculative_retry = \'99PERCENTILE\'',
                ]
            ],
            'reports_by_owner' => [
                'from' => 'reports',
                'select' => [
                    '*',
                ],
                'conditions' => [
                    'owner_guid IS NOT NULL',
                    'guid IS NOT NULL',
                ],
                'primaryKeys' => [
                    'owner_guid',
                    'guid',
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (guid DESC)'
                ]
            ],
            'reports_by_state' => [
                'from' => 'reports',
                'select' => [
                    '*',
                ],
                'conditions' => [
                    'state IS NOT NULL',
                    'guid IS NOT NULL',
                ],
                'primaryKeys' => [
                    'state',
                    'guid',
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (guid ASC)'
                ]
            ],
            'boosts_by_owner' => [
                'from' => 'boosts',
                'select' => [
                    '*',
                ],
                'conditions' => [
                    'type IS NOT NULL',
                    'owner_guid IS NOT NULL',
                    'guid IS NOT NULL',
                ],
                'primaryKeys' => [
                    'type', 'owner_guid', 'guid'
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (owner_guid ASC, guid DESC)'
                ]
            ],
            'boosts_by_destination' => [
                'from' => 'boosts',
                'select' => [
                    '*',
                ],
                'conditions' => [
                    'type IS NOT NULL',
                    'destination_guid IS NOT NULL',
                    'guid IS NOT NULL',
                ],
                'primaryKeys' => [
                    'type', 'destination_guid', 'guid'
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (destination_guid ASC, guid DESC)'
                ]
            ],
            'boosts_by_mongo_id' => [
                'from' => 'boosts',
                'select' => [
                    '*',
                ],
                'conditions' => [
                    'type IS NOT NULL',
                    'mongo_id IS NOT NULL',
                    'guid IS NOT NULL',
                ],
                'primaryKeys' => [
                    'type', 'mongo_id', 'guid'
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (mongo_id ASC, guid ASC)'
                ]
            ],
            'recurring_subscriptions_by_subscription_id' => [
                'from' => 'recurring_subscriptions',
                'select' => [ '*' ],
                'conditions' => [
                    'type IS NOT NULL',
                    'payment_method IS NOT NULL',
                    'entity_guid IS NOT NULL',
                    'user_guid IS NOT NULL',
                    'subscription_id IS NOT NULL'
                ],
                'primaryKeys' => [
                    'subscription_id', 'type', 'payment_method', 'entity_guid', 'user_guid'
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (type ASC, payment_method ASC, entity_guid ASC, user_guid ASC)'
                ]
            ],
            'recurring_subscriptions_by_user_guid' => [
                'from' => 'recurring_subscriptions',
                'select' => [ '*' ],
                'conditions' => [
                    'type IS NOT NULL',
                    'payment_method IS NOT NULL',
                    'entity_guid IS NOT NULL',
                    'user_guid IS NOT NULL'
                ],
                'primaryKeys' => [
                    'user_guid', 'type', 'payment_method', 'entity_guid'
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (type ASC, payment_method ASC, entity_guid ASC)'
                ]
            ],
            'payments_by_subscription_id' => [
                'from' => 'payments',
                'select' => [ '*' ],
                'conditions' => [
                    'type IS NOT NULL',
                    'user_guid IS NOT NULL',
                    'time_created IS NOT NULL',
                    'payment_id IS NOT NULL',
                    'subscription_id IS NOT NULL'
                ],
                'primaryKeys' => [
                    'subscription_id', 'type', 'user_guid', 'time_created', 'payment_id'
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (type ASC, user_guid ASC, time_created DESC, payment_id ASC)'
                ]
            ],
            'payments_by_payment_id' => [
                'from' => 'payments',
                'select' => [ '*' ],
                'conditions' => [
                    'type IS NOT NULL',
                    'user_guid IS NOT NULL',
                    'time_created IS NOT NULL',
                    'payment_id IS NOT NULL'
                ],
                'primaryKeys' => [
                    'payment_id', 'type', 'user_guid', 'time_created'
                ],
                'attributes' => [
                    'CLUSTERING ORDER BY (type ASC, user_guid ASC, time_created DESC)'
                ]
            ],
        ];

        // Apply

        $client = $this->client ?: Data\Client::build('Cassandra', [
            $config->keyspace,
            $config->cql_servers
        ]);

        // - Apply tables
        foreach ($cqlTables as $cqlTableName => $cqlTable) {
            $query = new Data\Cassandra\Prepared\System();
            $client->request($query->createTable(
                $cqlTableName,
                $cqlTable['schema'],
                $cqlTable['primaryKeys'],
                isset($cqlTable['attributes']) ? $cqlTable['attributes'] : []
            ));

            if (isset($cqlTable['indexes'])) {
                foreach ($cqlTable['indexes'] as $index) {
                    $query = new Data\Cassandra\Prepared\System();
                    $client->request($query->createIndex(
                        $cqlTableName,
                        $index
                    ));
                }
            }
        }

        // - Apply materialized views
        foreach ($cqlMaterializedViews as $cqlMaterializedViewName => $cqlMaterializedView) {
            $query = new Data\Cassandra\Prepared\System();
            $client->request($query->createMaterializedView(
                $cqlMaterializedViewName,
                $cqlMaterializedView['from'],
                $cqlMaterializedView['select'],
                $cqlMaterializedView['conditions'],
                $cqlMaterializedView['primaryKeys'],
                isset($cqlMaterializedView['attributes']) ? $cqlMaterializedView['attributes'] : []
            ));
        }

        return true;
    }

    private function thriftLegacySchema()
    {
        return [
            'schema' => [
                'key' => 'varchar',
                'column1' => 'varchar',
                'value' => 'varchar',
            ],
            'primaryKeys' => ['key', 'column1'],
            'attributes' => [
                'COMPACT STORAGE'
            ]
        ];
    }
}
