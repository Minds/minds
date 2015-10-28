<?php
/**
 * Minds Boost Api endpoint
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class boost implements Interfaces\Api{

    private $rate = 1;

    /**
     * Return a list of boosts that a user needs to review
     * @param array $pages
     */
    public function get($pages){
      $response = array();

      switch($pages[0]){

      }

      return Factory::response($response);
    }

    /**
     * Boost an entity
     * @param array $pages
     *
     * API:: /v1/boost/:type/:guid
     */
    public function post($pages){

        $entity = Entities\Factory::build($pages[0]);
        $destination = Entities\Factory::build($_POST['destination']);
        $bid = $_POST['bid'];

        if(!$entity){
          return Factory::response(array(
            'status' => 'error',
            'message' => 'We couldn\'t find the entity you wanted boost. Please try again.'
          ));
        }

        if(!$destination){
          return Factory::response(array(
            'status' => 'error',
            'message' => 'We couldn\'t find the user you wish to boost to. Please try another user.'
          ));
        }

        if(!$destination->merchant){
          return Factory::response(array(
            'status' => 'error',
            'message' => "@$destination->username is not a merchant and can not accept Pro Boosts"
          ));
        }

        //$sale = (new Payments\Sale)
        //  ->  

        return Factory::response($response);

    }

    /**
     * Called when a boost is to be accepted (assume channels only right now
     * @param array $pages
     */
    public function put($pages){
	    //validate the points
    	$ctrl = Core\Boost\Factory::build('Channel', array('destination'=>Core\Session::getLoggedinUser()->guid));
	    $guids = $ctrl->getReviewQueue(1, $pages[0]);
	    if(!$guids){
            return Factory::response(array('status'=>'error', 'message'=>'entity not in boost queue'));
        }
	    $points = reset($guids);
        Helpers\Wallet::createTransaction(Core\Session::getLoggedinUser()->guid, $points, $pages[0], "boost (remind)");
	    $accept = $ctrl->accept($pages[0], $points);
	    return Factory::response(array());
    }

    /**
     * Called when a boost is rejected (assume channels only right now)
     */
    public function delete($pages){
	    $ctrl = Core\Boost\Factory::build('Channel', array('destination'=>Core\Session::getLoggedinUser()->guid));
        $guids = $ctrl->getReviewQueue(1, $pages[0]);
        if(!$guids){
            return Factory::response(array('status'=>'error', 'message'=>'entity not in boost queue'));
        }
        $points = reset($guids);
        $entity = new \Minds\Entities\Activity($pages[0]);
        Helpers\Wallet::createTransaction($entity->owner_guid, $points, $pages[0], "boost refund");
    	$ctrl->reject($pages[0]);
    }

}
