<?php
/**
 * Stores site-side plugin settings as private data.
 *
 * This class is currently a stub, allowing a plugin to
 * save settings in an object's private settings for each site.
 *
 * @package    Elgg.Core
 * @subpackage Plugins.Settings
 */
class ElggPlugin extends ElggEntity {
	private $package;
	private $manifest;

	private $path;
	private $pluginID;
	private $errorMsg = '';

	/**
	 * Set subtype to 'plugin'
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		//parent::initializeAttributes();

		$this->attributes['type'] = "plugin";
		$this->attributes['title'] = "";
		$this->attributes['active'] = 0;
		$this->attributes['priority'] = 0;
		
		// plugins must be public.
		$this->attributes['access_id'] = ACCESS_PUBLIC;
	}

	/**
	 * Loads the plugin by GUID or path.
	 *
	 * @warning Unlike other ElggEntity objects, you cannot null instantiate
	 *          ElggPlugin. You must point it to an actual plugin GUID or location.
	 *
	 * @param mixed $plugin The GUID of the ElggPlugin object or the path of the plugin to load.
	 *
	 * @throws PluginException
	 */
	public function __construct($plugin) {
		
		global $CONFIG, $PLUGINS_CACHE;
	
		if (!$plugin) {
			throw new PluginException(elgg_echo('PluginException:NullInstantiated'));
		}
	
		$this->initializeAttributes();		

		if (is_object($plugin) || is_array($plugin)) {
			//parent::__construct($plugin);
			foreach($plugin as $k => $v){
				$this->attributes[$k] = $v;
			}
	   
			$this->pluginID = $this->attributes['guid'];	
			$this->title = $this->pluginID;
			$this->path = elgg_get_plugins_path() . $this->getID();
			$PLUGINS_CACHE[$this->guid] = $this->attributes;
		} else {
			$plugin_path = elgg_get_plugins_path();

			// not a full path, so assume an id
			// use the default path
			if (strpos($plugin, $plugin_path) !== 0) {
				$this->path =  sanitise_filepath($plugin_path . $plugin);
			}
			
			$plugin_id = $plugin;
			$this->pluginID = $plugin;
			$this->guid = $plugin;

			if(isset($PLUGINS_CACHE[$plugin])){
				foreach($PLUGINS_CACHE[$plugin] as $k => $v){
					$this->$k = $v;
				}
				return true;
			}
		if(get_input('debug')){

//			var_dump($PLUGINS_CACHE[$plugin], $plugin);
			//exit;
		}	
			// check if we're loading an existing plugin
			$db = new Minds\Core\Data\Call('plugin');
			$existing_plugin = $db->getRow($this->guid);
			if($existing_plugin){	
				foreach($existing_plugin as $k => $v){
					$this->attributes[$k] = $v;
				}
			}
		}
		if(!$plugin || !is_string($plugin))
			$plugin = $this->guid;
		
		$PLUGINS_CACHE[$plugin] = $this;

		//_elgg_cache_plugin_by_id($this);
	}

	/**
	 * Run on init
	 */
	public function init(){
		//do nothing.. legacy
	}
	
	/**
	 * Save the plugin object.  Make sure required values exist.
	 *
	 * @see ElggObject::save()
	 * @return bool
	 */
	public function save() {
	
		global $PLUGINS_CACHE;
		$PLUGINS_CACHE = null;
	//var_dump('tying to save yo');	
		Minds\Core\plugins::purgeCache('plugins:all');
		Minds\Core\plugins::purgeCache('plugins:active');
		Minds\Core\plugins::$cache == array();
	
		$attributes = array();
		foreach($this as $k=>$v){
			if(is_null($v))
				continue;

			$attributes[$k] = $v;
		}

		$db = new Minds\Core\Data\Call('plugin');
		return $db->insert($this->guid, $attributes);

	}


	// Plugin ID and path

	/**
	 * Returns the ID (dir name) of this plugin
	 *
	 * @return string
	 */
	public function getID() {
		return $this->title;
	}

	/**
	 * Returns the manifest's name if available, otherwise the ID.
	 * 
	 * @return string
	 * @since 1.8.1
	 */
	public function getFriendlyName() {
		$manifest = $this->getManifest();
		if ($manifest) {
			return $manifest->getName();
		}

		return $this->getID();
	}

	/**
	 * Returns the plugin's full path with trailing slash.
	 *
	 * @return string
	 */
	public function getPath() {
		return sanitise_filepath($this->path);
	}

	/**
	 * Sets the location of this plugin.
	 *
	 * @param string $id The path to the plugin's dir.
	 * @return bool
	 */
	public function setID($id) {
		return $this->attributes['title'] = $id;
	}

	/**
	 * Returns an array of available markdown files for this plugin
	 * 
	 * @return array
	 */
	public function getAvailableTextFiles() {
		$filenames = $this->getPackage()->getTextFilenames();

		$files = array();
		foreach ($filenames as $filename) {
			if ($this->canReadFile($filename)) {
				$files[$filename] = "$this->path/$filename";
			}
		}

		return $files;
	}

	// Load Priority

	/**
	 * Gets the plugin's load priority.
	 *
	 * @return int
	 */
	public function getPriority() {
		$name = elgg_namespace_plugin_private_setting('internal', 'priority');
		return (int) $this->$name;
	}

	/**
	 * Sets the priority of the plugin
	 *
	 * @param mixed $priority  The priority to set. One of +1, -1, first, last, or a number.
	 *                         If given a number, this will displace all plugins at that number
	 *                         and set their priorities +1
	 * @param mixed $site_guid Optional site GUID.
	 * @return bool
	 */
	public function setPriority($priority, $site_guid = null) {
		return Minds\Core\plugins\priorities::set($this, $priority);
	}


	// Plugin settings

	/**
	 * Returns a plugin setting
	 *
	 * @param string $name The setting name
	 * @return mixed
	 */
	public function getSetting($name) {
		//Are we storing settings in Conf?
		global $CONFIG;

		if(isset($this->$name))
			return $this->$name;

		if(isset($CONFIG->pluginSettings) && $setting = $CONFIG->pluginSettings->{$this->guid}[$name]){
			return $setting;

		}

		if(isset($CONFIG->multisite) && isset($CONFIG->multisite->plugin_default_settings[$this->guid]) && isset($CONFIG->multisite->plugin_default_settings[$this->guid][$name]))
	return $CONFIG->multisite->plugin_default_settings[$this->guid][$name];
	}

	/**
	 * Returns an array of all settings saved for this plugin.
	 *
	 * @note Unlike user settings, plugin settings are not namespaced.
	 *
	 * @return array An array of key/value pairs.
	 */
	public function getAllSettings() {
		if (!$this->guid) {
			return false;
		}

		$db_prefix = elgg_get_config('dbprefix');
		// need to remove all namespaced private settings.
		$us_prefix = elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());
		$is_prefix = elgg_namespace_plugin_private_setting('internal', '', $this->getID());

		// Get private settings for user
		$q = "SELECT * FROM {$db_prefix}private_settings
			WHERE entity_guid = $this->guid
			AND name NOT LIKE '$us_prefix%'
			AND name NOT LIKE '$is_prefix%'";

		$private_settings = get_data($q);

		if ($private_settings) {
			$return = array();

			foreach ($private_settings as $setting) {
				$return[$setting->name] = $setting->value;
			}

			return $return;
		}

		return false;
	}

	/**
	 * Set a plugin setting for the plugin
	 *
	 * @todo This will only work once the plugin has a GUID.
	 *
	 * @param string $name  The name to set
	 * @param string $value The value to set
	 *
	 * @return bool
	 */
	public function setSetting($name, $value) {
		if (!$this->guid) {
			return false;
		}
		
		$this->$name = $value;
		return $this->save();
	}

	/**
	 * Removes a plugin setting name and value.
	 *
	 * @param string $name The setting name to remove
	 *
	 * @return bool
	 */
	public function unsetSetting($name) {
		$this->$name = '';
		$this->save();
	}

	/**
	 * Removes all settings for this plugin.
	 *
	 * @todo Should be a better way to do this without dropping to raw SQL.
	 * @todo If we could namespace the plugin settings this would be cleaner.
	 * @return bool
	 */
	public function unsetAllSettings() {
		$db_prefix = get_config('dbprefix');
		$ps_prefix = elgg_namespace_plugin_private_setting('setting', '');

		$q = "DELETE FROM {$db_prefix}private_settings
			WHERE entity_guid = $this->guid
			AND name NOT LIKE '$ps_prefix%'";

		return delete_data($q);
	}


	// User settings

	/**
	 * Returns a user's setting for this plugin
	 *
	 * @param string $name      The setting name
	 * @param int    $user_guid The user GUID
	 *
	 * @return mixed The setting string value or false
	 */
	public function getUserSetting($name, $user_guid = null) {

		if (!$user_guid) {
			$user_guid = elgg_get_logged_in_user_guid();
		}

		$name = elgg_namespace_plugin_private_setting('user_setting', $name, $this->getID());
        $user = new \Minds\entities\user($user_guid);
        return $user->$name;
        //return get_private_setting($user_guid,'user', $name);
	}

	/**
	 * Returns an array of all user settings saved for this plugin for the user.
	 *
	 * @note Plugin settings are saved with a prefix. This removes that prefix.
	 *
	 * @param int $user_guid The user GUID. Defaults to logged in.
	 * @return array An array of key/value pairs.
	 */
	public function getAllUserSettings($user_guid = null) {
		$user_guid = (int)$user_guid;

		if ($user_guid) {
			$user = get_entity($user_guid);
		} else {
			$user = elgg_get_logged_in_user_entity();
		}

		if (!($user instanceof ElggUser)) {
			return false;
		}

		$db_prefix = elgg_get_config('dbprefix');
		// send an empty name so we just get the first part of the namespace
		$ps_prefix = elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());
		$ps_prefix_len = strlen($ps_prefix);

		// Get private settings for user
		$q = "SELECT * FROM {$db_prefix}private_settings
			WHERE entity_guid = {$user->guid}
			AND name LIKE '$ps_prefix%'";

		$private_settings = get_data($q);

		if ($private_settings) {
			$return = array();

			foreach ($private_settings as $setting) {
				$name = substr($setting->name, $ps_prefix_len);
				$value = $setting->value;

				$return[$name] = $value;
			}

			return $return;
		}

		return false;
	}

	/**
	 * Sets a user setting for a plugin
	 *
	 * @param string $name      The setting name
	 * @param string $value     The setting value
	 * @param int    $user_guid The user GUID
	 *
	 * @return mixed The new setting ID or false
	 */
	public function setUserSetting($name, $value, $user_guid = null) {

		if ($user_guid) {
			$user = get_entity($user_guid, 'user');
		} else {
			$user = elgg_get_logged_in_user_entity();
		}
		
		if (!($user instanceof ElggUser)) {
			return false;
		}

		// Hook to validate setting
		// note: this doesn't pass the namespaced name
		$value = elgg_trigger_plugin_hook('usersetting', 'plugin', array(
			'user' => $user,
			'plugin' => $this,
			'plugin_id' => $this->getID(),
			'name' => $name,
			'value' => $value
		), $value);

		// set the namespaced name.
		$name = elgg_namespace_plugin_private_setting('user_setting', $name, $this->getID());

		//update session... @todo, make this work better. Probably put through User class
        if(isset( $_SESSION['user'] ))
            $_SESSION['user']->$name = $value;
        
		return set_private_setting($user->guid, 'entities',$name, $value);
	}


	/**
	 * Removes a user setting name and value.
	 *
	 * @param string $name      The user setting name
	 * @param int    $user_guid The user GUID
	 * @return bool
	 */
	public function unsetUserSetting($name, $user_guid = null) {

		if ($user_guid) {
			$user = get_entity($user_guid,'user');
		} else {
			$user = elgg_get_logged_in_user_entity();
		}

		if (!($user instanceof ElggUser)) {
			return false;
		}

		// set the namespaced name.
		$name = elgg_namespace_plugin_private_setting('user_setting', $name, $this->getID());

		unset($user->$name);
		$_SESSION['user'] = $user;	
	
		return remove_private_setting($user->guid, 'user', $name);
	}

	/**
	 * Removes all User Settings for this plugin
	 *
	 * Use {@link removeAllUsersSettings()} to remove all user
	 * settings for all users.  (Note the plural 'Users'.)
	 *
	 * @param int $user_guid The user GUID to remove user settings.
	 * @return bool
	 */
	public function unsetAllUserSettings($user_guid) {
		$db_prefix = get_config('dbprefix');
		$ps_prefix = elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());

		$q = "DELETE FROM {$db_prefix}private_settings
			WHERE entity_guid = $user_guid
			AND name LIKE '$ps_prefix%'";

		return delete_data($q);
	}

	/**
	 * Removes this plugin's user settings for all users.
	 *
	 * Use {@link removeAllUserSettings()} if you just want to remove
	 * settings for a single user.
	 *
	 * @return bool
	 */
	public function unsetAllUsersSettings() {
		$db_prefix = get_config('dbprefix');
		$ps_prefix = elgg_namespace_plugin_private_setting('user_setting', '', $this->getID());

		$q = "DELETE FROM {$db_prefix}private_settings
			WHERE name LIKE '$ps_prefix%'";

		return delete_data($q);
	}


	// validation

	/**
	 * Returns if the plugin is complete, meaning has all required files
	 * and Elgg can read them and they make sense.
	 *
	 * @todo bad name? This could be confused with isValid() from ElggPackage.
	 *
	 * @return bool
	 */
	public function isValid() {
		
		if (!$this->getPackage() instanceof ElggPluginPackage) {echo 'no package';
			$this->errorMsg = elgg_echo('ElggPlugin:NoPluginPackagePackage', array($this->getID(), $this->guid));
			return false;
		}
		
		if (!$this->getPackage()->isValid()) {echo 'package not valid';
			$this->errorMsg = $this->getPackage()->getError();
			return false;
		}
		
		return true;
	}

	/**
	 * Is this plugin active?
	 *
	 * @param int $site_guid Optional site guid.
	 * @return bool
	 */
	public function isActive($site_guid = null) {
		if (!$this->guid) {
			return false;
		}

		$active = $this->active == 1 ? true : false;

		return $active;
	}

	/**
	 * Checks if this plugin can be activated on the current
	 * Elgg installation.
	 *
	 * @todo remove $site_guid param or implement it
	 *
	 * @param mixed $site_guid Optional site guid
	 * @return bool
	 */
	public function canActivate($site_guid = null) {
		if ($this->getPackage()) {
			$result = $this->getPackage()->isValid() && $this->getPackage()->checkDependencies();
			if (!$result) {
				$this->errorMsg = $this->getPackage()->getError();
			}
			return $result;
		}

		return false;
	}


	// activating and deactivating

	/**
	 * Actives the plugin for the current site.
	 *
	 * @param mixed $site_guid Optional site GUID.
	 * @return bool
	 */
	public function activate($site_guid = null) {
		if ($this->isActive($site_guid)) {
			return true;
		}
		
		if (!$this->canActivate()) {
			return false;
		}
		
		// set in the db, now perform tasks and emit events
		if ($this->setSetting('active', 1)) {
			// emit an event. returning false will make this not be activated.
			// we need to do this after it's been fully activated
			// or the deactivate will be confused.
			$params = array(
				'plugin_id' => $this->pluginID,
				'plugin_entity' => $this
			);
			$return = elgg_trigger_event('activate', 'plugin', $params);

			// if there are any on_enable functions, start the plugin now and run them
			// Note: this will not run re-run the init hooks!
			if ($return) {
				if ($this->canReadFile('activate.php')) {
					$flags = ELGG_PLUGIN_INCLUDE_START | ELGG_PLUGIN_REGISTER_CLASSES
							| ELGG_PLUGIN_REGISTER_LANGUAGES | ELGG_PLUGIN_REGISTER_VIEWS;

					$this->start($flags);

					$return = $this->includeFile('activate.php');
				}
			}
			if ($return === false) { 
				$this->deactivate($site_guid);
			}
			return $return;
		}

		return false;
	}

	/**
	 * Deactivates the plugin.
	 *
	 * @param mixed $site_guid Optional site GUID.
	 * @return bool
	 */
	public function deactivate($site_guid = null) {
		if (!$this->isActive($site_guid)) {
			//return false;
		}

		// emit an event. returning false will cause this to not be deactivated.
		$params = array(
			'plugin_id' => $this->pluginID,
			'plugin_entity' => $this
		);

		$return = elgg_trigger_event('deactivate', 'plugin', $params);

		// run any deactivate code
		if ($return) {
			if ($this->canReadFile('deactivate.php')) {
				$return = $this->includeFile('deactivate.php');
			}
		}

		if ($return === false) {
			return false;
		} else {
			return 	$this->setSetting('active', "0");
		}
	}
	
	/**
	 * Is the plugin enabled, this is different to active and should be set to true here
	 */
	public function isEnabled(){
		return true;
	}


	/**
	 * Start the plugin.
	 *
	 * @param int $flags Start flags for the plugin. See the constants in lib/plugins.php for details.
	 * @return true
	 * @throws PluginException
	 */
	public function start($flags =NULL) {
		if (!$this->canActivate()) {
			return false;
		}

		// include classes, plugins should really use namespace standards now
		$this->registerClasses();

		$this->includeFile('start.php');

		$this->registerViews();
		
		$this->registerLanguages();
		
		return true;
	}


	// start helpers

	/**
	 * Includes one of the plugins files
	 *
	 * @param string $filename The name of the file
	 *
	 * @throws PluginException
	 * @return mixed The return value of the included file (or 1 if there is none)
	 */
	protected function includeFile($filename) {
		// This needs to be here to be backwards compatible for 1.0-1.7.
		// They expect the global config object to be available in start.php.
		if ($filename == 'start.php') {
			global $CONFIG;
		}

		$filepath = "$this->path/$filename";

		if (!$this->canReadFile($filename)) {
			$msg = elgg_echo('ElggPlugin:Exception:CannotIncludeFile',
							array($filename, $this->getID(), $this->guid, $this->path));
			throw new PluginException($msg);
		}

		return include_once($filepath);
	}

	/**
	 * Checks whether a plugin file with the given name exists
	 *
	 * @param string $filename The name of the file
	 * @return bool
	 */
	protected function canReadFile($filename) {
		return is_readable($this->path . '/' . $filename);
	}

	/**
	 * Registers the plugin's views
	 *
	 * @throws PluginException
	 * @return true
	 */
	protected function registerViews() {
		$view_dir = "$this->path/views/";

		// plugins don't have to have views.
		if (!is_dir($view_dir)) {
			return true;
		}

		// but if they do, they have to be readable
		$handle = opendir($view_dir);
		if (!$handle) {
			$msg = elgg_echo('ElggPlugin:Exception:CannotRegisterViews',
							array($this->getID(), $this->guid, $view_dir));
			throw new PluginException($msg);
		}

		while (FALSE !== ($view_type = readdir($handle))) {
			$view_type_dir = $view_dir . $view_type;

			if ('.' !== substr($view_type, 0, 1) && is_dir($view_type_dir)) {
				if (autoregister_views('', $view_type_dir, $view_dir, $view_type)) {
					elgg_register_viewtype($view_type);
				} else {
					$msg = elgg_echo('ElggPlugin:Exception:CannotRegisterViews',
									array($this->getID(), $view_type_dir));
					throw new PluginException($msg);
				}
			}
		}

		return true;
	}

	/**
	 * Registers the plugin's languages
	 *
	 * @throws PluginException
	 * @return true
	 */
	protected function registerLanguages() {
		$languages_path = "$this->path/languages";

		// don't need to have classes
		if (!is_dir($languages_path)) {
			return true;
		}

		// but need to have working ones.
		if (!register_translations($languages_path)) {
			$msg = elgg_echo('ElggPlugin:Exception:CannotRegisterLanguages',
							array($this->getID(), $this->guid, $languages_path));
			throw new PluginException($msg);
		}

		return true;
	}

	/**
	 * Registers the plugin's classes
	 *
	 * @throws PluginException
	 * @return true
	 */
	protected function registerClasses() {
		$classes_path = "$this->path/classes";

		// don't need to have classes
		if (!is_dir($classes_path)) {
			return true;
		}

		elgg_register_classes($classes_path);

		return true;
	}


	// generic helpers and overrides

	/**
	 * Get a value from private settings.
	 *
	 * @param string $name Name
	 *
	 * @return mixed
	 */
	public function get($name) {
		// rewrite for old and inaccurate plugin:setting
		if (strstr($name, 'plugin:setting:')) {
			$msg = 'Direct access of user settings is deprecated. Use ElggPlugin->getUserSetting()';
			elgg_deprecated_notice($msg, 1.8);
			$name = str_replace('plugin:setting:', '', $name);
			$name = elgg_namespace_plugin_private_setting('user_setting', $name);
		}

		// See if its in our base attribute
		if (array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		}
		return NULL;
	}

	/**
	 * Save a value as private setting or attribute.
	 *
	 * Attributes include title and description.
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 *
	 * @return bool
	 */
	public function set($name, $value) {
		$this->attributes[$name] = $value;
		return true;
	}

	/**
	 * Sets the plugin to active or inactive for $site_guid.
	 *
	 * @param bool  $active    Set to active or inactive
	 * @param mixed $site_guid Int for specific site, null for current site.
	 *
	 * @return bool
	 */
	private function setStatus($active, $site_guid = null) {
		if (!$this->guid) {
			return false;
		}

		if ($site_guid) {
			$site = get_entity($site_guid, 'site');

			if (!($site instanceof ElggSite)) {
				return false;
			}
		} else {
			$site = get_config('site');
		}
		
		return true;
	}

	/**
	 * Returns the last error message registered.
	 *
	 * @return string|null
	 */
	public function getError() {
		return $this->errorMsg;
	}

	/**
	 * Returns this plugin's ElggPluginManifest object
	 *
	 * @return ElggPluginManifest
	 */
	public function getManifest() {
		if ($this->manifest instanceof ElggPluginManifest) {
			return $this->manifest;
		}

		try {
			$this->manifest = $this->getPackage()->getManifest();
		} catch (Exception $e) {
			elgg_log("Failed to load manifest for plugin $this->guid. " . $e->getMessage(), 'WARNING');
			$this->errorMsg = $e->getmessage();
		}

		return $this->manifest;
	}

	/**
	 * Returns this plugin's ElggPluginPackage object
	 *
	 * @return ElggPluginPackage
	 */
	public function getPackage() {
		if ($this->package instanceof ElggPluginPackage) {
			return $this->package;
		}

		try {
			$this->package = new ElggPluginPackage($this->path, false);
		} catch (Exception $e) {
			elgg_log("Failed to load package for $this->guid. " . $e->getMessage(), 'WARNING');
			$this->errorMsg = $e->getmessage();
		}

		return $this->package;
	}
}
