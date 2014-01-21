<?php 
class ElggSessionHandler{

	function __construct(){
	}

	function open(){
		return true;

	}

    function close(){
 		return true;
    }
	
	function read($id){

		try {
			$db = new DatabaseCall('session');
			$result = $db->getRow($id);

			if($result){
				//load serialized owner entity & add to cache
				return $result['data'];
		    }
		} catch (Exception $e) {
 			return false;
		}
		
		return '';

	}

    function write($id, $sess_data){

		$time = time();

		try {
			$db = new DatabaseCall('session');
	        $result = $db->insert($id, array('ts'=>$time,'data'=>$sess_data));
		
			if($result !== false){
				return true;
			}

		} catch (Exception $e) {
		}

		return false;
	}

	function destroy($id) {
		global $DB_PREFIX;
	
		try {
			$db = new DatabaseCall('session');
			return (bool)$db->removeRow($id);
		} catch (Exception $e) {
			return false;
		}
	}

	function gc($maxlifetime) {
		global $DB_PREFIX;
		$life = time() - $maxlifetime;
		return true;
	}
}
