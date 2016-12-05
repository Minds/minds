<?php
/**
 * The session storage handler
 */

namespace Minds\Core\Data;

if (version_compare(phpversion(), '5.4.0', '<')) {
    require_once(__MINDS_ROOT__ . '/engine/classes/stub/SessionHandlerInterface.php');
}

class Sessions implements \SessionHandlerInterface
{
    private $db;

    private $session;

    public function __construct()
    {
        $this->db = new Call('session');
    }

    public function open($save_path, $name)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($session_id)
    {
        try {
            $cacher = cache\factory::build();
            if ($result = $cacher->get($session_id)) {
                $this->session = ['data' => $result];
                return $result;
            }

            $result = $this->db->getRow($session_id);
            $this->cache[$session_id] = $result;
            $this->session = $result;

            if ($result) {
                //load serialized owner entity & add to cache
                return $result['data'];
            }
        } catch (Exception $e) {
            return '';
        }

        return '';
    }

    public function write($session_id, $session_data)
    {
        $time = time();
        $params = session_get_cookie_params();

        try {
            if ($this->session && $this->session['data'] == $session_data) {
                return true; //no change detected
            }

            $cacher = cache\factory::build();
            $cacher->set($session_id, $session_data, $params['lifetime']);

            $result = $this->db->insert($session_id, array('ts'=>$time,'data'=>$session_data), $params['lifetime']);
            $this->addIndex($session_id, $params['lifetime']);

            if ($result !== false) {
                return true;
            }
        } catch (Exception $e) {
            error_log('sessions write error: '.$e->getMessage());
        }

        return false;
    }

    public function destroy($session_id)
    {
        try {
            $cacher = cache\factory::build();
            $cacher->destroy($session_id);

            $this->db->removeRow($session_id);
            $this->removeIndex($session_id);

            return true;
        } catch (Exception $e) {
            return false;
        }
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
        $result = $this->db->getRow('user:' . $guid, [
            'limit' => 99999,
            'reversed' => false
        ]);

        if (!$result) {
            return true;
        }

        foreach ($result as $session_id => $ts) {
            $this->destroy($session_id);
        }

        return true;
    }

    /**
     * Creates an User<->SessionID index, if not exists
     * @param string $session_id
     * @param number $ttl
     * @return bool
     */
    protected function addIndex($session_id, $ttl)
    {
        $guid = $this->getUserGuid();

        if (!$guid) {
            return false;
        }

        try {
            $this->db->insert('user:' . $guid, [ $session_id => time() ], $ttl);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Deletes an User<->SessionID index
     * @param string $session_id
     * @return bool
     */
    protected function removeIndex($session_id)
    {
        $guid = $this->getUserGuid();

        if (!$guid) {
            return false;
        }

        try {
            $this->db->removeAttributes('user:' . $guid, [ $session_id ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Gets user's GUID from session (if exists)
     * @return mixed
     */
    protected function getUserGuid()
    {
        if (isset($_SESSION['user']) && $_SESSION['user']) {
            return $_SESSION['user']->guid;
        } elseif (isset($_SESSION['guid']) && $_SESSION['guid']) {
            return $_SESSION['guid'];
        }

        return false;
    }
}
