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
	echo "User:: ";
	return factory::response(array('guid'=>elgg_get_logged_in_user_guid()));
	$image = new \minds\plugin\archive\entities\image();
	$image->save();	
	$dir = $image->getFilenameOnFilestore() . "/image/$image->batch_guid/$image->guid";	

	/* PUT data comes in on the stdin stream */
	$putdata = fopen("php://input", "r");

	/* Open a file for writing */
	$fp = fopen($dir, "w");

	/* Read the data 1 KB at a time
	   and write to the file */
	while ($data = fread($putdata, 1024))
	    fwrite($fp, $data);

	/* Close the streams */
	fclose($fp);
	fclose($putdata);
        
        return factory::response(array('guid'=>$guid));
        
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
        
