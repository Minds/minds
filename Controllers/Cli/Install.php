<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Cli;
use Minds\Interfaces;
use Minds\Exceptions;
use Minds\Exceptions\ProvisionException;

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
            $provisioner = new Core\Provisioner\Installer();
            $provisioner
                ->setApp($this->getApp())
                ->setOptions($this->getAllOpts());

            $this->out('- Checking passed options:', $this::OUTPUT_INLINE);
            $provisioner->checkOptions();
            $this->out('OK');

            if ($this->getOpt('use-existing-settings')) {
                $this->out('- Fetching settings:', $this::OUTPUT_INLINE);
                $provisioner->checkSettingsFile();
                $this->getApp()->loadConfigs();
                $this->out('OK');
            } else {
                $this->out('- Building configuration file:', $this::OUTPUT_INLINE);
                $provisioner->buildConfig();
                $this->out('OK');

                $this->out('- Loading new configuration:', $this::OUTPUT_INLINE);
                $this->getApp()->loadConfigs();
                $this->out('OK');
            }

            $newStorage = false;

            try {
                $this->out('- Setting up data storage (ignore errors, if any):', $this::OUTPUT_INLINE);
                $provisioner->setupStorage();
                $this->out('OK');

                $this->out('- Emptying Cassandra pool:', $this::OUTPUT_INLINE);
                $provisioner->reloadStorage();
                $this->out('OK');

                $newStorage = true;
            } catch (ProvisionException $e) {
                if ($this->getOpt('graceful-storage-provision')) {
                    $this->out('ALREADY SETUP');
                } else {
                    throw $e;
                }
            }

            if ($newStorage) {
                $this->out('- Setting up site:', $this::OUTPUT_INLINE);
                $provisioner->setupSite();
                $this->out('OK');

                $this->out('- Setting up administrative user (ignore warnings, if any):', $this::OUTPUT_INLINE);
                $provisioner->setupFirstAdmin();
                $this->out('OK');
            }

            $this->out(['Done!', 'Open your browser and go to ' . $provisioner->getSiteUrl()], $this::OUTPUT_PRE);
        } catch (Exceptions\ProvisionException $e) {
            throw new Exceptions\CliException($e->getMessage());
        }
    }

    public function keys()
    {
        $target = '/.dev' . DIRECTORY_SEPARATOR;
        $ssl = openssl_pkey_new([
            "digest_alg" => "sha512",
            "private_key_bits" => 4096,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);
        
        openssl_pkey_export($ssl, $privateKey);
        $publicKey = openssl_pkey_get_details($ssl)['key'];
        
        mkdir($target);
        file_put_contents("{$target}minds.pem", $privateKey);
        file_put_contents("{$target}minds.pub", $publicKey);

        $this->out("Keys done {$target}minds.pem");
    }
}
