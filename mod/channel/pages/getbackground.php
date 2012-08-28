<?php
	/**
	* CustomStyle - Returns custombackground for user
	* 
	* @package customstyle
	* @author ColdTrick IT Solutions
	* @copyright Coldtrick IT Solutions 2009
	* @link http://www.coldtrick.com/
	*/
	header('Content-Type: image/jpeg');
	
	if(get_input('id')){
		$filehandler = new ElggFile();
		$filehandler->owner_guid = get_input('id');
		if(get_input('thumb') == "true"){
			$filehandler->setFilename('custombackground_thumb');
			if (!$filehandler->exists()){
					
				$filehandler->setFilename('custombackground');
			}
		} else {
			$filehandler->setFilename('custombackground');
		}
		
		if ($filehandler->exists()){
			echo $filehandler->grabFile();
		} 
	}
	
?>