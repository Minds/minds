<?php 
class ElggSessionHandler{

	private $db;

	public function open(){
		$this->db = new DatabaseCall('session');
	}

	public function close(){
 		return true;
	}
	
	public function read($id){

		try {
			$result = $this->db->getRow($id);

			if($result){
				//load serialized owner entity & add to cache
				return $result['data'];
		    }
		} catch (Exception $e) {
 			return false;
		}
		
		return '';

	}

   	public function write($id, $sess_data){

		$time = time();

		try {
	        	$result = $this->db->insert($id, array('ts'=>$time,'data'=>$sess_data));
		
			if($result !== false){
				return true;
			}

		} catch (Exception $e) {
		}

		return false;
	}
	
	public function destroy($id) {
		try {
			return (bool)$this->db->removeRow($id);
		} catch (Exception $e) {
			return false;
		}
	}

	public function gc($maxlifetime) {
		$life = time() - $maxlifetime;
		return true;
	}
}
