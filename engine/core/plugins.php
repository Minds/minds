<?php
/**
 * Handles plugins for Minds
 * 
 */
namespace minds\core;

class plugins extends base{
	
	static public $path;
	static public $cache = array();

	/**
	 * Construct plugins
	 */
	public function __construct(){

		self::$path = __MINDS_ROOT__ . "/mod/";
		$this->load();
		
		\elgg_register_event_handler('init', 'system', array($this, 'initPlugins'));
	}

	/**
	 * Returns all available plugins in the plugin directory
	 * 
	 * @param string $dir - the directory to discover from
	 * @return array
	 */
	static private function getFromDir($dir = NULL){
		if(!$dir){
			$dir = self::$path;
		}
		
		$plugin_ids = array();
		$handle = opendir($dir);
	
		if ($handle) {
			while ($plugin_id = readdir($handle)) {
				// must be directory and not begin with a .
				if (substr($plugin_id, 0, 1) !== '.' && is_dir($dir . $plugin_id)) {
					$plugin_ids[] = $plugin_id;
				}
			}
		}
	
		//does this really need to be sorted?
		sort($plugin_ids);
		return $plugin_ids;
	}
	
	/**
	 * Get plugins
	 * 
	 * @param string $status - eg. active
	 * @return array of objects
	 */
	static public function get($status = 'active'){
		
		//check the in memory cache, in case we already loaded this
		if(isset(self::$cache[$status])){
			return self::$cache[$status];
		} else {
			self::$cache[$status] = array();
		}
	
		$plugins = self::getFromDir();
	
		//check the tmp disk cache, because it reduces a database call
		if($cached = self::getFromCache("plugins:$status")){
		
			$rows = $cached;
		
		} else {
					
			$db = new \minds\core\data\call('plugin');
			$rows = $db->getRows($plugins);
			self::saveToCache("plugins:$status", $rows);
			
		}
	
		foreach($plugins as $key){
			$row = $rows[$key];
	
			//do a quick check to see if the plugin is active, if it's not, then we can skip
			if($status == 'active' && (!isset($row['active']) || $row['active'] != 1))
				continue;
			
			//build the correct entity for the plugin
			$row['guid'] = $key;
			$plugin = self::factory($key, $row);
			
			self::$cache[$status][$key] = $plugin;
			$list[$key] = $plugin;
			
		}
	
		if($list){	
			//now order them since cassandra does not do this
			usort($plugins, function($a, $b) {
				return	 $a->{'elgg:internal:priority'} > $b->{'elgg:internal:priority'};
			});
		} 
		
		return $list;
		
	}
	
	/**
	 * Load plugins
	 */
	private function load(){
		/**
		 * Disables all plugins if a 'disabled' file is found in the directory
		 */
		if (file_exists(self::$path."disabled")) {
			if (\elgg_is_admin_logged_in() && \elgg_in_context('admin')) {
				\system_message(elgg_echo('plugins:disabled'));
			}
			return false;
		}

		$plugins = self::get('active');	
		$return = true;
		if ($plugins) {
			foreach ($plugins as $plugin) {
				try {
					$plugin->start();
				
				} catch (Exception $e) {
					
					$plugin->deactivate();
					$msg = \elgg_echo('PluginException:CannotStart', array(
						$plugin->getID(), 
						$plugin->guid, 
						$e->getMessage()
					));
					
					\elgg_add_admin_notice('cannot_start' . $plugin->getID(), $msg);
					$return = false;
	
					continue;
				}
			}
		}
	
		\elgg_trigger_event('plugins_loaded', 'plugin');
		
		return $return;
	}
	
	public function initPlugins(){
		$plugins = self::get('active');
		foreach($plugins as $plugin)
			$plugin->init();
	}
	
	/**
	 * Check if a plugin is active
	 * @param string $id - the id of the plugin
	 * @return bool
	 */
	public function isActive($id){
		$plugins = self::get('active');
		return isset($plugins[$id]);
		//$plugin = self::factory($id);
		//return $plugin->isActive();
	}

	/**
	 * Save to cache
	 * @todo make this a generic minds function
	 * @param string $key
	 * @param array $data
	 * @return bool
	 */
	public static function saveToCache($key, $data){
		//@todo, make this work in other directories, not just tmp
		global $CONFIG;
		$path = "/tmp/minds/".$CONFIG->cassandra->keyspace;
		@mkdir($path, 0777, true);
		
		return file_put_contents("$path/$key", json_encode($data));
	}
	
	/**
	 * Get from cache
	 * @todo maek this a generic minds function
	 * @param string $key
	 * @return array
	 */
	public static function getFromCache($key){
		global $CONFIG;
		$path = "/tmp/minds/".$CONFIG->cassandra->keyspace;
		$data = file_get_contents("$path/$key");
		if($data)
			return json_decode($data, TRUE);
			
		return false;
	}
	
	/**
	 * Purge cache
	 */
	public static function purgeCache($key){
		global $CONFIG;
		$path = "/tmp/minds/".$CONFIG->cassandra->keyspace;
		return unlink("$path/$key");
	}
	
	/**
	 * Factory to load a plugin entity
	 * @param string/int $guid - the name of the plugin (ie. in the plugin directory)
	 * @param array $data - if passed, it will the load the plugin with settings information
	 * 
	 * @return object
	 */
	 static public function factory($guid, $data = NULL){
	 	if(!$data)
			$data = $guid;
		 
		$class = "\\minds\\plugin\\$guid\\start";
		 if(class_exists($class)){
		 	return new $class($data);
		 } else {
		 	//support legacy plugins
		 	return new \ElggPlugin($data);
		 }
	 }
	
}
