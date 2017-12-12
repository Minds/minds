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
                ],
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
