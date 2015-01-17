<?php
/**
 * Elgg plugins library
 * Contains functions for managing plugins
 *
 * @package Elgg.Core
 * @subpackage Plugins
 */

//Define the plugin cache
global $PLUGINS_CACHE;

/**
 * Tells ElggPlugin::start() to include the start.php file.
 */
define('ELGG_PLUGIN_INCLUDE_START', 1);

/**
 * Tells ElggPlugin::start() to automatically register the plugin's views.
 */
define('ELGG_PLUGIN_REGISTER_VIEWS', 2);

/**
 * Tells ElggPlugin::start() to automatically register the plugin's languages.
 */
define('ELGG_PLUGIN_REGISTER_LANGUAGES', 4);

/**
 * Tells ElggPlugin::start() to automatically register the plugin's classes.
 */
define('ELGG_PLUGIN_REGISTER_CLASSES', 8);


/**
 * Prefix for plugin user setting names
 */
define('ELGG_PLUGIN_USER_SETTING_PREFIX', 'plugin:user_setting:');


/**
 * @deprecated
 */
function elgg_get_plugin_ids_in_dir($dir = null) {
	return Minds\Core\plugins::getFromDir($dir);
}

/**
 * @deprecated
 */
function elgg_generate_plugin_entities() {
	//cassandra doesn't require 'generating' of plugins. We can just get from the directory and use the keys
}

/**
 * @deprecated
 */
function _elgg_cache_plugin_by_id(ElggPlugin $plugin) {
	return false; //we have a better caching mechanism
}

/**
 * @deprecated
 */
function elgg_get_plugin_from_id($plugin_id) {
	return Minds\Core\plugins::factory($plugin_id);
}

/**
 * @deprecated
 */
function elgg_plugin_exists($id) {
	//use isActive instead. 
}

/**
 * Returns the highest priority of the plugins
 *
 * @return int
 * @since 1.8.0
 * @access private
 */
function elgg_get_max_plugin_priority() {
	return Minds\Core\plugins\priorities::getMax();
}

/**
 * @deprecated
 */
function elgg_is_active_plugin($plugin_id, $site_guid = null) {
	return Minds\Core\plugins::isActive($plugin_id);
}

/**
 * @deprecated
 */
function elgg_load_plugins() {
	//private function now in Minds\Core\plugins.
}

/**
 * @deprecated
 */
function elgg_get_plugins($status = 'active', $site_guid = null) {
	return Minds\Core\plugins::get($status);
}

/**
 * @deprecated
 */
function elgg_set_plugin_priorities(array $order) {
	return false;
}

/**
 * @deprecated
 */
function elgg_reindex_plugin_priorities() {
	return false;
}

/**
 * Namespaces a string to be used as a private setting for a plugin.
 *
 * @param string $type The type of value: user_setting or internal.
 * @param string $name The name to namespace.
 * @param string $id   The plugin's ID to namespace with.  Required for user_setting.
 * @return string
 * @since 1.8.0
 * @access private
 */
function elgg_namespace_plugin_private_setting($type, $name, $id = null) {
	switch ($type) {
		// commented out because it breaks $plugin->$name access to variables
		//case 'setting':
		//	$name = ELGG_PLUGIN_SETTING_PREFIX . $name;
		//	break;

		case 'user_setting':
			if (!$id) {
				$id = elgg_get_calling_plugin_id();
			}
			$name = ELGG_PLUGIN_USER_SETTING_PREFIX . "$id:$name";
			break;

		case 'internal':
			$name = ELGG_PLUGIN_INTERNAL_PREFIX . $name;
			break;
	}

	return $name;
}

/**
 * Get the name of the most recent plugin to be called in the
 * call stack (or the plugin that owns the current page, if any).
 *
 * i.e., if the last plugin was in /mod/foobar/, this would return foo_bar.
 *
 * @param boolean $mainfilename If set to true, this will instead determine the
 *                              context from the main script filename called by
 *                              the browser. Default = false.
 *
 * @return string|false Plugin name, or false if no plugin name was called
 * @since 1.8.0
 * @access private
 *
 * @todo get rid of this
 */
function elgg_get_calling_plugin_id($mainfilename = false) {
	if (!$mainfilename) {
		if ($backtrace = debug_backtrace()) {
			foreach ($backtrace as $step) {
				$file = $step['file'];
				$file = str_replace("\\", "/", $file);
				$file = str_replace("//", "/", $file);
				if (preg_match("/mod\/([a-zA-Z0-9\-\_]*)\/start\.php$/", $file, $matches)) {
					return $matches[1];
				}
			}
		}
	} else {
		//@todo this is a hack -- plugins do not have to match their page handler names!
		if ($handler = get_input('handler', FALSE)) {
			return $handler;
		} else {
			$file = $_SERVER["SCRIPT_NAME"];
			$file = str_replace("\\", "/", $file);
			$file = str_replace("//", "/", $file);
			if (preg_match("/mod\/([a-zA-Z0-9\-\_]*)\//", $file, $matches)) {
				return $matches[1];
			}
		}
	}
	return false;
}

/**
 * Returns an array of all provides from all active plugins.
 *
 * Array in the form array(
 * 	'provide_type' => array(
 * 		'provided_name' => array(
 * 			'version' => '1.8',
 * 			'provided_by' => 'provider_plugin_id'
 *  	)
 *  )
 * )
 *
 * @param string $type The type of provides to return
 * @param string $name A specific provided name to return. Requires $provide_type.
 *
 * @return array
 * @since 1.8.0
 * @access private
 */
function elgg_get_plugins_provides($type = null, $name = null) {
	static $provides = null;
	$active_plugins = elgg_get_plugins('active');

	if (!isset($provides)) {
		$provides = array();

		foreach ($active_plugins as $plugin) {
			$plugin_provides = array();
			$manifest = $plugin->getManifest();
			if ($manifest instanceof ElggPluginManifest) {
				$plugin_provides = $plugin->getManifest()->getProvides();
			}
			if ($plugin_provides) {
				foreach ($plugin_provides as $provided) {
					$provides[$provided['type']][$provided['name']] = array(
						'version' => $provided['version'],
						'provided_by' => $plugin->getID()
					);
				}
			}
		}
	}

	if ($type && $name) {
		if (isset($provides[$type][$name])) {
			return $provides[$type][$name];
		} else {
			return false;
		}
	} elseif ($type) {
		if (isset($provides[$type])) {
			return $provides[$type];
		} else {
			return false;
		}
	}

	return $provides;
}

/**
 * Checks if a plugin is currently providing $type and $name, and optionally
 * checking a version.
 *
 * @param string $type       The type of the provide
 * @param string $name       The name of the provide
 * @param string $version    A version to check against
 * @param string $comparison The comparison operator to use in version_compare()
 *
 * @return array An array in the form array(
 * 	'status' => bool Does the provide exist?,
 * 	'value' => string The version provided
 * )
 * @since 1.8.0
 * @access private
 */
function elgg_check_plugins_provides($type, $name, $version = null, $comparison = 'ge') {
	$provided = elgg_get_plugins_provides($type, $name);
	if (!$provided) {
		return array(
			'status' => false,
			'version' => ''
		);
	}

	if ($version) {
		$status = version_compare($provided['version'], $version, $comparison);
	} else {
		$status = true;
	}

	return array(
		'status' => $status,
		'value' => $provided['version']
	);
}

/**
 * Returns an array of parsed strings for a dependency in the
 * format: array(
 * 	'type'			=>	requires, conflicts, or provides.
 * 	'name'			=>	The name of the requirement / conflict
 * 	'value'			=>	A string representing the expected value: <1, >=3, !=enabled
 * 	'local_value'	=>	The current value, ("Not installed")
 * 	'comment'		=>	Free form text to help resovle the problem ("Enable / Search for plugin <link>")
 * )
 *
 * @param array $dep An ElggPluginPackage dependency array
 * @return array
 * @since 1.8.0
 * @access private
 */
function elgg_get_plugin_dependency_strings($dep) {
	$dep_system = elgg_extract('type', $dep);
	$info = elgg_extract('dep', $dep);
	$type = elgg_extract('type', $info);

	if (!$dep_system || !$info || !$type) {
		return false;
	}

	// rewrite some of these to be more readable
	switch($info['comparison']) {
		case 'lt':
			$comparison = '<';
			break;
		case 'gt':
			$comparison = '>';
			break;
		case 'ge':
			$comparison = '>=';
			break;
		case 'le':
			$comparison = '<=';
			break;
		default;
			$comparison = $info['comparison'];
			break;
	}

	/*
	'requires'	'plugin oauth_lib'	<1.3	1.3		'downgrade'
	'requires'	'php setting bob'	>3		3		'change it'
	'conflicts'	'php setting'		>3		4		'change it'
	'conflicted''plugin profile'	any		1.8		'disable profile'
	'provides'	'plugin oauth_lib'	1.3		--		--
	'priority'	'before blog'		--		after	'move it'
	*/
	$strings = array();
	$strings['type'] = elgg_echo('ElggPlugin:Dependencies:' . ucwords($dep_system));

	switch ($type) {
		case 'elgg_version':
		case 'elgg_release':
			// 'Elgg Version'
			$strings['name'] = elgg_echo('ElggPlugin:Dependencies:Elgg');
			$strings['expected_value'] = "$comparison {$info['version']}";
			$strings['local_value'] = $dep['value'];
			$strings['comment'] = '';
			break;

		case 'php_extension':
			// PHP Extension %s [version]
			$strings['name'] = elgg_echo('ElggPlugin:Dependencies:PhpExtension', array($info['name']));
			if ($info['version']) {
				$strings['expected_value'] = "$comparison {$info['version']}";
				$strings['local_value'] = $dep['value'];
			} else {
				$strings['expected_value'] = '';
				$strings['local_value'] = '';
			}
			$strings['comment'] = '';
			break;

		case 'php_ini':
			$strings['name'] = elgg_echo('ElggPlugin:Dependencies:PhpIni', array($info['name']));
			$strings['expected_value'] = "$comparison {$info['value']}";
			$strings['local_value'] = $dep['value'];
			$strings['comment'] = '';
			break;

		case 'plugin':
			$strings['name'] = elgg_echo('ElggPlugin:Dependencies:Plugin', array($info['name']));
			$expected = $info['version'] ? "$comparison {$info['version']}" : elgg_echo('any');
			$strings['expected_value'] = $expected;
			$strings['local_value'] = $dep['value'] ? $dep['value'] : '--';
			$strings['comment'] = '';
			break;

		case 'priority':
			$expected_priority = ucwords($info['priority']);
			$real_priority = ucwords($dep['value']);
			$strings['name'] = elgg_echo('ElggPlugin:Dependencies:Priority');
			$strings['expected_value'] = elgg_echo("ElggPlugin:Dependencies:Priority:$expected_priority", array($info['plugin']));
			$strings['local_value'] = elgg_echo("ElggPlugin:Dependencies:Priority:$real_priority", array($info['plugin']));
			$strings['comment'] = '';
			break;
	}

	if ($dep['type'] == 'suggests') {
		if ($dep['status']) {
			$strings['comment'] = elgg_echo('ok');
		} else {
			$strings['comment'] = elgg_echo('ElggPlugin:Dependencies:Suggests:Unsatisfied');
		}
	} else {
		if ($dep['status']) {
			$strings['comment'] = elgg_echo('ok');
		} else {
			$strings['comment'] = elgg_echo('error');
		}
	}

	return $strings;
}

/**
 * Returns the ElggPlugin entity of the last plugin called.
 *
 * @return mixed ElggPlugin or false
 * @since 1.8.0
 * @access private
 */
function elgg_get_calling_plugin_entity() {
	$plugin_id = elgg_get_calling_plugin_id();

	if ($plugin_id) {
		return Minds\Core\plugins::factory($plugin_id);
	}

	return false;
}

/**
 * Returns an array of all plugin settings for a user.
 *
 * @param mixed  $user_guid  The user GUID or null for the currently logged in user.
 * @param string $plugin_id  The plugin ID
 * @param bool   $return_obj Return settings as an object? This can be used to in reusable
 *                           views where the settings are passed as $vars['entity'].
 * @return array
 * @since 1.8.0
 */
function elgg_get_all_plugin_user_settings($user_guid = null, $plugin_id = null, $return_obj = false) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin instanceof ElggPlugin) {
		return false;
	}

	$settings = $plugin->getAllUserSettings($user_guid);

	if ($settings && $return_obj) {
		$return = new stdClass;

		foreach ($settings as $k => $v) {
			$return->$k = $v;
		}

		return $return;
	} else {
		return $settings;
	}
}

/**
 * Set a user specific setting for a plugin.
 *
 * @param string $name      The name - note, can't be "title".
 * @param mixed  $value     The value.
 * @param int    $user_guid Optional user.
 * @param string $plugin_id Optional plugin name, if not specified then it
 *                          is detected from where you are calling from.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_set_plugin_user_setting($name, $value, $user_guid = null, $plugin_id = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}

	return $plugin->setUserSetting($name, $value, $user_guid);
}

/**
 * Unsets a user-specific plugin setting
 *
 * @param string $name      Name of the setting
 * @param int $user_guid Defaults to logged in user
 * @param string $plugin_id Defaults to contextual plugin name
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_unset_plugin_user_setting($name, $user_guid = null, $plugin_id = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		$plugin = elgg_get_calling_plugin_entity();
	}
	
	if (!$plugin) {
		return false;
	}

	return $plugin->unsetUserSetting($name, $user_guid);
}

/**
 * Get a user specific setting for a plugin.
 *
 * @param string $name      The name of the setting.
 * @param int    $user_guid Guid of owning user
 * @param string $plugin_id Optional plugin name, if not specified
 *                          it is detected from where you are calling.
 *
 * @return mixed
 * @since 1.8.0
 */
function elgg_get_plugin_user_setting($name, $user_guid = null, $plugin_id = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}

	return $plugin->getUserSetting($name, $user_guid);
}

/**
 * Set a setting for a plugin.
 *
 * @param string $name      The name of the setting - note, can't be "title".
 * @param mixed  $value     The value.
 * @param string $plugin_id Optional plugin name, if not specified
 *                          then it is detected from where you are calling from.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_set_plugin_setting($name, $value, $plugin_id = null) {
	if ($plugin_id) {
		$plugin = Minds\Core\plugins::factory($plugin_id);
	} else {
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}

	return $plugin->setSetting($name, $value);
}

/**
 * Get setting for a plugin.
 *
 * @param string $name      The name of the setting.
 * @param string $plugin_id Optional plugin name, if not specified
 *                          then it is detected from where you are calling from.
 *
 * @return mixed
 * @since 1.8.0
 * @todo make $plugin_id required in future version
 */
function elgg_get_plugin_setting($name, $plugin_id = null) {
	
	if ($plugin_id) {
		if(!Minds\Core\plugins::isActive($plugin_id)){
			return false;
		}
		$plugin = Minds\Core\plugins::factory($plugin_id);
	} else {
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}
	
	if($plugin instanceof ElggPlugin && $plugin->isActive()){
		return $plugin->getSetting($name);
	}
}

/**
 * Unsets a plugin setting.
 *
 * @param string $name      The name of the setting.
 * @param string $plugin_id Optional plugin name, if not specified
 *                          then it is detected from where you are calling from.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_unset_plugin_setting($name, $plugin_id = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}

	return $plugin->unsetSetting($name);
}

/**
 * Unsets all plugin settings for a plugin.
 *
 * @param string $plugin_id Optional plugin name, if not specified
 *                          then it is detected from where you are calling from.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_unset_all_plugin_settings($plugin_id = null) {
	if ($plugin_id) {
		$plugin = elgg_get_plugin_from_id($plugin_id);
	} else {
		$plugin = elgg_get_calling_plugin_entity();
	}

	if (!$plugin) {
		return false;
	}

	return $plugin->unsetAllSettings();
}

/**
 * @deprecated
 */
function elgg_get_entities_from_plugin_user_settings(array $options = array()) {
	return false;
}



function elgg_plugins_loaded_event_hook($event, $object_type, $params){
	// This validates the view type - first opportunity to do it is after plugins load.
	$view_type = elgg_get_viewtype();
	if (!elgg_is_valid_view_type($view_type)) {
		elgg_set_viewtype('default');
	}
}

/**
 * Initialize the plugin system
 * Listens to system init and registers actions
 * 
 * @todo move these into the oop section
 *
 * @return void
 * @access private
 */
function plugin_init() {
	
	elgg_register_event_handler('plugins_loaded', 'plugin', 'elgg_plugins_loaded_event_hook');

	elgg_register_action("plugins/settings/save", '', 'admin');
	elgg_register_action("plugins/usersettings/save");

	elgg_register_action('admin/plugins/activate', '', 'admin');
	elgg_register_action('admin/plugins/deactivate', '', 'admin');
	elgg_register_action('admin/plugins/activate_all', '', 'admin');
	elgg_register_action('admin/plugins/deactivate_all', '', 'admin');

	elgg_register_action('admin/plugins/set_priority', '', 'admin');

	elgg_register_library('elgg:markdown', elgg_get_root_path() . 'vendors/markdown/markdown.php');
}

elgg_register_event_handler('init', 'system', 'plugin_init');
