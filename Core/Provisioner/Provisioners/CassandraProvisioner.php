<?php
namespace Minds\Core\Provisioner\Provisioners;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Exceptions\ProvisionException;

class CassandraProvisioner implements ProvisionerInterface
{
    public function provision(array $options = [])
    {
        $db = new Data\Call(
            null,
            $options['cassandra-keyspace'],
            [ $options['cassandra-server'] ]
        );

        if ($db->keyspaceExists()) {
            throw new ProvisionException('Cassandra storage is already provisioned');
        }
        
        $db->createKeyspace([
            'strategy_options' => [
                'replication_factor' => $options['cassandra-replication-factor'] ?: '3'
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
        ];

        $client = Data\Client::build('Cassandra', [
            'keyspace' => $options['cassandra-keyspace'],
            'cql_servers' => [ $options['cassandra-server'] ]
        ]);

        foreach ($cqlTables as $cqlTableName => $cqlTable) {
            $query = new Data\Cassandra\Prepared\System();
            $client->request($query->createTable(
                $cqlTableName,
                $cqlTable['schema'],
                $cqlTable['primaryKeys'],
                isset($cqlTable['attributes']) ? $cqlTable['attributes'] : []
            ));
        }
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
