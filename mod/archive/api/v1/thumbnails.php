<?php
/**
 * Minds Archive API
 *
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\archive\api\v1;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class thumbnails implements Interfaces\api, Interfaces\ApiIgnorePam{

    /**
     * Return the archive items
     * @param array $pages
     *
     * API:: /v1/archive/:filter || :guid
     */
    public function get($pages){

        global $CONFIG;

        if(is_numeric($pages[0])){
            $entity = entities\Factory::build($pages[0]);
          if(!$entity)
            exit;

          $user = $entity->getOwnerEntity(false);
    			if(isset($user->legacy_guid) && $user->legacy_guid)
    				$user_guid = $user->legacy_guid;
    			else
    				$user_guid = $user->guid;

    			$user_path = date('Y/m/d/', $user->time_created) . $user_guid;

    			$data_root = $CONFIG->dataroot;
    			$filename = "$data_root$user_path/archive/thumbnails/$entity->guid.jpg";


    			switch($entity->subtype){
    				case 'image':
    					if($entity->filename)
    						$filename = "$data_root$user_path/$entity->filename";

    					if(isset($page[2])  && $size = $page[2]){
    						if(!isset($entity->batch_guid))
    							$entity->batch_guid = $this->container_guid;

    						$filename = "$data_root$user_path/image/$entity->batch_guid/$entity->guid/$size.jpg";
    					}
    					break;
    				case 'album':
    					//get the first image attached to this album
    					$image_guids = $entity->getChildrenGuids();
    					forward($CONFIG->cdn_url.'archive/thumbnail/'.current($image_guids));
    					break;
    				case 'video':
    					if(!$entity->thumbnail){
    						$cinemr = $entity->cinemr();
                            $ret = $cinemr::factory('media')->get($entity->cinemr_guid.'/thumbnail');
                            forward($ret);
                            exit;
    					}
                      break;
                    case 'audio':
                        $filename = elgg_get_site_url() . 'mod/archive/graphics/wave.png';
                        break;
    			}

    			if(!file_exists($filename)){
    				$user_path = date('Y/m/d/', $user->time_created) . $user->guid;
    				$filename = "$data_root$user_path/archive/thumbnails/$entity->guid.jpg";
    			}

    			$contents = @file_get_contents($filename);

    			header("Content-type: image/jpeg");
    			header('Expires: ' . date('r', strtotime("today+6 months")), true);
    			header("Pragma: public");
    			header("Cache-Control: public");
    			header("Content-Length: " . strlen($contents));
    			// this chunking is done for supposedly better performance
    			$split_string = str_split($contents, 1024);
    			foreach ($split_string as $chunk) {
    			     echo $chunk;
    			}
    			exit;
        }

    }

    /**
     */
    public function post($pages){

    }

    /**
     */
    public function put($pages){

    }

    /**
     */
    public function delete($pages){

         return Factory::response();

    }

}
