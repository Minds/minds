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
			$item = new entities\item($pages[0]);
			$form_data = array(
				'title' => $item->title,
				'description'=> $item->description,
				'price'=> $item->price,
				'category' => $item->category,
				'guid' => $item->guid
			);
		}
		
		$form = \elgg_view_form('market/edit', array(
				'method'=>'POST', 
				'action'=>isset($pages[0]) ? elgg_get_site_url() . 'market/item/edit/'.$pages[0] : $_SERVER['REQUEST_URI'],
				'enctype' => 'multipart/form-data'
			), $form_data);
		
		$body = \elgg_view_layout('one_sidebar', array(
			'content'=>$form,
			'sidebar' => elgg_view('market/sidebar'),
			'sidebar_class'=> 'elgg-sidebar-alt'
		));
		
		echo $this->render(array('body'=>$body));
	}
	
	/**
	 * Creates or edits an item
	 */
	public function post($pages){
		if(isset($pages[0]) && is_numeric($pages[0]))
			$item = new market\entities\item($pages[0]);
		else 
			$item = new market\entities\item();
		
		$item->title = $_POST['title'];
		$item->description = $_POST['description'];
		$item->price = $_POST['price'];
		$item->category = $_POST['category'];
		$item->size = $_POST['size'];
		$item->color = $_POST['color'];
		$item->stock = $_POST['stock'];

		$guid = $item->save();
		//is a file uploaded?
		if(is_uploaded_file($_FILES['image']['tmp_name'])){
			$sizes = array(
				'thumb' => array('w' => 200, 'h' => 200, 'square' => FALSE, 'upscale' =>true),
				'medium' => array('w' => 600, 'h' => 600, 'square' => FALSE, 'upscale' => true),
				'master' => array('w' => 2000, 'h' => 2000, 'square' => FALSE, 'upscale' => FALSE),
			);
			foreach($sizes as $size => $info){
				$resized = \get_resized_image_from_uploaded_file('image', $info['w'], $info['h'], $info['square'], $info['upscale']);
				$file = new \ElggFile();
				$file->owner_guid = $item->owner_guid;
				$file->setFilename("market/{$guid}/$size.jpg");
				$file->open('write');
				$file->write($resized);
				$file->close();
				$item->image = true;
			}
		}

		$item->save();
		
		
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
