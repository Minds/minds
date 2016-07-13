<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;

class Install extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
        define('__MINDS_INSTALLING__', true);
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }
    
    public function exec()
    {
        try {
            $this->out('Installing Minds...', $this::OUTPUT_PRE);

            $provisioner = new Core\Provisioner();
            $provisioner
                ->setApp($this->getApp())
                ->setOptions($this->getAllOpts()); 

            $this->out('- Checking passed options:', $this::OUTPUT_INLINE);
            $provisioner->checkOptions();
            $this->out('OK');

            $this->out('- Building configuration file:', $this::OUTPUT_INLINE);
            $provisioner->buildConfig();
            $this->out('OK');

            $this->out('- Setting up data storage:', $this::OUTPUT_INLINE);
            $provisioner->setupStorage();
            $this->out('OK');

            $this->out('- Loading new configuration:', $this::OUTPUT_INLINE);
            $this->getApp()->loadConfigs();
            $this->out('OK');

            $this->out('- Setting up site:', $this::OUTPUT_INLINE);
            $provisioner->setupSite();
            $this->out('OK');

            $this->out('- Setting up administrative user:', $this::OUTPUT_INLINE);
            $provisioner->setupFirstAdmin();
            $this->out('OK');

            $this->out(['Done!', 'Open your browser and go to ' . $provisioner->getSiteUrl()], $this::OUTPUT_PRE);
        } catch (Exceptions\ProvisionException $e) {
            throw new Exceptions\CliException($e->getMessage());
        }
    }
}
