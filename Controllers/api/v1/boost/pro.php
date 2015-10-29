<?php
/**
 * Minds Boost Api endpoint
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1\boost;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class pro implements Interfaces\Api{

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
          return Factory::response([
            'status' => 'error',
            'message' => 'We couldn\'t find the entity you wanted boost. Please try again.'
          ]);
        }

        if(!$destination){
          return Factory::response([
            'status' => 'error',
            'message' => 'We couldn\'t find the user you wish to boost to. Please try another user.'
          ]);
        }

        if(!$destination->merchant){
          return Factory::response([
            'status' => 'error',
            'message' => "@$destination->username is not a merchant and can not accept Pro Boosts"
          ]);
        }

        $boost = (new Entities\Boost\Pro())
          ->setEntity($entity)
          ->setBid($_POST['bid'])
          ->setDestination($destination)
          ->setOwner(Core\Session::getLoggedInUser())
          ->setState('created');
          //->save();

        $sale = (new Payments\Sale)
          ->setAmount($boost->getBid())
          ->setMerchant($boost->getDestination())
          ->setCustomerId($boost->getOwner()->guid)
          ->setNonce($_POST['nonce']);

        try{
          $transaction_id = Payments\Factory::build('braintree')->setSale($sale);
        } catch(\Exception $e){
          return Factory::response([
            'status' => 'error',
            'message' => $e->getMessage()
          ]);
        }

        $boost->setTransactionId($transaction_id)
          ->save();

        $response['boost_guid'] = $boost->getGuid();

        return Factory::response($response);

    }

    /**
     * @param array $pages
     */
    public function put($pages){

    }

    /**
     */
    public function delete($pages){

    }

}
