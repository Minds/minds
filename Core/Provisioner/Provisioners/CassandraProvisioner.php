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

        // Apply

        $client = $this->client ?: Data\Client::build('Cassandra', [
            'keyspace' => 'system',
            $config->cql_servers
        ]);

        $cql = file_get_contents(dirname(__FILE__) . '/cassandra-provision.cql');
        $statements = explode("\n\n", $cql);

        try {
            foreach ($statements as $statement) {
                $client->execute($statement);
            }
        } catch (\Exception $e) {
            var_dump($e); exit;
        }
 
        return true;

    }

}
