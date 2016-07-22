<?php

namespace Minds\Controllers\Cli\Multi;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Cli;

class ConfigSchema extends Cli\Controller implements Interfaces\CliControllerInterface
{
    protected $config;

    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }

    public function exec(array $args = [])
    {
        $options = [
            'cassandra-keyspace' => $this->config->multi['cassandra']->keyspace,
            'cassandra-server' => $this->config['cassandra-server'],
            'cassandra-replication-factor' => $this->config['cassandra-replication-factor'],
        ];

        (new Core\Provisioner\Provisioners\CassandraProvisioner)->provision($options);
    }
}
