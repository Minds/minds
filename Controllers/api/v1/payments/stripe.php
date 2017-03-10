<?php
/**
 * Minds Payments API:: stripe
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\payments;

use Minds\Core;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments;

class stripe implements Interfaces\Api
{
    /**
   * Returns merchant information
   * @param array $pages
   *
   * API:: /v1/merchant/:slug
   */
  public function get($pages)
  {
      $response = array();

      switch ($pages[0]) {
        case "token":
          $response['token'] = Core\Config::_()->get('payments')['stripe']['public_key']
          break;
      }

      return Factory::response($response);
  }

    public function post($pages)
    {
        $response = [];

        return Factory::response($response);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        return Factory::response(array());
    }
}
