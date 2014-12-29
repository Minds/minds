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
use minds\entities;
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
	$guid = $image->save();	
	$dir = $image->getFilenameOnFilestore() . "/image/$image->batch_guid/$image->guid";	
	$image->filename = "$dir/upload.jpg";
	if (!file_exists($dir)) {
	    mkdir($dir, 0755, true);
	}

	/**
	 * PHP PUT is a bit tricky, this should really be in a helper function
	 * @todo ^^
	 */
	$putdata = fopen("php://input", "r");
	$fp = fopen($image->filename, "w");
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
        
