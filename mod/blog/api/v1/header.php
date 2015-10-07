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
use minds\interfaces;
use Minds\Api\Factory;

class header implements Interfaces\api, Interfaces\ApiIgnorePam{

    /**
     * Returns the conversations or conversation
     * @param array $pages
     *
     * API:: /v1/blog/:filter
     */
    public function get($pages){

      $blog = new entities\Blog($pages[0]);
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

    }

    public function post($pages){

        return Factory::response(array());

    }

    public function put($pages){

        return Factory::response(array());

    }

    public function delete($pages){

        return Factory::response(array());

    }

}
