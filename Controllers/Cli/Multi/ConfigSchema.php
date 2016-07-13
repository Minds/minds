<?php

namespace Minds\Controllers\Cli\Multi;

use Minds\Core\Di\Di;
use Minds\Core\Data;
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
        $db = new Data\Call(null, $this->config->multi['cassandra']->keyspace);
        $db->installSchema();
    }
}
