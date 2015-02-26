<?php
/**
 * Minds Archive API
 * 
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\archive\api\v1;

use Minds\Core;
use minds\interfaces;
use minds\plugin\archive\entities;
use Minds\Api\Factory;

class archive implements interfaces\api{

    /**
     * Return the archive items
     * @param array $pages
     * 
     * API:: /v1/archive/:filter || :guid
     */      
    public function get($pages){
        $response = array();

        if(is_numeric($pages[0])){
            $entity = core\entities::build(new \minds\entities\entity($pages[0]));
            if(is_string($pages[1]) && $pages[1] == 'play'){
                //echo $entity->getSourceUrl('360.mp4'); exit;
                Header( "HTTP/1.1 301 Moved Permanently" ); 
                header("Location:" . $entity->getSourceUrl('360.mp4'));
                exit;    
            }
            $response = reset(factory::exportable(array($entity)));
            $response['transcodes'] = array(
                '360.mp4' => $entity->getSourceUrl('360.mp4'),
                '720.mp4' =>  $entity->getSourceUrl('720.mp4')
            );  
        }

        return Factory::response($response);
        
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

        $entity = core\entities::build(new \minds\entities\entity($guid));

        if($entity->subtype == 'image'){
            if(isset($_POST['album_guid'])){
                $album = new entities\album($_POST['album_guid']);
                if(!$album->guid)
                    return Factory::response(array('error'=>'Sorry, the album was not found'));
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
            $entity->container_guid = $album->guid; 
            $activity = new \minds\entities\activity();
                $activity->setCustom('batch', array(array('src'=>elgg_get_site_url() . 'archive/thumbnail/'.$guid, 'href'=>elgg_get_site_url() . 'archive/view/'.$album->guid.'/'.$guid)))
                        //->setMessage('Added '. count($guids) . ' new images. <a href="'.elgg_get_site_url().'archive/view/'.$album_guid.'">View</a>')
                    ->setFromEntity($activity)
                    ->setTitle($_POST['title'])
                    ->setBlurb($_POST['description'])
                        ->save();
        }

        $index = new core\Data\indexes('object:container');
        $index->set($album->guid, array($guid=>$guid));

        $allowed = array('title', 'description', 'license');
        foreach($allowed as $key){
            if(isset($_POST[$key])){
            $entity->$key = $_POST[$key];
            }
        }
        
        $entity->access_id = 2;
        $entity->save(true);

        if($entity->subtype == 'video'){

            $activity = new \minds\entities\activity();
            $activity->setCustom('video', array(
                'thumbnail_src'=>$entity->getIconUrl(),
                'guid'=>$entity->guid))
                ->setTitle($entity->title)
                ->setBlurb($entity->description)
                ->save();

        }	

         return Factory::response(array());
        
    }
    
    /**
     * Upload a file to the archive
     * @param array $pages
     * 
     * API:: /v1/archive/:type
     */
    public function put($pages){
	
	switch($pages[0]){

		case 'video':
			error_log(print_r($_FILES,true));
			$video = new entities\video();

			$fp = tmpfile();
			$metaDatas = stream_get_meta_data($fp);
			$tmpFilename = $metaDatas['uri'];
			$req = $this->parsePut();
                        $body = $req['body'];
			fwrite($fp, $body);
			$video->upload($tmpFilename);
			$guid = $video->save();
			 fclose($fp);
			break;
		case 'image':
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
			$fp = fopen("$dir/master.jpg", "w");
			$req = $this->parsePut();
			$body = $req['body'];			
			fwrite($fp, $body);
			fclose($fp);


			$loc = $image->getFilenameOnFilestore();
			$image->createThumbnails();
			$image->save();
	}

        return Factory::response(array('guid'=>$guid, "location"=>$loc));
        
    }
    
    /**
     * Delete an entity
     * @param array $pages
     * 
     * API:: /v1/archive/:guid
     */
    public function delete($pages){
     
         return Factory::response();
        
    }
   
    /**
     * Helper function, move this to a static class soon
     */
    public function parsePut(){
        $putdata = fopen("php://input", "r");
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

	fclose($putdata);
	return array('headers'=>$raw_headers, 'body'=>$body);
    }
 
}
        
