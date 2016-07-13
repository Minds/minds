<?php

namespace Minds\Controllers\Cli\Multi;

use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Cli;

class ConfigSchema extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function help()
    {
        $this->out('TBD');
    }

    public function exec(array $args = [])
    {
        $config = Di::_()->get('Config');

        $db = new Data\Call(null, $config->multi['cassandra']->keyspace);
        $db->installSchema();
    }
}
