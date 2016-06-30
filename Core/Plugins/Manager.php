<?php
/**
 * Handles plugins for Minds
 *
 */
namespace Minds\Core\Plugins;

use Minds\Core\Di\Di;

class Manager
{

    protected $config;

    protected $path = "";
    protected $enabled = [];

    /**
     * Construct plugins
     */
    public function __construct($config = NULL)
    {
        $this->config = $config ?: Di::_()->get('Config');

        $this->path = dirname(__MINDS_ROOT__) . "/plugins/";

    }

    public function init()
    {
        $this->initPlugins();
    }

    /**
     * Returns all available plugins in the plugin directory
     *
     * @param string $dir - the directory to discover from
     * @return array
     */
    private static function getFromDir()
    {

        $plugin_ids = [];
        $handle = opendir($this->path);

        if ($handle) {
            while ($plugin_id = readdir($handle)) {
                // must be directory and not begin with a .
                if (substr($plugin_id, 0, 1) !== '.' && is_dir($dir . $plugin_id)) {
                    $plugin_ids[] = $plugin_id;
                }
            }
        }

        return $plugin_ids;
    }

    protected function initPlugins()
    {
        $plugins = $this->config->get('plugins');
        if (!$plugins){
          return;
        }

        foreach ($plugins as $id) {
            $plugin = self::buildPlugin($id);
            try {
                $plugin->start();
                $plugin->init();
            } catch (Exception $e) {}
        }

        \elgg_trigger_event('plugins_loaded', 'plugin');
    }

    /**
     * Factory to load a plugin entity
     * @param string/int $guid - the name of the plugin (ie. in the plugin directory)
     * @param array $data - if passed, it will the load the plugin with settings information
     *
     * @return object
     */
     public static function buildPlugin($name, $data = null)
     {
         if (!$data) {
             $data = $name;
         }

         $class = "\\Minds\\Plugin\\$name\\start";
         if (class_exists($class)) {
             return new $class($data);
         } else {
             //support legacy plugins
            return new \ElggPlugin($data);
         }
     }
}
