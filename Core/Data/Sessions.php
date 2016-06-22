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

    public function open($save_path, $name)
    {
        $this->db = new Call('session');
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

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function gc($maxlifetime)
    {
        return true;
    }
}
