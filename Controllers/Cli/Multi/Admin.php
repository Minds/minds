<?php

namespace Minds\Controllers\Cli\Multi;

use Minds\Core\Di\Di;
use Minds\Entities\Multi\Site;
use Minds\Interfaces;
use Minds\Cli\Factory;

class Admin implements Interfaces\CliControllerInterface
{

    public function exec(array $args = [])
    {

        if($args[0] == "--help"){
            echo "[opts]: --domain --name \n";
            exit;
        }

        $config = Di::_()->get('Config');
        $opts = Factory::getOpts(['domain', 'name', 'username', 'email', 'password'], $args);

        switch($args[0]){
            case "create":
                $site = new Site();
                $site->loadFromDomain($opts['domain']);

                $provisioner = Di::_()->get('Multi\Provisioner');
                $provisioner->setSite($site);

                $success = $provisioner->setupAdmin($opts['username'], $opts['email'], $opts['password']);

                if(!$success){
                    throw new \Exception("The user could not be saved");
                }
                break;
        }

        echo "\n";
    }

}
