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
        $this->out('Configures web server and provisions and sets up databases for the minds application.');
        $this->out('use-existing-settings: uses the existing settings in settings.php.');
        $this->out('only=[keys|site|cassandra|cockroach] to set up individual components.');
        $this->out('cleanCassandra cleanCockroach: deletes and recreates db.');
        $this->out('graceful-storage-provision: causes installation to proceed past storage (db) failures.');
    }

    public function exec()
    {
        try {
            $provisioner = new Core\Provisioner\Installer();
            $provisioner
                ->setApp($this->getApp())
                ->setOptions($this->getAllOpts());

            // If flagged, use existing settings, otherwise build from template.
            if ($this->getOpt('use-existing-settings')) {
                $this->out('- Fetching settings:', $this::OUTPUT_INLINE);
                $provisioner->checkSettingsFile();
            } else {
                $this->out('- Building configuration file:', $this::OUTPUT_INLINE);
                $provisioner->buildConfig();
                $this->out('OK');
            }
            $this->out('- Loading new configuration:', $this::OUTPUT_INLINE);
            $this->getApp()->loadConfigs();
            $this->out('OK');

            // TODO: List setup parameters flag.

            // REVNOTE: Moved to after the other configuration loaders, in order to parameter check
            // values arriving from those sources.
            $this->out('- Checking install options:', $this::OUTPUT_INLINE);
            $provisioner->checkOptions();
            $this->out('OK');

            // only=[keys|cassandra|cockroach|site]
            $installOnly = $this->getopt('only');
            $installType = $installOnly ? $installOnly : "all";

            if ($installType == "all" || $installType == "keys") {
                $this->keys();
            }

            try {
                if ($installType == "all" || $installType == "cassandra") {
                    $this->out('- Provisioning Cassandra: ', $this::OUTPUT_INLINE);
                    $isCleanCassandra = $this->getopt("cleanCassandra") != null;
                    $provisioner->provisionCassandra(null, $isCleanCassandra);
                    $this->out('OK');

                    $this->out('- Emptying Cassandra pool:', $this::OUTPUT_INLINE);
                    $provisioner->reloadStorage();
                    $this->out('OK');
                }
            } catch (Exception $e) {
                // REVNOTE: This seems unused, currently. None of the database provisioners currently
                // throw ProvisionException. We should maybe catch general exceptions (log them) and continue,
                // and not ProvisionExceptions. I considered removing this altogether, but it is useful to continue
                // past server errors in an setup.
                if ($this->getOpt('graceful-storage-provision')) {
                    $this->out($e->getMessage());
                    $this->out('Error in cassandra setup. Continuing.');
                } else {
                    throw $e;
                }
            }

            try {
                if ($installType == "all" || $installType == "cockroach") {
                    $this->out('- Provisioning Cockroach:', $this::OUTPUT_INLINE);
                    $isCleanCockroach = $this->getopt("cleanCockroach") != null;
                    $provisioner->provisionCockroach(null, $isCleanCockroach);
                    $this->out('OK');
                }
            } catch (Exception $e) {
                // See REVNOTE above.
                if ($this->getOpt('graceful-storage-provision')) {
                    $this->out($e->getMessage());
                    $this->out('Error in cockroach setup. Continuing.');
                } else {
                    throw $e;
                }
            }

            if (($installType == "all") || ($installType == "site")) {
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
