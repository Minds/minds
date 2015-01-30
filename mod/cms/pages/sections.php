<?php

namespace minds\plugin\cms\pages;

use minds\core;
use minds\interfaces;
use minds\plugin\cms\entities;

class sections extends core\page implements interfaces\page{
	
	public $context = 'cms';
	
	/**
	 * Get requests
	 */
	public function get($pages){
		switch($pages[1]){
			case 'bg':
				
				$section = new entities\section($pages[0]);
							
				$file = new \ElggFile();
				$file->owner_guid = $section->owner_guid;
				$file->setFilename("cms/sections/{$section->guid}.jpg");
				$contents = @file_get_contents($file->getFilenameOnFilestore());
				if (!empty($contents)) {
					header("Content-type: image/jpeg");
					header('Expires: ' . date('r',  strtotime("today+6 months")), true);
					header("Pragma: public");
					header("Cache-Control: public");
					header("Content-Length: " . strlen($contents));
					header("ETag: $etag");
					header("X-No-Client-Cache:0");
					// this chunking is done for supposedly better performance
					$split_string = str_split($contents, 1024);
					foreach ($split_string as $chunk) {
						echo $chunk;
					}
					exit;
				}
				break;
		}
	}


	
	public function post($pages){
		
		$section = new entities\section($pages[0]);
		
		if(isset($_FILES)){
			
			$guid = $pages[0];
			foreach($_FILES as $file){
				
				global $CONFIG;
				
				$resized = get_resized_image_from_existing_file($file['tmp_name'], 2000, NULL, false, NULL, NULL, NULL, NULL, true, 'jpeg', 80);
				
				$file = new \ElggFile();
				$file->owner_guid = $owner->guid;
				$file->setFilename("cms/sections/{$guid}.jpg");
				$file->open('write');
				$file->write($resized);
				$file->close();
				
				$section->background = true;
				$section->last_updated = time();
				$section->save();
				
				echo elgg_get_site_url() . "s/$guid/bg/".time();
				exit;
			}
		}
		
		$vars = array(
			'content'=>'',
			'leftH2' => '',
			'leftP' => '',
			'rightH2' => '',
			'rightP' => '',
			'color' => '',
			'position' => 0,
			'href' => '',
			'top_offset' => '',
			'overlay_colour' => '',
			'overlay_opacity' => 0.5,
			'size' => 'thin'
		);
		foreach($_POST as $k=>$v){
			if(!isset($vars[$k]))
				continue;
			
			$section->$k = $v;

		}

		echo $section->save();		
		
	}
	
	/**
	 * Adds section to a group
	 */
	public function put($pages){
		$section = new entities\section();
		$section->group = $pages[0];
		$section->version = 2;
		
		$guid = $section->save();
		//$section->guid = $guid;//duh, it should already do this!!!
		 
		echo elgg_view('cms/sections/section', array('section'=>$section));
	}
	
	/**
	 * Removes a section
	 */
	public function delete($pages){
		$section = new entities\section($pages[0]);
		$section->delete($pages[0]);
	}
	
}
    
