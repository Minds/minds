<?php

namespace Minds\Controllers\Cli\Multi;

use Minds\Core\Di\Di;
use Minds\Entities\Multi\Site;
use Minds\Interfaces;
use Minds\Cli;
use Minds\Exceptions\CliException;

class Admin extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function help()
    {
        $this->out('[opts]: --domain --username --email --password');
    }

    public function exec()
    {
        $config = Di::_()->get('Config');

        if (!isset($this->args[0])) {
            throw new CliException('Specify a subcommand');
        }

        switch($this->args[0]){
            case "create":
                $opts = $this->getOpts(['domain', 'username', 'email', 'password']);

                $site = new Site();
                $site->loadFromDomain($opts['domain']);

                $provisioner = Di::_()->get('Multi\Provisioner');
                $provisioner->setSite($site);

                $success = $provisioner->setupAdmin($opts['username'], $opts['email'], $opts['password']);

                if (!$success) {
                    throw new CliException("The user could not be saved");
                }
                break;
        }
    }
}
