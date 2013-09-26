<?php
/**
 * APC wrapper class.
 *
 * @package    Elgg.Core
 * @subpackage Memcache
 */
class ElggApcCache extends ElggSharedMemoryCache {

	/**
	 * Expiry of saved items (default timeout after a day to prevent anything getting too stale)
	 */
	private $expires = 86400;


	/**
	 * Connect to local APC cache.
	 *
	 * @param string $namespace The namespace for this cache to write to -
	 * note, namespaces of the same name are shared!
	 *
	 * @throws ConfigurationException
	 */
	function __construct($namespace = 'default') {
		global $CONFIG;

		$this->setNamespace($namespace);

		$is_apc_installed = extension_loaded('apc');
		// Do we have memcache?
		if (!$is_apc_installed) {
			throw new ConfigurationException('PHP apc module not installed, you must install APC');
		}

	}

	/**
	 * Set the default expiry.
	 *
	 * @param int $expires The lifetime as a unix timestamp or time from now. Defaults forever.
	 *
	 * @return void
	 */
	public function setDefaultExpiry($expires = 0) {
		$this->expires = $expires;
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
		$key = $this->makeKey($key);

		if ($expires === null) {
			$expires = $this->expires;
		}

		$result = apc_store($key, $data, $expires);
		if ($result === false) {
			elgg_log("APC: FAILED TO SAVE $key", 'ERROR');
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
		$key = $this->makeKey($key);
		$result = apc_fetch($key);
		
		if ($result === false) {
			elgg_log("APC: FAILED TO LOAD $key", 'ERROR');
		}

		return $result;
	}

	/**
	 * Delete data
	 *
	 * @param string $key Name of data
	 *
	 * @return bool
	 */
	public function delete($key) {
		$key = $this->makeKey($key);

		return apc_delete($key);
	}

	/**
	 * Clears the entire cache?
	 *
	 * @todo write or remove.
	 *
	 * @return true
	 */
	public function clear() {
		// DISABLE clearing for now - you must use delete on a specific key.
		return true;

		// @todo Namespaces as in #532
	}
}
