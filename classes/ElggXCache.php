<?php
/**
 * XCache wrapper class.
 *
 * @package    Elgg.Core
 * @subpackage Memcache
 */
class ElggXCache extends ElggSharedMemoryCache {
	/**
	 * Expiry of saved items (default timeout after a day to prevent anything getting too stale)
	 */
	private $expires = 86400;
	private $enabled = false;

	/**
	 * Establish XCACHE VARIALE CACHE 
	 *
	 * @param string $namespace The namespace for this cache to write to -
	 * note, namespaces of the same name are shared!
	 *
	 * @throws ConfigurationException
	 */
	function __construct($namespace = 'default') {
		global $CONFIG;

		$this->setNamespace($namespace);

		// Do we have xcache?
		if (function_exists('xcache_get') || intval(ini_get('xcache.var_size')) > 0) {	
			$this->enabled = true;
		}	
	}

	/**
	 * Combine a key with the namespace.
	 *
	 * @param string $key The key
	 *
	 * @return string The new key.
	 */
	private function makeKey($key) {
		$prefix = $this->getNamespace() . ":";

		if (strlen($prefix . $key) > 250) {
			$key = md5($key);
		}

		return $prefix . $key;
	}

	/**
	 * Saves a name and value to the cache
	 *
	 * @param string  $key     Name
	 * @param string  $data    Value
	 * @param integer $expires Expires (in seconds)
	 *
	 * @return bool
	 */
	public function save($key, $data, $expires = null) {

		if(!$this->enabled){
			return false;
		}

		$key = $this->makeKey($key);	
	
		if ($expires === null) {
			$expires = $this->expires;
		}

		$result = xcache_set($key, serialize($data), $expires);

		if (!$result) {
			elgg_log("XCACHE: FAILED TO SAVE $key", 'ERROR');
		}

		return $result;
	}

	/**
	 * Retrieves data.
	 *
	 * @param string $key    Name of data to retrieve
	 * @param int    $offset Offset
	 * @param int    $limit  Limit
	 *
	 * @return mixed
	 */
	public function load($key, $offset = 0, $limit = null) {
		
		if(!$this->enabled){
			return false;
		}
		
		$key = $this->makeKey($key);

		$result = xcache_get($key);

		if (!$result) {
			elgg_log("XCACHE: FAILED TO LOAD $key", 'ERROR');
		}

		return unserialize($result);
	}

	/**
	 * Delete data
	 *
	 * @param string $key Name of data
	 *
	 * @return bool
	 */
	public function delete($key) {
		
		if(!$this->enabled){
			return false;
		}
		
		$key = $this->makeKey($key);

		return xcache_unset($key);
	}

	/**
	 * Clears the entire cache?
	 *
	 * @todo write or remove.
	 *
	 * @return true
	 */
	public function clear() {
		
		if(!$this->enabled){
			return false;
		}
		
		// DISABLE clearing for now - you must use delete on a specific key.
		return true;
	}
}
