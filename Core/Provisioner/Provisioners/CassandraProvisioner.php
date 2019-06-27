<?php
namespace Minds\Core\Provisioner\Provisioners;

use Minds\Core\Di\Di;
use Minds\Core\Data;

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

    public function provision(bool $cleanData)
    {
        // TODO: Add cleanData to provisioner.
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
            error_log("Error provisioning cassandra: " . $e->getMessage());
        }
 
        return true;

    }

}
