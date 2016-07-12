<?php

namespace Minds\Controllers\Cli\Multi;

use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Cli\Factory;

class Install implements Interfaces\CliControllerInterface
{

    public function exec(array $args = [])
    {

        if($args[0] == "--help"){
            echo "[opts]: --domain --name \n";
            exit;
        }

        $opts = Factory::getOpts(['domain', 'name'], $args);

        if(!$opts['domain']){
            throw new \Exception('Domain needs to be supplied');
        }

        $site = new Entities\Multi\Site();
        $site->setDomain($opts['domain']);

        $provisioner = Di::_()->get('Multi\Provisioner');
        $provisioner->setSite($site)
          ->install([]);

        $site->save();

        echo "\n";
    }

}
