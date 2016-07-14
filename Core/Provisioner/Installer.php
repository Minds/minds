<?php
namespace Minds\Core\Provisioner;

use Minds\Core;
use Minds\Entities;
use Minds\Exceptions\ProvisionException;
use \ElggSite;

class Installer
{
    protected $app;

    protected $defaults = [];
    protected $options = [];

    public function __construct()
    {
        $this->defaults = [
            'cassandra-keyspace' => 'minds',
            'cassandra-server' => '127.0.0.1',
            'cassandra-replication-factor' => '3',
            'dataroot' => '/data/',
            'default-site' => 1,
            'cache-path' => '/tmp/minds-cache/',
            'elasticsearch-server' => 'http://localhost:9200/',
            'elasticsearch-prefix' => 'mehmac_',
            'facebook-app-id' => '',
            'facebook-app-secret' => '',
            'google-api-key' => '',
            'site-name' => 'Minds',
            'site-email' => 'root@localhost',
            'no-https' => false,
        ];

        usleep(mt_rand(1, 9999));
        $this->defaults['site-secret'] = md5(microtime() . mt_rand());

        usleep(mt_rand(1, 9999));
        $this->defaults['jwt-secret'] = md5(microtime() . mt_rand());
    }

    public function setApp($app)
    {
        $this->app = $app;
        return $this;
    }

    public function setOptions(array $options = []) {
        $this->options = array_merge($this->defaults, $options);
        return $this;
    }

    public function checkOptions()
    {
        if (!isset($this->options['domain'])) {
            throw new ProvisionException('Domain name was not provided');
        }

        if (!isset($this->options['username'])) {
            throw new ProvisionException('Admin username was not provided');
        }

        if (!isset($this->options['password'])) {
            throw new ProvisionException('Admin password was not provided');
        }

        if (!isset($this->options['email'])) {
            throw new ProvisionException('Admin email was not provided');
        }

        // TODO: Check parameters formatting (specially domains, urls or numbers)
    }

    public function buildConfig()
    {
        $source = $this->app->root . DIRECTORY_SEPARATOR . 'settings.example.php';
        $target = $this->app->root . DIRECTORY_SEPARATOR . 'settings.php';

        if (is_file($target) && !isset($this->options['overwrite-settings'])) {
            throw new ProvisionException('Minds is already installed');
        }

        $template = file_get_contents($source);

        // Build options
        if (!isset($this->options['path'])) {
            $this->options['path'] = dirname($this->app->root) . DIRECTORY_SEPARATOR;
        }

        if (!isset($this->options['jwt-domain'])) {
            $this->options['jwt-domain'] = $this->options['domain'];
        }

        if (!isset($this->options['socket-server-uri'])) {
            $this->options['socket-server-uri'] = $this->options['domain'] . ':8010';
        }

        // Inject options

        $result = preg_replace_callback('/\{\{([a-z0-9\-_]+)\}\}/', function($matches) {
            if (!isset($this->options[$matches[1]])) {
                throw new ProvisionException("Configuration key `{$matches[1]}` is not present on defaults or command line arguments");
            }

            return (string) $this->options[$matches[1]];
        }, $template);

        // Write template
        file_put_contents($target, $result);
    }

    public function setupStorage(Provisioners\ProvisionerInterface $storage = null)
    {
        // TODO: DI?
        $storage = $storage ?: new Provisioners\CassandraProvisioner();
        $storage->provision($this->options);
    }

    public function setupSite()
    {
        $site = new ElggSite();
        $site->name = $this->options['site-name'];
        $site->url = $this->getSiteUrl();
        $site->access_id = ACCESS_PUBLIC;
        $site->email = $this->options['site-email'];

        $done = $site->save();

        if (!$done) {
            throw new ProvisionException('Cannot create Site entity');
        }
    }

    public function setupFirstAdmin()
    {
        $guid = register_user(
            $this->options['username'],
            $this->options['password'],
            $this->options['username'],
            $this->options['email']
        );

        if (!$guid) {
            throw new ProvisionException('Cannot create new User entity');
        }
    
        $user = new Entities\User($guid);
        $user->admin = 'yes';
        $user->validated = true;
        $user->validated_method = 'admin_user';
        $userSaved = $user->save();

        if (!$userSaved) {
            throw new ProvisionException('Cannot grant privileges to new User entity');
        }

        $activity = new Entities\Activity();
        $activity->owner_guid = $guid;
        $activity->setMessage('Hello Minds!');
        $activitySaved = $activity->save();

        if (!$activitySaved) {
            throw new ProvisionException('Cannot create first Activity entity');
        }

        // TODO: Maybe grant 1M points to admin?
    }

    public function getSiteUrl()
    {
        $siteUrl = $this->options['no-https'] ? 'http' : 'https';
        $siteUrl .= '://' . $this->options['domain'] . '/';

        return $siteUrl;
    }
}
