<?php
/**
 * Elgg multisite library.
 *
 * @package ElggMultisite
 * @author Marcus Povey <marcus@marcus-povey.co.uk>
 * @copyright Marcus Povey 2010
 * @link http://www.marcus-povey.co.uk/
 */

use phpcassa\ColumnFamily;
use phpcassa\ColumnSlice;
use phpcassa\Connection\ConnectionPool;
use phpcassa\SystemManager;
use phpcassa\Schema\StrategyClass;
use phpcassa\Index\IndexClause;
use phpcassa\Index\IndexExpression;
use phpcassa\Schema\DataType\LongType;
use phpcassa\UUID;
require_once('elgglib.php');
global $MULTI_DB;
$MULTI_DB = new stdClass();
//make a database class for caching etc

/**
 * Multisite domain.
 */
class MultisiteDomain implements
Iterator,	// Override foreach behaviour
ArrayAccess// Override for array access
{
	private $__attributes = array();

	//private $domain = "";
	private $id;

	public function __construct($url = '') {
		if ($url) {
			if ($this -> load($url) === false)
				throw new Exception("Domain settings for $url could not be found");
		}
	}

	public function __get($name) {
		return $this -> __attributes[$name];
	}

	public function __set($name, $value) {
		return $this -> __attributes[$name] = $value;
	}

	public function __isset($name) {
		return isset($this -> __attributes[$name]);
	}

	public function __unset($name) { unset($this -> __attributes[$name]);
	}

	// ITERATOR INTERFACE //////////////////////////////////////////////////////////////
	private $__iterator_valid = FALSE;

	public function rewind() { $this -> __iterator_valid = (FALSE !== reset($this -> __attributes));
	}

	public function current() {
		return current($this -> __attributes);
	}

	public function key() {
		return key($this -> __attributes);
	}

	public function next() { $this -> __iterator_valid = (FALSE !== next($this -> __attributes));
	}

	public function valid() {
		return $this -> __iterator_valid;
	}

	// ARRAY ACCESS INTERFACE //////////////////////////////////////////////////////////
	public function offsetSet($key, $value) {
		if (array_key_exists($key, $this -> __attributes))
			$this -> __attributes[$key] = $value;
	}

	public function offsetGet($key) {
		if (array_key_exists($key, $this -> __attributes))
			return $this -> __attributes[$key];
	}

	public function offsetUnset($key) {
		if (array_key_exists($key, $this -> __attributes))
			$this -> __attributes[$key] = "";
	}

	public function offsetExists($offset) {
		return array_key_exists($offset, $this -> __attributes);
	}

	/**
	 * Can the site be accessed?
	 *
	 * Override in order to check quotas etc.
	 *
	 * @return unknown
	 */
	public function isSiteAccessible() {
		if ($this -> enabled == 'no')
			return false;

		return true;
	}

	public function setDomain($url) { $this -> domain = $url;
	}

	public function getDomain() {
		return $this -> domain;
	}

	public function getID() {
		return $this -> domain;
	}// ID now is the same as DOMAIN

	public function isDbInstalled() {
		/*$link = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass, true);
		 mysql_select_db($this->dbname, $link);

		 $result = mysql_query("SHOW tables like '{$this->dbprefix}%'", $link);
		 if (!$result) return false;

		 $result = mysql_fetch_object($result);
		 if ($result)
		 return true;

		 return false;*/
	}

	public function getDBVersion() {
		/*$link = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass, true);
		 mysql_select_db($this->dbname, $link);

		 $result = mysql_query("SELECT * FROM {$this->dbprefix}datalists WHERE name='version'");

		 if (!$result) return false;
		 $result = mysql_fetch_object($result);

		 if ($result)
		 (int)$result->value;

		 return false;*/
	}

	public function disable_plugin($plugin) {
		return $this -> toggle_plugin($plugin, false);
	}

	public function enable_plugin($plugin) {
		return $this -> toggle_plugin($plugin, true);
	}

	protected function toggle_plugin($plugin, $enable = true, $site_id = 1) {

		throw new Exception('TODO');

		//TODO: Handle nested sites
		/*
		 $link = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass, true);
		 mysql_select_db($this->dbname, $link);

		 $plugin = mysql_real_escape_string($plugin);
		 $site_id = (int)$site_id;

		 $result = mysql_query("SELECT * FROM {$this->dbprefix}metastrings WHERE string='enabled_plugins'",$link);
		 if (!$result) return false;
		 $string = mysql_fetch_object($result);
		 if (!$string) {
		 mysql_query("INSERT into {$this->dbprefix}metastrings (string) VALUES ('enabled_plugins')");
		 $enabled_id = mysql_insert_id($link);
		 }
		 else
		 {

		 $enabled_id = $string->id;
		 }

		 $result = mysql_query("SELECT * FROM {$this->dbprefix}metastrings WHERE string='$plugin'", $link);
		 if (!$result) return false;
		 $string = mysql_fetch_object($result);
		 if (!$string) {
		 mysql_query("INSERT into {$this->dbprefix}metastrings (string) VALUES ('$plugin')");
		 $plugin_id = mysql_insert_id($link);
		 }
		 else
		 {

		 $plugin_id = $string->id;
		 }

		 // Insert metadata
		 if ($enable)
		 {
		 /*	$query = "INSERT INTO {$this->dbprefix}metadata (entity_guid, name_id, value_id, value_type, owner_guid, access_id, time_created) VALUES($site_id, $enabled_id, $plugin_id, 'text', 2, 2, '".time()."')";

		 //	mysql_query($query);

		 // TODO : Enable
		 }
		 else
		 {
		 $query = "DELETE from {$this->dbprefix}metadata WHERE entity_guid=$site_id and name_id=$enabled_id and value_id=$plugin_id";

		 mysql_query($query);
		 }

		 return true;*/
	}

	/**
	 * Save object to database.
	 */
	public function save() {
		global $MULTI_DB;

		$class = get_class($this);
		$url = $this -> getDomain();
		//mysql_real_escape_string($this->getDomain());

		$dblink = elggmulti_db_connect();

		// Remove entry (is this strictly necessary?)
		try {
			$MULTI_DB -> cfs['domain'] -> remove($url);
		} catch (\Exception $e) {
		}

		try {
			$attrs = $this -> __attributes;
			$attrs['class'] = $class;

			foreach ($attrs as $k => $v) {
				$attrs[$k] = serialize($v);
			}

			$MULTI_DB -> cfs['domain'] -> insert($url, $attrs);

			return $this -> getID();
		} catch (\Exception $e) {
			return false;
		}

		/*if (!$this->id) {
		 $result = elggmulti_execute_query("INSERT into domains (domain, class) VALUES ('$url', '$class')");
		 $this->id = mysql_insert_id($dblink);
		 }
		 else
		 $result = elggmulti_execute_query("UPDATE domains set domain='$url', class='$class' WHERE id={$this->id}");

		 if (!$result)
		 return false;*/

		/*elggmulti_execute_query("DELETE from domains_metadata where domain_id='{$this->id}'");

		 foreach ($this->__attributes as $key => $value)
		 {
		 // Sanitise string
		 $key = mysql_real_escape_string($key);

		 // Convert non-array to array
		 if (!is_array($value))
		 $value = array($value);

		 // Save metadata
		 foreach ($value as $meta)
		 elggmulti_execute_query("INSERT into domains_metadata (domain_id,name,value) VALUES ({$this->id}, '$key', '".mysql_real_escape_string($meta)."')");

		 }*/
	}

	/**
	 * Load database settings.
	 *
	 * @param string $url URL to load
	 */
	public function load($url) {
		//$row = elggmulti_getdata_row("SELECT * from domains WHERE domain='$url' LIMIT 1");
		$row = elggmulti_getdata_row(array('domain' => $url));
		if (!$row)
			return false;

		foreach ($row as $k => $v)
			$this -> $k = unserialize($v);

		return true;
	}

	/**
	 * Return available multisite domains.
	 *
	 * @return array
	 */
	public static function getDomainTypes() {
		return array('MultisiteDomain' => 'Elgg domain', );
	}

}

/**
 * Helper function for constructing classes.
 */
function __elggmulti_db_row($row) {
	// Sanity check
	if ((!($row instanceof stdClass)) ||
		(!$row -> class))
			throw new Exception('Invalid handling class');

	$class = $row -> class;
	unset($row -> class);

	if (class_exists($class)) {
		$object = new $class();

		if (!($object instanceof MultisiteDomain))
			throw new Exception('Class is invalid');

		$object -> load($row -> domain);

		return $object;
	}

	return false;
}

/**
 * Connect multisite database.
 *
 * @return bool
 */
function elggmulti_db_connect() {
	global $CONFIG, $MULTI_DB;

	if (empty($MULTI_DB -> pool)) {
		require_once (dirname(dirname(dirname(__FILE__))) . '/vendors/phpcassa/lib/autoload.php');
		$servers = $CONFIG -> multisite -> servers;

		$pool = new ConnectionPool($CONFIG -> multisite -> keyspace, $servers, null, 1);

		$MULTI_DB -> pool = $pool;

		$cfs = array('mindsuser_to_domain', 'domain');

		$MULTI_DB -> cfs = array();
		foreach ($cfs as $n) {
			$MULTI_DB -> cfs[$n] = new ColumnFamily($MULTI_DB -> pool, $n);
		}


	}

	if ($MULTI_DB -> pool)
		return true;

	return false;
}

/**
 * Get data from a database.
 *
 * @param string $query
 * @param string $callback
 */
function elggmulti_getdata($options, $callback = '') {
	global $MULTI_DB;

	$defaults = array('type' => 'domain', 'limit' => 12, 'offset' => "", );

	$options = array_merge($defaults, $options);

	if ($options['limit'] == 0) {
		unset($options['limit']);
	}

	$type = $options['type'];
	if (!$type || !array_key_exists($type, $MULTI_DB -> cfs)) {
		return;
	}
	//if($type == 'plugin'){ echo '<pre>';var_dump(debug_backtrace());echo '</pre>';exit;}
	//echo "Called $type"; var_dump($options);
	try {
		// 1. We're retrieving a domain by its url
		if ($domain = $options['domain']) {
			if (is_array($domain)) {
				$rows = $MULTI_DB -> cfs[$type] -> multiget($domain);
			} else {
				if(class_exists('ElggXCache')){
                  			$cache = new ElggXCache('ms_domains');
              				if(!$rows[0] = $cache->load($domain)){
						 $rows[0] = $MULTI_DB -> cfs[$type] -> get($domain);
						 $cache->save($domain, $rows[0]);
					}	
				 } else {
					$rows[0] = $MULTI_DB -> cfs[$type] -> get($domain);
				}
			}
		}

	} catch (Exception $e) {
		return false;
	}

	if ($callback) {

		foreach ($rows as $k => $row) {

			$new_row = new StdClass;

			foreach ($row as $rk => $v) {
				$new_row -> $rk = unserialize($v);
			}

			$entities[] = $callback($new_row);
		}
		return $entities;
	} else
		return $rows;
}

/**
 * Get data row.
 *
 * @param string $query
 */
function elggmulti_getdata_row($query, $callback = '') {
	$result = elggmulti_getdata($query, $callback);
	if ($result)
		return $result[0];

	return false;
}

/**
 * Retrieve db settings.
 *
 * Retrieve a database setting based on the current multisite domain
 * detected.
 *
 * @param $url The url who's settings you need to retrieve, detected if not provided.
 * @return MultisiteDomain|false
 */
function elggmulti_get_db_settings($url = '') {
	global $CONFIG;

	$dblink = elggmulti_db_connect();

	// If no url then use the server referrer
	if (!$url)
		$url = $_SERVER['HTTP_HOST'];
	if (!$url)
		$url = $_SERVER['SERVER_NAME'];
	
	/*if(class_exists('ElggXCache')){
		$cache = new ElggXCache('ms_settings');
		if(!$result = $cache->load($url)){	
			$result = elggmulti_getdata_row(array('domain' => $url), '__elggmulti_db_row');
			if($result){
				$cache->save($url, $result);
			} else {
				$cache->remove($url);
			}
		} 
	}*/

		$result = elggmulti_getdata_row(array('domain' => $url), '__elggmulti_db_row');
	
	if ($result) {

		if (!$result -> isSiteAccessible())
			return false;

		return $result;
	}

	return false;
}

function elggmulti_get_db_by_id($id) {
	$id = (int)$id;
	throw new \Exception('TODO');
	//$result = elggmulti_getdata_row("SELECT * from domains WHERE id=$id LIMIT 1", '__elggmulti_db_row');

	if ($result)
		return $result;

	return false;
}

/**
 * Return whether a plugin is available for a given
 *
 * @param string $plugin
 */
function elggmulti_is_plugin_available($plugin) {
	static $activated;
return true;
	if (!$activated)
		$activated = elggmulti_get_activated_plugins();

	return in_array($plugin, $activated);
}

/**
 * Get plugins which have been activated for a given domain.
 *
 * @param int $domain_id
 * @return array|false
 */
function elggmulti_get_activated_plugins($domain_id = false) {
	if (!$domain_id) {
		$result = elggmulti_get_db_settings();

		$domain_id = $result -> getID();
	}
exit;	
	$site = new \MultisiteDomain($domain_id);
	
	$resultarray = $site -> enabled_plugins;
	if (($resultarray) && (!is_array($resultarray)))
		$resultarray = array($resultarray);
	
	$defaults = elggmulti_get_default_plugins();
	$resultarray = array_merge($defaults, $resultarray);

	return $resultarray;
}

function elggmulti_get_default_plugins(){
	return array( 'tinymce', 
			'channel',
            		'groups',
            		'wall',
			 'analytics',
			'archive',
			'blog',
			 'persona',
	            'notifications',
        	    'minds_connect',
			'mobile',
			'anypage',
            		'Login-As',
        	    	'minds_widgets',
			'minds_connect'
		);
}

/**
 * Return a list of all installed plugins.
 *
 */
function elggmulti_get_installed_plugins() {
	$plugins = array();

	$path = dirname(dirname(dirname(__FILE__))) . '/mod/';

	if ($handle = opendir($path)) {

		while ($mod = readdir($handle)) {

			if (!in_array($mod, array('.', '..', '.svn', 'CVS')) && is_dir($path . "/" . $mod)) {
				if ($mod != 'pluginmanager')// hide plugin manager
					$plugins[] = $mod;
			}

		}
	}

	sort($plugins);

	return $plugins;
}

/**
 * A list of plugins which should be hidden
 */
function elggmulti_required_plugins() {
	return array(	'pluginmanager' => array('activated'=>true), 
					'minds' => array('activated'=>true), 
					'mindsmulti_pluginmanager' => array('activated'=>true),
					'minds_themeconfig' => array('activated'=>true),
					'minds_theme_selector' => array('activated'=>true),
					
					//not compatible
					//'market' => array('activated'=>false),
					//'logbrowser' => array('activated'=>false),
					//'Login-As' => array('activated'=>false),
					//'image-captcha' => array('activated'=>false),
					//'voting' => array('activated'=>false),
					//'webinar' => array('activated'=>false),
					
				);		
}
/**
 * Activate or deactivate a plugin.
 *
 * @param unknown_type $domain_id
 * @param unknown_type $plugin
 * @param unknown_type $activate
 */
function elggmulti_toggle_plugin($domain_id, $plugin, $activate = true) {
	/*$plugin = mysql_real_escape_string($plugin);
	 $domain_id = (int)$domain_id;*/

	$domain = new \MultisiteDomain($domain_id);
	$enabled_plugins = $domain -> enabled_plugins;
	if ($enabled_plugins && !is_array($enabled_plugins))
		$enabled_plugins = array($enabled_plugins);
	else if (!$enabled_plugins)
		$enabled_plugins = array();

	if ($activate) {
		$enabled_plugins[] = $plugin;
		//elggmulti_execute_query("INSERT into domains_activated_plugins (domain_id, plugin) VALUES ($domain_id, '$plugin')");
	} else {
		//elggmulti_execute_query("DELETE FROM domains_activated_plugins where domain_id=$domain_id and plugin='$plugin'");

		$e = array();
		foreach ($enabled_plugins as $p) {
			if ($p != $plugin)
				$e[] = $p;
		}
		$enabled_plugins = $e;

		$domain -> disable_plugin($plugin);
	}

	$domain -> enabled_plugins = $enabled_plugins;
	$domain -> save();
}


/*	function elggmulti_get_messages()
 {
 if ((isset($_SESSION['_EM_MESSAGES'])) && (is_array($_SESSION['_EM_MESSAGES'])))
 {
 $messages = $_SESSION['_EM_MESSAGES'];
 $_SESSION['_EM_MESSAGES'] = null;

 return $messages;
 }

 return false;
 }

 function elggmulti_set_message($message)
 {
 if (!is_array($_SESSION['_EM_MESSAGES']))
 $_SESSION['_EM_MESSAGES'] = array();

 $_SESSION['_EM_MESSAGES'][] = $message;

 return true;
 }
 */
