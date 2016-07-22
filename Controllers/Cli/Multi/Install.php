<?php

namespace Minds\Controllers\Cli\Multi;

use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Cli;
use Minds\Exceptions\CliException;

class Install extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function help($command = null)
    {
        $this->out('[opts]: --domain --name');
    }

    public function exec()
    {
        $opts = $this->getOpts(['domain', 'name']);

        if (!$opts['domain']) {
            throw new CliException('Specify a domain name');
        }

        $site = new Entities\Multi\Site();
        $site->setDomain($opts['domain']);
        $site->setName($opts['name'] ?: 'Minds');

        $provisioner = Di::_()->get('Multi\Provisioner');
        $provisioner
            ->setSite($site)
            ->install([]);

        $site->save();
    }
}
