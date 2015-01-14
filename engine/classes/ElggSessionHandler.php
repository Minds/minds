<?php 
if (version_compare(phpversion(), '5.4.0', '<')) {
	require_once(dirname(__FILE__) . '/stub/SessionHandlerInterface.php');	
}
class ElggSessionHandler implements SessionHandlerInterface{

	private $db;

	public function open($save_path , $name){
		$this->db = new Minds\Core\Data\Call('session');
	}

	public function close(){
 		return true;
	}
	
	public function read($session_id ){

		try {
			$result = $this->db->getRow($session_id);

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

		try {
	        	$result = $this->db->insert($session_id, array('ts'=>$time,'data'=>$session_data));
		
			if($result !== false){
				return true;
			}

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
		$life = time() - $maxlifetime;
		return true;
	}
}
