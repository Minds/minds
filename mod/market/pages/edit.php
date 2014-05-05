<?php
/**
 * Market view page
 */
namespace minds\plugin\market\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\market;
use minds\plugin\market\entities;

class edit extends core\page implements interfaces\page{
	
	/**
	 * Display the edit page for the item
	 */
	public function get($pages){
		
		$form_data = array();
		
		if(isset($pages[0])){
			$item = entities\item($pages[0]);
			$form_data = array(
				'title' => $item->title,
				'description'=> $item->description,
				'price'=> $item->price,
				'category' => $item->category
			);
		}
		
		$form = \elgg_view_form('market/edit', array('method'=>'POST', 'action'=>$_SERVER['REQUEST_URI']), $form_data);
		
		$body = \elgg_view_layout('content', array('content'=>$form));
		
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
