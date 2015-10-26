<?php
/**
 * Minds Merchant API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;

class merchant implements Interfaces\Api{

  /**
   * Returns merchant information
   * @param array $pages
   *
   * API:: /v1/merchant/:slug
   */
  public function get($pages){

    $response = array();

    switch($pages[0]){
      case "orders":
        //return a list of orders
        break;
      case "balance":
        break;
      case "settings":
        break;
    }

    return Factory::response($response);

  }

  public function post($pages){
    $response = array();
    return Factory::response($response);
  }

  public function put($pages){
    return Factory::response(array());
  }

  public function delete($pages){
    return Factory::response(array());
  }

}
