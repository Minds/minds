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

	/** @var ElggSessionStorage */
	protected $storage;

	/** @var ElggUser */
	protected $loggedInUser;

	/**
	 * We use reference to $_SESSION variable as storage. 
	 */
	public function __construct(ElggSessionStorage $storage) {
		$this->storage = $storage;
		$this->loggedInUser = null;
	}
	
	/**
     * Start the session
     *
     * @return boolean
     * @throws RuntimeException If session fails to start.
     * @since 1.9
     */
    public function start() {
            $result = $this->storage->start();
            //$this->generateSessionToken();
            return $result;
    }

    /**
     * Migrates the session to a new session id while maintaining session attributes
     *
     * @param boolean $destroy Whether to delete the session or let gc handle clean up
     * @return boolean
     * @since 1.9
     */
    public function migrate($destroy = false) {
            return $this->storage->regenerate($destroy);
    }

    /**
     * Invalidates the session
     *
     * Deletes session data and session persistence. Starts a new session.
     *
     * @return boolean
     * @since 1.9
     */
    public function invalidate() {
            $this->storage->clear();
            $this->loggedInUser = null;
            $result = $this->migrate(true);
            $this->generateSessionToken();
            return $result;
    }

    /**
     * Has the session been started
     *
     * @return boolean
     * @since 1.9
     */
    public function isStarted() {
            return $this->storage->isStarted();
    }

    /**
     * Get the session ID
     *
     * @return string
     * @since 1.9
     */
    public function getId() {
            return $this->storage->getId();
    }

    /**
     * Set the session ID
     *
     * @param string $id Session ID
     * @return void
     * @since 1.9
     */
    public function setId($id) {
            $this->storage->setId($id);
    }

    /**
     * Get the session name
     *
     * @return string
     */
    public function getName() {
            return $this->storage->getName();
    }

    /**
     * Set the session name
     *
     * @param string $name Session name
     * @return void
     */
    public function setName($name) {
            $this->storage->setName($name);
    }

    /**
     * Get an attribute of the session
     *
     * @param string $name    Name of the attribute to get
     * @param mixed  $default Value to return if attribute is not set (default is null)
     * @return mixed
     */
    public function get($name, $default = null) {
            return $this->storage->get($name, $default);
    }

    /**
     * Set an attribute
     *
     * @param string $name  Name of the attribute to set
     * @param mixed  $value Value to be set
     * @return void
     */
    public function set($name, $value) {
            $this->storage->set($name, $value);
    }
	
	/**
     * Test if property is set either as an attribute or metadata.
     *
     * @param string $key The name of the attribute or metadata.
     *
     * @return bool
     * @deprecated 1.9 Use has()
     */
    public function __isset($key) {
            return $this->offsetExists($key);
    }
	
	
	/**
	 * Nests array parameters in ArrayObject instances to allow unset to work on multidimensional arrays
	 * @see ArrayAccess::offsetSet()
	 */
	public function offsetSet($index, $newval) {var_dump($index, $newval);
		$_SESSION[$key] = $newval;
		$this->set($index, $newval);
	}
	
	public function offsetGet($key){
		if (in_array($key, array('user', 'id', 'name', 'username'))) {
                       // elgg_deprecated_notice("Only 'guid' is stored in session for user now", 1.9);
                        if ($this->loggedInUser) {
                                switch ($key) {
                                        case 'user':
                                                return $this->loggedInUser;
                                                break;
                                        case 'id':
                                                return $this->loggedInUser->guid;
                                                break;
                                        case 'name':
                                        case 'username':
                                                return $this->loggedInUser->$key;
                                                break;
                                }
                        } else {
                               // return null;
                        }
                }

		if($this->get($key)){
			return $this->get($key);
		}

                if (isset($_SESSION[$key])) {
                        return $_SESSION[$key];
                }

                $orig_value = null;
                $value = elgg_trigger_plugin_hook('session:get', $key, null, $orig_value);
                if ($orig_value !== $value) {
                        elgg_deprecated_notice("Plugin hook session:get has been deprecated.", 1.9);
                }

                $_SESSION[$key] = $value;
				if($value){
					return $this->get($key);
				}
                return $value;
	}

	/**
         * Unset a value from the cache and the session.
         *
         * @see ArrayAccess::offsetUnset()
         *
         * @param mixed $key Name
         *
         * @return void
         * @deprecated 1.9 Use remove()
         */
        public function offsetUnset($key) {
                unset($_SESSION[$key]);
        }

        /**
         * Return whether the value is set in either the session or the cache.
         *
         * @see ArrayAccess::offsetExists()
         *
         * @param int $offset Offset
         *
         * @return bool
         * @deprecated 1.9 Use has()
         */
        public function offsetExists($offset) {

                if (in_array($offset, array('user', 'id', 'name', 'username'))) {
                        return (bool)$this->loggedInUser;
                }

                if (isset($_SESSION[$offset])) {
                        return true;
                }

                if ($this->offsetGet($offset)) {
                        return true;
                }

                return false;
        }

}
