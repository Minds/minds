<?php
/**
 * Directory pages
 */
namespace minds\plugin\channel\pages;

use minds\core;
use minds\interfaces;
use minds\entities;

class directory extends core\page implements interfaces\page{
	
	public $context = 'channel';
	

	public function get($pages){

		echo $this->render(array('body'=>'hello!'));

	}
	
	public function post($pages){}

	public function put($pages){}

	public function delete($pages){}
	
}
