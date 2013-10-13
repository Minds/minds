<?php
/**
 * Wrapper for session data stored in cookie. Valid only for logged out users. You MUSTN'T convert logged in user to cookie session - that's extremally unsafe!
 *
 * @package    Elgg.Core
 * @subpackage Sessions
 */
class ElggSessionCookie extends ElggSession {
	
	const COOKIE_PREFIX = '_elgg_';
	private $root = null;
	private $rootKey = null;
	private $localstore = array();
	
	function setCookie($key, $newval) {
// 		global $SCT;
// 		if (!is_array($SCT)) {
// 			$SCT = array();
// 		}
// 		$SCT[$key] = $newval;
		if ($newval instanceof ArrayObject) {
			$newval = $newval->getArrayCopy();
		}
		if ($_COOKIE[ElggSessionCookie::COOKIE_PREFIX.$key] != json_encode($newval)) {
			setcookie(ElggSessionCookie::COOKIE_PREFIX.$key, json_encode($newval), (time() + (86400 * 30)), "/");
		}
	}
	
	function unsetCookie($key) {
// 		global $SCT;
// 		if (!is_array($SCT)) {
// 			$SCT = array();
// 		}
// 		unset($SCT[$key]);
		setcookie(ElggSessionCookie::COOKIE_PREFIX.$key, null, (time() - (86400 * 30)), "/");
	}
	
 	function getCookie($key) {
// 		global $SCT;
// 		return $SCT;
		return $_COOKIE[ElggSessionCookie::COOKIE_PREFIX.$key];
 	}

	static function filterCookiePrefixes($val) {
		if (is_array($val)) {
			$new = array();
			foreach ($val as $key => $val) {
				if (strpos($key, ElggSessionCookie::COOKIE_PREFIX)===0) {
					$key = substr($key, strlen(ElggSessionCookie::COOKIE_PREFIX));
					$new[$key] = json_decode($val, true);
				}
			}
			return $new;
		}
	}
	
	public function __construct($val = null, $rootKey = null, $root = null) {
		$this->root = $root;
		$this->rootKey = $rootKey;
		//do the initialization when value passed
		if (is_array($val)) {
			foreach ($val as $key => $val) {
				$this->offsetSet($key, $val);
			}
		}
	}
	
	private function updateGlobal($index, $newval = null) {
		if (isset($this->root)) {
			$arr = $this->root->getArrayCopy();
			$this->root->setCookie($this->rootKey, $arr[$this->rootKey]);
		} else {
			$this->setCookie($index, $newval);
		}
	}
	
	/**
	 * @see ElggSession::offsetSet()
	 */
	public function offsetSet($index, $newval) {
		//save changes locally (if array, initialize with value)
		if (is_array($newval) && !($newval instanceof ArrayObject)) {
			$newval = new ElggSessionCookie($newval, isset($this->rootKey)?$this->rootKey:$index, isset($this->root)?$this->root:$this);
		}
		
		//support for []=
		if ($index===null) {
			$this->localstore[] = $newval;
			$index = array_pop(array_keys($this->localstore));
		} else {
			$this->localstore[$index] = $newval;
		}
		
		//find root tree and save
		$this->updateGlobal($index, $newval);
	}
	
	/**
	 * @see ArrayObject::offsetGet()
	 */
	public function offsetGet($index) {
		if (array_key_exists($index, $this->localstore)) {
			return $this->localstore[$index];
		}
		return null;
	}
	
	/**
	 * @see ArrayObject::offsetUnset()
	 */
	public function offsetUnset($index) {
		//unset local
		unset($this->localstore[$index]);
		
		//find root tree and save
		if (isset($this->root)) {
			$this->updateGlobal($index);
		} else {
			$this->unsetCookie($index);
		}
	}
	
	/**
	 * @see ArrayObject::offsetExists()
	 */
	public function offsetExists($index) {
		return isset($this->localstore[$index]);
	}
	
	/**
	 * @see ArrayObject::getArrayCopy()
	 */
	public function getArrayCopy() {
		$ret = $this->localstore;
		foreach ($ret as $k => $v) {
			if ($v instanceof ArrayObject) {
				$ret[$k] = $v->getArrayCopy();
			}
		}
		return $ret;
	}
}
