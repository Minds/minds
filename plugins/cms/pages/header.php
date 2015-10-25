<?php

namespace minds\plugin\cms\pages;

use Minds\Core;
use Minds\Interfaces;
//use minds\plugin\comments;
use minds\plugin\cms\entities;
use minds\plugin\cms\exceptions;

class header extends core\page implements Interfaces\page{
	
	public $context = 'cms';
	

	public function get($pages){
	    $ia = elgg_set_ignore_access(true);	
        $page = new entities\page($pages[0]);
		$header = new \ElggFile();
		$header->owner_guid = $page->owner_guid;
		$header->setFilename("cms/page/{$page->guid}.jpg");
        elgg_set_ignore_access($ia);


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
    
