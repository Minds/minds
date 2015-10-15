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

class active implements Interfaces\Api, Interfaces\ApiAdminPam{

    /**
     * Return analytics data
     * @param array $pages
     */
    public function get($pages){
      $response = array();

      $db = new Core\Data\Call('entities_by_time');

      $mam = array();
      $time = (new DateTime('midnight first day of this month'))->modify("-6 months");
      while($time->getTimestamp() < strtotime('midnight first day of this month')){
        $timestamp = $time->modify("+1 month")->getTimestamp();
        $mam[] = array(
          'timestamp' => $timestamp,
          'date' => date('m-Y', $timestamp),
          'total' => Helpers\Analytics::get("active", "month", $timestamp)
        );
      }

      /**
       * Return daily active users
       */
      $dam = array();

      $time = new DateTime('midnight last day of last month');
      while($time->getTimestamp() < strtotime('midnight')){
        $timestamp = $time->modify("+1 days")->getTimestamp();
        $dam[] = array(
          'timestamp' => $timestamp,
          'date' => date('d-m-Y', $timestamp),
          'total' => Helpers\Analytics::get("active", "day", $timestamp)
        );
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
