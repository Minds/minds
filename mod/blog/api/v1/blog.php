<?php
/**
 * Minds Blog API
 *
 * @version 1
 * @author Mark Harding
 */
namespace minds\plugin\blog\api\v1;

use Minds\Core;
use minds\plugin\blog\entities;
use Minds\Entities\User;
use Minds\Interfaces;
use Minds\Api\Factory;

class blog implements Interfaces\Api{

    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/blog/:filter
     */
    public function get($pages){

        $response = array();

        if(!isset($pages[0]))
          $pages[0] = "featured";

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : "";

        switch($pages[0]){
          case "all":
            $entities = core\Entities::get(array(
              'subtype' => 'blog',
              'offset'=> $offset,
              'limit'=> $limit
            ));
            $response['blogs'] = Factory::exportable($entities);
            $response['load-next'] = (string) end($entities)->guid;
            break;
          case "featured":
            $guids = Core\Data\indexes::fetch('object:blog:featured', array('offset'=> $offset, 'limit'=> $limit ));
  				  if(!$guids)
              break;
  				  $entities = core\Entities::get(array('guids'=>$guids));
            usort($entities, function($a, $b){
    					if ((int)$a->featured_id == (int) $b->featured_id) {
    					   return 0;
    					 }
    					return ((int)$a->featured_id < (int)$b->featured_id) ? 1 : -1;
    				});
            $response['blogs'] = Factory::exportable($entities);
            $response['load-next'] = (string) end($entities)->featured_id;
            break;
          case "trending":
            //this is temporary until we bring in neo4j sorting
            $db = new Core\Data\Call('entities_by_time');
            $guids = $db->getRow('trending:month:object:blog', array('offset'=> $offset, 'limit'=> $limit ));
            if(!$guids)
              break;
            $entities = core\Entities::get(array('guids'=>$guids));
            $response['blogs'] = Factory::exportable($entities);
            $response['load-next'] = (string) end($entities)->guid;
            break;
          case "owner":
            if(!is_numeric($pages[1])){
              $lookup = new Core\Data\lookup();
              $pages[1] = $lookup->get($pages[1]);
            }
            $entities = core\Entities::get(array(
              'subtype' => 'blog',
              'owner_guid' => isset($pages[1]) ? $pages[1] : \Minds\Core\Session::getLoggedInUser()->guid,
              'offset'=> $offset,
              'limit'=> $limit
            ));
            $response['blogs'] = Factory::exportable($entities);
            $response['load-next'] = (string) end($entities)->guid;
            break;
          case is_numeric($pages[0]):
            $blog = new entities\Blog($pages[0]);
            $response['blog'] = $blog->export();
            //provide correct subscribe info for userobj (renormalize)
            $owner = new user($blog->ownerObj);
            $response['blog']['ownerObj'] = $owner->export();
            break;
          case "header":
            $blog = new entities\Blog($pages[1]);
            $header = new \ElggFile();
      			$header->owner_guid = $blog->owner_guid;
      			$header->setFilename("blog/{$blog->guid}.jpg");
      			header('Content-Type: image/jpeg');
      			header('Expires: ' . date('r', time() + 864000));
      			header("Pragma: public");
       			header("Cache-Control: public");

      			try{
      				echo file_get_contents($header->getFilenameOnFilestore());
      			}catch(\Exception $e){}
      			exit;
            break;
        }


        return Factory::response($response);
    }

    public function post($pages){

        $response = array();

        if(isset($pages[0]) && is_numeric($pages[0]))
          $blog = new entities\Blog($pages[0]);
        else
          $blog = new entities\Blog();

        $allowed = array('title', 'description', 'access_id', 'status', 'license');

        foreach($allowed as $v){
          if(isset($_POST[$v]))
            $blog->$v = $_POST[$v];
        }

        $blog->save();

        if(is_uploaded_file($_FILES['file']['tmp_name'])){
          $resized = get_resized_image_from_uploaded_file('file', 2000);
          $file = new \ElggFile();
          $file->owner_guid = $blog->owner_guid;
          $file->setFilename("blog/{$blog->guid}.jpg");
          $file->open('write');
          $file->write($resized);
          $file->close();
          $blog->header_bg = true;
          $blog->header_top = $_POST['header_top'] ?: 0;
          $blog->last_updated = time();
          $blog->save();
        }

        $response['guid'] = (string) $blog->guid;

        return Factory::response($response);

    }

    public function put($pages){

        if(isset($pages[0]) && is_numeric($pages[0]))
          $blog = new entities\Blog($pages[0]);
        else
          $blog = new entities\Blog();

        if(is_uploaded_file($_FILES['header']['tmp_name'])){
          $resized = get_resized_image_from_uploaded_file('header', 2000);
          $file = new \ElggFile();
          $file->owner_guid = $blog->owner_guid;
          $file->setFilename("blog/{$blog->guid}.jpg");
          $file->open('write');
          $file->write($resized);
          $file->close();
          $blog->header_bg = true;
          $blog->last_updated = time();
        }

        $blog->save();

        return Factory::response(array());
    }

    public function delete($pages){
      $blog = new entities\Blog($pages[0]);
      $blog->delete();
      return Factory::response(array());
    }

}
