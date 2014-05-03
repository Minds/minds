<?php
/**
 * Market view page
 */
namespace minds\plugin\market\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\market;
use minds\plugin\market\entities;

class view extends core\page implements interfaces\page{
	
	/**
	 * Display the edit page for the item
	 */
	public function get($pages){
		
		if(isset($pages[0])){
			$item = entities\item($pages[0]);
		}
		
		$body = \elgg_view_layout('content', array());
		
		echo $this->render(array('body'=>$body));
	}
	
	/**
	 * Creates or edits an item
	 */
	public function post($pages){
		if(isset($pages[0]) && is_int($pages[0]))
			$item = new market\entities\item($pages[0]);
		else 
			$item = new market\entities\item();
		
		$item->title = $_POST['title'];
		$item->description = $_POST['description'];
		$item->price = $_POST['price'];
		$item->category = $_POST['category'];
		$guid = $item->save();
		
		if($guid){
			$this->forward($item->getURL());
		} else {
			echo "An error occured in saving this item \n";
		}
			
	}
	
	public function put($pages){
		
	}
	public function delete($pages){
		
	}
	
}
