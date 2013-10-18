<?php
/**
 * Magic session class.
 * This class is intended to extend the $_SESSION magic variable by providing an API hook
 * to plug in other values.
 *
 * Primarily this is intended to provide a way of supplying "logged in user"
 * details without touching the session (which can cause problems when
 * accessed server side).
 *
 * If a value is present in the session then that value is returned, otherwise
 * a plugin hook 'session:get', '$var' is called, where $var is the variable
 * being requested.
 *
 * Setting values will store variables in the session in the normal way.
 *
 * LIMITATIONS: You can not access multidimensional arrays
 *
 * @package    Elgg.Core
 * @subpackage Sessions
 */
class ElggSession extends ArrayObject {

	/**
	 * We use reference to $_SESSION variable as storage. 
	 */
	public function __construct($val = null) {
		if ($val===null) {
			if (!is_array($_SESSION)) {
				$_SESSION = array();
			}
			parent::__construct(&$_SESSION);
		} else {
			parent::__construct(&$val);
		}
	}
	
	/**
	 * Nests array parameters in ArrayObject instances to allow unset to work on multidimensional arrays
	 * @see ArrayAccess::offsetSet()
	 */
	public function offsetSet($index, $newval) {
		if (is_array($newval) && !($newval instanceof ArrayObject)) {
			unset($this[$index]);
			$val = new ArrayObject(&$newval);
			parent::offsetSet($index, $val);
		} else {
			parent::offsetSet($index, $newval);
		}
	}
	
// 	/**
// 	 * Test if property is set either as an attribute or metadata.
// 	 *
// 	 * @param string $key The name of the attribute or metadata.
// 	 *
// 	 * @return bool
// 	 */
// 	function __isset($key) {
// 		return $this->offsetExists($key);
// 	}

	/**
	 * Alias to ::offsetGet()
	 *
	 * @param string $key Name
	 *
	 * @return mixed
	 */
	function get($key) {
		return $this->offsetGet($key);
	}

	/**
	 * Alias to ::offsetSet()
	 *
	 * @param string $key   Name
	 * @param mixed  $value Value
	 *
	 * @return void
	 */
	function set($key, $value) {
		$this->offsetSet($key, $value);
	}

	/**
	 * Alias to offsetUnset()
	 *
	 * @param string $key Name
	 *
	 * @return void
	 */
	function del($key) {
		$this->offsetUnset($key);
	}

}
