<?php
/**
 * The session storage handler
 */

namespace Minds\Core\Data;

use Minds\Core\Data\cache\Redis;
use Minds\Core;

class Sessions implements \SessionHandlerInterface
{
    /** @var Call */
    private $db;
    /** @var Redis */
    private $cacher;

    private $cache;

    private $session;

    public function __construct($db = null, $cacher = null)
    {
    }

    public function open($save_path, $name)
    {
        error_log('DEPRECATED: Sessions->open called');
        return true;
    }

    public function close()
    {
        error_log('DEPRECATED: Sessions->close called');
        return true;
    }

    public function read($session_id)
    {
        error_log('DEPRECATED: Sessions->read called');
    }

    public function write($session_id, $session_data)
    {
        error_log('DEPRECATED: Sessions->write called');
    }

    public function destroy($session_id)
    {
        error_log('DEPRECATED: Sessions->destroy'); 
    }

    public function gc($maxlifetime)
    {
        return true;
    }

    /**
     * Destroy all of an user's sessions
     * @param string $guid
     * @return bool
     */
    public function destroyAll($guid)
    {
        error_log('DEPRECATED: Sessions->destroyAll called'); 
    }

    /**
     * Sync all of a user's sessions (uses the $_SESSION global)
     * @param string $guid
     * @return bool
     */
    public function syncAll($guid)
    {
        error_log('DEPRECATED: Sessions->syncAll called'); 
    }

    /**
     * Sync all of a user's sessions (uses the $_SESSION global)
     * @param string $guid
     * @return bool
     */
    public function syncRemote($guid, $user)
    {
        error_log('DEPRECATED: Sessions->syncRemote called');
    }

    /**
     * Returns the amount of opened sessions from a user
     * @param string $guid
     * @return int
     */
    public function count($guid)
    {
        error_log('DEPRECATED: Sessions->count called');
    }

    /**
     * Creates an User<->SessionID index, if not exists
     * @param string $session_id
     * @param number $ttl
     * @return bool
     */
    protected function addIndex($session_id, $ttl)
    {
        error_log('DEPRECATED: Sessions->addIndex called');
    }

    /**
     * Deletes an User<->SessionID index
     * @param string $session_id
     * @return bool
     */
    protected function removeIndex($session_id)
    {
        error_log('DEPRECATED: Sessions->removeIndex called');
    }

    /**
     * Gets user's GUID from session (if exists)
     * @return mixed
     */
    protected function getUserGuid()
    {
        return Core\Session::getLoggedInUserGuid(); 
    }
}
