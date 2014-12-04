<?php

namespace minds\plugin\cms\pages;

use minds\core;
use minds\interfaces;
//use minds\plugin\comments;
use minds\plugin\cms\entities;
use minds\plugin\cms\exceptions;

class header extends core\page implements interfaces\page{
	
	public $context = 'cms';
	

	public function get($pages){
		
		$page = new entities\page($pages[0]);
		$header = new \ElggFile();
		$header->owner_guid = $page->owner_guid;
		$header->setFilename("cms/page/{$page->guid}.jpg");
	
		header('Content-Type: image/jpeg');
		header('Expires: ' . date('r', time() + 864000));
		header("Pragma: public");
		header("Cache-Control: public");
		echo file_get_contents($header->getFilenameOnFilestore());
		
	}

	public function post($pages){}
	
	public function put($pages){}
	
	public function delete($pages){}
	
}
    
