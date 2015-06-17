<?php 
/**
 * The session storage handler
 */
 
namespace Minds\Core\Data;

if (version_compare(phpversion(), '5.4.0', '<')) {
	require_once(__MINDS_ROOT__ . '/engine/classes/stub/SessionHandlerInterface.php');	
}

class Sessions implements \SessionHandlerInterface{

	private $db;

	public function open($save_path , $name){
		$this->db = new Call('session');
        return true;
	}

	public function close(){
 		return true;
	}
	
	public function read($session_id ){

		try {
			if(function_exists('apc_fetch')){
				if($result = apc_fetch($session_id)){
					return $result;
				}

			}
			$result = $this->db->getRow($session_id);
			$this->cache[$session_id] = $result;
			
			if($result){
				//load serialized owner entity & add to cache
				return $result['data'];
		    }
		} catch (Exception $e) {
 			return false;
		}
		
		return false;

	}

   	public function write( $session_id , $session_data ){
		
		$time = time();
		$params = session_get_cookie_params();

		try {
			if(function_exists('apc_store'))
				apc_store($session_id, $session_data, 60);	
			$result = $this->db->insert($session_id, array('ts'=>$time,'data'=>$session_data), $params['lifetime']);

			if($result !== false)
				return true;

		} catch (Exception $e) {
			error_log('sessions write error: '.$e->getMessage());
		}

		return false;
	}
	
	public function destroy($session_id ) {
		try {
			//$this->db->removeRow($session_id);

		    return true;
        } catch (Exception $e) {
			return false;
		}
	}

	public function gc( $maxlifetime) {
		return true;
	}
}
