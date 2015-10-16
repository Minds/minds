<?php
/**
 * Minds Subscriptions
 *
 * @version 1
 * @author Mark Harding
 */
namespace minds\pages\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class block implements Interfaces\Api, Interfaces\ApiIgnorePam{

    /**
     * Return a list of your blocked users
     */
    public function get($pages){

      $limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
      $offset = isset($_GET['offset']) ? $_GET['offset'] : "";

      $block = Core\Security\ACL\Block::_();
      $guids = $block->getBlockList(Core\Session::getLoggedinUser(), $limit, $offset);

      if($guids){
        $entities = Core\Entities::get(array('guids'=>$guids));
        $response['entities'] = Api\Factory::exportable($entities);
      }

      return Factory::response(array('status'=>'error', 'message'=>'GET is not supported for this endpoint'));
    }

    /**
     *
     */
    public function post($pages){
      return Factory::response(array());
    }

    /**
     * Block a user
     */
    public function put($pages){
      $block = Core\Security\ACL\Block::_();
      $block->block($pages[0]);

      return Factory::response(array());
    }

    /**
     * UnBlock a user
     */
    public function delete($pages){
      $block = Core\Security\ACL\Block::_();
      $block->unBlock($pages[0]);

      return Factory::response(array());
    }

}
