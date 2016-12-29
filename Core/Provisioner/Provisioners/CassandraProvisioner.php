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

        // Thrift ColumnFamilies

        $columnFamilies = [
            'plugin' => ['active' => 'IntegerType'], // TODO: still used?
            'entities'=> ['type' => 'UTF8Type'],
            'entities_by_time' => [],
            'user_index_to_guid' => [],
            'session' => [],
            'friends' => [], // TODO: Will replace with relationships?
            'friendsof' => [], // TODO: Will replace with relationships?
            'relationships' => [],
        ];

        $keyspace = $db->describeKeyspace();

        foreach ($columnFamilies as $columnFamily => $indexes) {
            $exists = false;

            foreach ($keyspace->cf_defs as $keyspaceCfs) {
                if ($keyspaceCfs->name == $columnFamily) {
                    $exists = true;
                    break;
                }
            }

            if (!$exists) {
                $db->createCF($columnFamily, $indexes);
            }
        }

        // CQL Tables

        $cqlTables = [
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
            $client->request($query->createTable($cqlTableName, $cqlTable['schema'], $cqlTable['primaryKeys']));
        }
    }
}
