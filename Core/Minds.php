<?php
namespace Minds\Core;

/**
 * Core Minds Engine
 */
class Minds extends base
{
    public $root = __MINDS_ROOT__;
    public $legacy_lib_dir = "/lib/";
    public static $booted = false;

    /**
     * Initializes the site
     * @return null
     */
    public function init()
    {
        $this->initProviders();
    }

    /**
     * Register our DI providers
     * @return null
     */
    public function initProviders()
    {
        (new Config\ConfigProvider())->register();
        //(new Core\Boost\BoostProvider())->register();
        (new Plugins\PluginsProvider())->register();
        (new Data\DataProvider())->register();
        (new Email\EmailProvider())->register();
        //(new Core\Events\EventsProvider())->register();
        //(new Core\Notification\NotificationProvider())->register();
        (new Pages\PagesProvider())->register();
        (new Payments\PaymentsProvider())->register();
        (new Queue\QueueProvider())->register();
        (new Security\SecurityProvider())->register();
        (new Http\HttpProvider())->register();
        (new Translation\TranslationProvider())->register();
    }

    /**
     * Start the Minds engine
     * @return null
     */
    public function start()
    {
        $this->checkInstalled();

        $this->loadConfigs();
        $this->loadLegacy();

        /*
         * If this is a multisite, then load the specific database settings
         */
        if ($this->detectMultisite()) {
            new multisite();
        }

        /**
         * Load session info
         */
        new Session();

        Security\XSRF::setCookie();

        Events\Defaults::_();
        new SEO\Defaults(static::$di->get('Config'));

        /**
         * Boot the system, @todo this should be oop?
         */
        \elgg_trigger_event('boot', 'system');

        /**
         * Load the plugins
         */
        static::$di->get('Plugins\Manager')->init();

        /**
         * Complete the boot process for both engine and plugins
         */
        elgg_trigger_event('init', 'system');

        /**
         * tell the system that we have fully booted
         */
        self::$booted = true;

        /**
         * System loaded and ready
         */
        \elgg_trigger_event('ready', 'system');
    }

    /**
     * Load settings files
     * @return null
     */
    public function loadConfigs()
    {
        global $CONFIG;
        if (!isset($CONFIG)) {
            $CONFIG = static::$di->get('Config');
        }

        // Load the system settings
        if (file_exists(__MINDS_ROOT__ . '/settings.php')) {
            include_once(__MINDS_ROOT__ . "/settings.php");
        }

        // Load mulit globals if set
        if (file_exists(__MINDS_ROOT__ . '/multi.settings.php')) {
            define('multisite', true);
            require_once(__MINDS_ROOT__ . '/multi.settings.php');
        }
    }


    /**
     * Load the legacy files for Elgg framework
     * @todo Deprecate this
     */
    public function loadLegacy()
    {
        // load the rest of the library files from engine/lib/
        $lib_files = array(
            'elgglib.php', 'access.php',
            'configuration.php', 'cron.php',
            'entities.php', 'extender.php', 'filestore.php', 'group.php',
            'input.php', 'languages.php', 'location.php',
            'memcache.php',
            'notification.php', 'objects.php', 'output.php',
            'pagehandler.php', 'pageowner.php', 'pam.php', 'plugins.php',
            'private_settings.php', 'sessions.php',
            'sites.php', 'statistics.php',
            'user_settings.php', 'users.php', 'views.php',
            'widgets.php', 'xml.php', 'xml-rpc.php'
        );

        foreach ($lib_files as $file) {
            $file = __MINDS_ROOT__ . $this->legacy_lib_dir . $file;
            if (!include_once($file)) {
                $msg = "Could not load $file";
                throw new \InstallationException($msg);
            }
        }
    }

    /**
     * Detects if there are multisite settings present
     * @return bool
     */
    public function detectMultisite()
    {
        if (file_exists(__MINDS_ROOT__ . '/multi.settings.php')) {
            return true;
        }

        return false;
    }

    /**
     * Check if Minds is installed, if not redirect to install script
     * @return null
     */
    public function checkInstalled()
    {
        /**
         * If we are a multisite, we get the install status from the multisite settings
         */
        if ($this->detectMultisite()) {
            //we do this on db load.. not here
        } else {
            if (!file_exists(__MINDS_ROOT__ . '/settings.php') && !defined('__MINDS_INSTALLING__')) {
                header("Location: install.php");
                exit;
            }
        }
    }

    /**
     * TBD. Not used
     * @return bool
     */
    public static function getVersion()
    {
        return false;
    }
}
