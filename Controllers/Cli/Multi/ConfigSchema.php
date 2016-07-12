<?php

namespace Minds\Controllers\Cli\Multi;

use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Cli\Factory;

class ConfigSchema implements Interfaces\CliControllerInterface
{

    public function exec(array $args = [])
    {

        if($args[0] == "--help"){
            echo "[opts]: --domain --name \n";
            exit;
        }

        $config = Di::_()->get('Config');

        $db = new Data\Call(null, $config->multi['cassandra']->keyspace);
        $db->installSchema();

        echo "\n";
    }

}
