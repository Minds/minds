<?php 
/**
 * The session storage handler
 */
 
namespace minds\core\data;

if (version_compare(phpversion(), '5.4.0', '<')) {
	require_once(__MINDS_ROOT__ . '/engine/classes/stub/SessionHandlerInterface.php');	
}

class sessions implements \SessionHandlerInterface{

	private $db;

	public function open($save_path , $name){
		$this->db = new call('session');
	}

	public function close(){
 		return true;
	}
	
	public function read($session_id ){

		try {
			$result = $this->db->getRow($session_id);
			$this->cache[$session_id] = $result;
			
			if($result){
				//load serialized owner entity & add to cache
				return $result['data'];
		    }
		} catch (Exception $e) {
 			return false;
		}
		
		return '';

	}

   	public function write( $session_id , $session_data ){
		
		$time = time();
		$params = session_get_cookie_params();
		try {
			
			$result = $this->db->insert($session_id, array('ts'=>$time,'data'=>$session_data), $params['lifetime']);
		
			if($result !== false)
				return true;

		} catch (Exception $e) {
		}

		return false;
	}
	
	public function destroy($session_id ) {
		try {
			return (bool)$this->db->removeRow($session_id);
		} catch (Exception $e) {
			return false;
		}
	}

	public function gc( $maxlifetime) {
		return true;
	}
}
