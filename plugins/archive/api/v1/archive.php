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
use Minds\Interfaces;
use Minds\Core\Events\Dispatcher;
use Minds\Entities as CoreEntities;
use minds\plugin\archive\entities;
use Minds\Api\Factory;

class archive implements Interfaces\Api, Interfaces\ApiIgnorePam{

    /**
     * Return the archive items
     * @param array $pages
     *
     * API:: /v1/archive/:filter || :guid
     */
    public function get($pages){
        $response = array();

        if(is_numeric($pages[0])){
            $entity = core\Entities::build(new \Minds\Entities\Entity($pages[0]));
            switch($entity->subtype){
                case "video":
                    Helpers\Counters::increment($pages[0], 'plays');
                    if(is_string($pages[1]) && $pages[1] == 'play'){
                        //echo $entity->getSourceUrl('360.mp4'); exit;
                        Header( "HTTP/1.1 301 Moved Permanently" );
                        if($entity->subtype == 'audio'){
                            header("Location:" . $entity->getSourceUrl('128.mp3'));
                        } else {
                        //    echo $entity->getSourceUrl('360.mp4'); exit;
                            header("Location:" . $entity->getSourceUrl('360.mp4'));
                        }
                        exit;
                    }
                    $response = reset(factory::exportable([$entity]));
                    $response['transcodes'] = [
                        '360.mp4' => $entity->getSourceUrl('360.mp4'),
                        '720.mp4' =>  $entity->getSourceUrl('720.mp4')
                    ];
                default:
                    $response['entity'] = $entity->export();
                    if(method_exists($entity, 'getAlbumChildrenGuids')){
                        $response['entity']['album_children_guids'] = $entity->getAlbumChildrenGuids();
                    }
            }

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
        Factory::isLoggedIn();
        $response = [];

        $owner_guid = Core\Session::getLoggedInUserGuid();
        $container_guid = isset($_POST['container_guid']) && $_POST['container_guid'] ? $_POST['container_guid'] : null;

        if(!is_numeric($pages[0])){
            //images should still use put, large videos use post because of memory issues.
            //some images are uploaded like videos though, if they don't have mime tags.. hack time!
            error_log("[upload][log]:: got type " . $pages[0]);
            if((strpos($_FILES['file']['type'], 'image') !== FALSE || @is_array(getimagesize($_FILES['file']['tmp_name'])))
                && (!isset($_SERVER['HTTP_X_XSRF_TOKEN']) || $pages[0] != "video")){
                error_log("[upload][log]:: detected {$pages[0]} is an image");
                error_log($_FILES['file']['type']);
                error_log(is_array(getimagesize($_FILES['file']['tmp_name'])));
                //error_log('image as a video..');
                $image = new \minds\plugin\archive\entities\image();
                $image->batch_guid = 0;
                $image->access_id = 2;
                $image->title = isset($_POST['name']) ? $_POST['name'] : '';

                $mature = isset($_POST['mature']) && !!$_POST['mature'];
                if ($image instanceof \Minds\Interfaces\Flaggable) {
                  $image->setFlag('mature', $mature);
                }
 
                $image->owner_guid = $owner_guid;

                if ($container_guid) {
                    $image->container_guid = $container_guid;
                }

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
                fwrite($fp, file_get_contents($_FILES['file']['tmp_name']));
                fclose($fp);

                $loc = $image->getFilenameOnFilestore();
                $image->createThumbnails();
                $image->access_id = 0;
                if(strpos($_FILES['file']['type'], 'gif') !== FALSE)
                    $image->gif = true;
                $image->save();
                $pages[0] = 'image';
            } else {
              switch($pages[0]){
                  case 'video':
                  default:
                    error_log("[upload][log]:: start video");
                      $video = new entities\video();
                      $video->upload($_FILES['file']['tmp_name']);
                      $video->access_id = 0;

                      $mature = isset($_POST['mature']) && !!$_POST['mature'];
                      if ($video instanceof \Minds\Interfaces\Flaggable) {
                        $video->setFlag('mature', $mature);
                      }

                      $video->owner_guid = $owner_guid;

                      if ($container_guid) {
                          $video->container_guid = $container_guid;
                      }

                      $guid = $video->save();
                      error_log("[upload][log]:: video saved as ($guid)");
                      break;
              }
            }
            return Factory::response(array('guid'=>$guid, "location"=>$loc));
        }

        $guid = $pages[0];
        $album = NULL;
        $activity_post = NULL;

        $entity = core\Entities::build(new \Minds\Entities\Entity($guid));
        if($entity->access_id == 0 && (isset($_POST['access_id']) && $_POST['access_id'] == 2))
          $activity_post = true;

        if ($entity instanceof \Minds\Interfaces\Flaggable) {
          $mature = isset($_POST['mature']) && !!$_POST['mature'];
          $entity->setFlag('mature', $mature);
        }

        //need a better check for if this was a mobile post.
        if($entity->subtype == 'image'){
            if(isset($_POST['album_guid'])){
                $album = new entities\album($_POST['album_guid']);
                if(!$album->guid)
                    return Factory::response(array('error'=>'Sorry, the album was not found'));
            } else {

                if (!$container_guid) {
                    //does the owner already have and album?
                    $albums = core\Entities::get(array('subtype'=>'album', 'owner_guid' => $owner_guid));
                    if(!$albums){
                      $album = new entities\album();
                      $album->owner_guid = $owner_guid;
                      $album->title = "My Album";
                      $album->save();
                    } else {
                      $album = $albums[0];
                    }
                    $album->addChildren(array($entity->guid => time()));
                    $entity->container_guid = $album->guid;
                }
                else {
                    $entity->container_guid = $container_guid;
                }

            }

            if($_POST['access_token'])
                $activity_post = true;

            if($activity_post){
              $activity = new \Minds\Entities\Activity();
              $activity->setCustom('batch', [[
                'src'=>elgg_get_site_url() . 'archive/thumbnail/'.$guid,
                'href'=>elgg_get_site_url() . 'archive/view/'.$album->guid.'/'.$guid,
                'mature'=>$entity instanceof \Minds\Interfaces\Flaggable ? $entity->getFlag('mature') : false
              ]])
                  //->setMessage('Added '. count($guids) . ' new images. <a href="'.elgg_get_site_url().'archive/view/'.$album_guid.'">View</a>')
                  ->setFromEntity($entity)
                  ->setTitle($_POST['title'])
                  ->setBlurb($_POST['description'])
                  ->save();
              $response['activity_guid'] = $activity->guid;
            } else {
                //get the attached activity items
                $db = new Core\Data\Call('entities_by_time');
                $e = new Core\Data\Call('entities');
                foreach($db->getRow("activity:entitylink:$entity->guid") as $guid => $ts){
                    $e->insert($guid, ['title' => $_POST['title']]);
                }
            }
        }

        $allowed = array('title', 'description', 'license');
        foreach($allowed as $key){
          if(isset($_POST[$key])){
            $entity->$key = $_POST[$key];
          }
        }

        /**
         * Video specific actions
         */
        if($entity->subtype == 'video'){
            if(isset($_POST['file'])){
                $thumb = str_replace('data:image/jpeg;base64,', '', $_POST['file']);
              	$thumb = str_replace(' ', '+', $thumb);
              	$data = base64_decode($thumb);

              	$file = new CoreEntities\File();
              	$file->owner_guid = $entity->owner_guid;
              	$file->setFilename("archive/thumbnails/{$entity->guid}.jpg");
              	$file->open('write');
              	$file->write($data);
              	$file->close();
              	$entity->thumbnail = $_POST['thumbnail'];
            }
            if($activity_post || $_POST['access_token']){
                $activity = new \Minds\Entities\Activity();
                $activity->setFromEntity($entity)
                    ->setCustom('video', array(
                    'thumbnail_src'=>$entity->getIconUrl(),
                    'guid'=>$entity->guid,
                    'mature'=>$entity instanceof \Minds\Interfaces\Flaggable ? $entity->getFlag('mature') : false))
                    ->setTitle($entity->title)
                    ->setBlurb($entity->description)
                    ->save();
            } else {
                //get the attached activity items
                $db = new Core\Data\Call('entities_by_time');
                $e = new Core\Data\Call('entities');
                foreach($db->getRow("activity:entitylink:$entity->guid") as $guid => $ts){
                    $e->insert($guid, ['title' => $_POST['title']]);
                }
            }
        }

        $entity->access_id = !isset($_POST['access_id']) ? 2 : (int) $_POST['access_id'];
        $entity->save(true);

        if($activity_post){
          Core\Events\Dispatcher::trigger('social', 'dispatch', array(
              'services' => array(
                  'facebook' => isset($_POST['facebook']) && $_POST['facebook'] ? $_POST['facebook'] : false,
                  'twitter' => isset($_POST['twitter']) && $_POST['twitter'] ? true : false
              ),
              'data' => array(
                  'message' => $entity->title,
                  'thumbnail_src'=>$entity->getIconUrl(),
                  'perma_url' => $entity->getURL()
              )
          ));

          Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, 1, $entity->guid, 'upload');
          $response['activity_guid'] = $activity->guid;
        }

        $response['guid']=$entity->guid;
        $response['entity']=$entity->export();
        return Factory::response($response);

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
    			//error_log(print_r($_FILES,true));
    			$video = new entities\video();

    			$fp = tmpfile();
    			$metaDatas = stream_get_meta_data($fp);
    			$tmpFilename = $metaDatas['uri'];
    			$req = $this->parsePut();
                            $body = $req['body'];
                fwrite($fp, $body);
                $video->access_id = 0;
    			$video->upload($tmpFilename);
    			$guid = $video->save();
    			 fclose($fp);
    			break;
    		case 'image':
    			$image = new \minds\plugin\archive\entities\image();
    			$image->batch_guid = 0;
    			$image->access_id = 0;
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

        $entity = \Minds\Entities\Factory::build($pages[0]);
        $entity->delete();

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
