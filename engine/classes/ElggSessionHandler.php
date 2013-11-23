<?php 
class ElggSessionHandler{

	function __construct(){
		global $DB;
		$this->db = $DB->cfs['session']; 
	}

	function open(){
		global $sess_save_path;
		$sess_save_path = $save_path;

		return true;

	}

    function close(){
 		return true;
    }
	
	function read($id){

		try {
			$result = $this->db->get($id);

			if($result){
				//load serialized owner entity & add to cache
				return $result['data'];
		    }
		} catch (Exception $e) {
 		
			// Fall back to file store in this case, since this likely means
		    // that the database hasn't been upgraded
		    global $sess_save_path;
			$sess_file = "$sess_save_path/sess_$id";
			return (string) @file_get_contents($sess_file);
		}
		
		return '';

	}

    function write($id, $sess_data){

		$time = time();

		try {
	        $result = $this->db->insert($id, array('ts'=>$time,'data'=>$sess_data));
		
			if($result !== false){
				return true;
			}

		} catch (Exception $e) {
			// Fall back to file store in this case, since this likely means
            // that the database hasn't been upgraded
			global $sess_save_path;

			$sess_file = "$sess_save_path/sess_$id";
			if ($fp = @fopen($sess_file, "w")) {
				$return = fwrite($fp, $sess_data);
				fclose($fp);
				return $return;
			}
		}

		return false;
	}

	function destroy($id) {
		global $DB_PREFIX;
	
		try {
			return (bool)$this->db->remove($id);
		} catch (Exception $e) {
			// Fall back to file store in this case, since this likely means that
			// the database hasn't been upgraded
			global $sess_save_path;

			$sess_file = "$sess_save_path/sess_$id";
			return @unlink($sess_file);
		}
	}

	function gc($maxlifetime) {
		global $DB_PREFIX;
		$life = time() - $maxlifetime;
		return true;
	}
}
