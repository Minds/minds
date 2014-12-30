<?php
/**
 * Minds Archive API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\archive\api\v1;

use minds\core;
use minds\interfaces;
use minds\plugin\archive\entities;
use minds\api\factory;

class archive implements interfaces\api{

    /**
     * Return the archive items
     * @param array $pages
     * 
     * API:: /v1/archive/:filter || :guid
     */      
    public function get($pages){


        return factory::response($response);
        
    }
    
    /**
     * Update entity based on guid
     * @param array $pages
     * 
     * API:: /v1/archive/:guid
     */
    public function post($pages){

	$guid = $pages[0];
	$album = NULL;

	if(isset($_POST['album_guid'])){
		$album = new entities\album($_POST['album_guid']);
		if(!$album->guid)
			return factory::response(array('error'=>'Sorry, the album was not found'));
	} else {
		//does the user already have and album?
		$albums = core\entities::get(array('subtype'=>'album', 'owner_guid'=>elgg_get_logged_in_user_guid()));
		if($albums){
			if(isset($_POST['album_title'])){
				foreach($albums as $a){
					if($_POST['album_title'] == $a->title){
						$album = $a;
						break;
					}
				}
			}
		}

		if(!$album){
			$album = new entities\album();
			if(isset($_POST['album_title'])){
				$album->title = $_POST['album_title'];
			} else {
				$album->title = "API Uploads";
			}
			$album->save();
			$ablums = array($album);
		}
	} 

	$index = new core\data\indexes('object:container');
	$index->set($album->guid, array($guid=>$guid));

	$db = new core\data\call('entities');
	$db->insert($guid, array('container_guid'=>$album->guid));
	
	$activity = new \minds\entities\activity();
	$activity->setCustom('batch', array(array('src'=>elgg_get_site_url() . 'archive/thumbnail/'.$guid, 'href'=>elgg_get_site_url() . 'archive/view/'.$album->guid.'/'.$guid)))
		//->setMessage('Added '. count($guids) . ' new images. <a href="'.elgg_get_site_url().'archive/view/'.$album_guid.'">View</a>')
		->save();

         return factory::response(array());
        
    }
    
    /**
     * Upload a file to the archive
     * @param array $pages
     * 
     * API:: /v1/archive
     */
    public function put($pages){
	
	$image = new \minds\plugin\archive\entities\image();
	$image->batch_guid = 0;
	$image->access_id = 2;
	$guid = $image->save();
	$dir = $image->getFilenameOnFilestore() . "image/$image->batch_guid/$image->guid";	
	$image->filename = "/image/$image->batch_guid/$image->guid/master.jpg";
	if (!file_exists($dir)) {    
		mkdir($dir, 0755, true);
	}

	/**
	 * PHP PUT is a bit tricky, this should really be in a helper function
	 * @todo ^^
	 */
	$putdata = fopen("php://input", "r");
	$fp = fopen("$dir/master.jpg", "w");
	$raw = '';
	while ($data = fread($putdata, 1024)){
	    $raw .= $data;
	}
	
	$boundary = substr($raw, 0, strpos($raw, "\r\n"));
	$parts = array_slice(explode($boundary, $raw), 1);

	foreach($parts as $part){
	    // If this is the last part, break
	    if ($part == "--\r\n")
		break;
	    
	// Separate content from headers
	    $part = ltrim($part, "\r\n");
	    list($raw_headers, $body) = explode("\r\n\r\n", $part, 2);	
	}
	

	fwrite($fp, $body);
	fclose($fp);
	fclose($putdata);


	$loc = $image->getFilenameOnFilestore();
	$image->createThumbnails();
        $image->save();
        return factory::response(array('guid'=>$guid, "location"=>$loc));
        
    }
    
    /**
     * Delete an entity
     * @param array $pages
     * 
     * API:: /v1/archive/:guid
     */
    public function delete($pages){
     
         return factory::response();
        
    }
    
}
        
