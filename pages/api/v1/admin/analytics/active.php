<?php
/**
 * Minds Admin: Analytics : Active
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace minds\pages\api\v1\admin\analytics;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use DateTime;

class active implements Interfaces\Api, Interfaces\ApiIgnorePam{

    /**
     * Return analytics data
     * @param array $pages
     */
    public function get($pages){
      $response = array();

      $db = new Core\Data\Call('entities_by_time');

      $mam = array(
        "month" => Helpers\Analytics::get("active", "month"),
        "last-month" => Helpers\Analytics::get("active", "month", Helpers\Analytics::buildTS("last-month"))
      );

      /**
       * Return daily active users
       */
      $dam = array();

      $time = new DateTime('midnight last day of last month');
      $day_of_month = 0;
      while($time->getTimestamp() < strtotime('midnight')){
        $day_of_month++;
        $timestamp = $time->modify("+1 days")->getTimestamp();
        $dam[$day_of_month] = Helpers\Analytics::get("active", "day", $timestamp);
      }

      $response['monthly'] = $mam;
      $response['daily'] = $dam;

      return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function post($pages){
      return Factory::response(array());
    }

    /**
     * @param array $pages
     */
    public function put($pages){
	    return Factory::response(array());
    }

    /**
     * @param array $pages
     */
    public function delete($pages){
      return Factory::response(array());
    }

}
