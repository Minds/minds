<?php
/**
 * Market image controller
 */
namespace minds\plugin\market\pages;

use Minds\Core;
use minds\interfaces;
use minds\plugin\market\entities;

class image extends core\page implements interfaces\page{
	

	public function get($pages){
		
		$guid = $pages[0];
		$size = $pages[1];
		
		$item = new entities\item($guid);
		$file = new \ElggFile();
		$file->owner_guid = $item->owner_guid;
		$file->setFilename("market/{$guid}/$size.jpg");
		
		header('Content-Type: image/jpeg');
		header('Expires: ' . date('r', time() + 864000));
		header("Pragma: public");
 		header("Cache-Control: public");
		
		echo file_get_contents($file->getFilenameonFilestore()); 
		
	}
	
	public function post($pages){}
	public function put($pages){}
	public function delete($pages){}
	
}