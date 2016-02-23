<?php
/**
 * Retention Metric
 */
namespace Minds\Core\Analytics\Metrics;

use Minds\Helpers;
use Minds\Core;
use Minds\Core\Analytics\Timestamps;
use Minds\Interfaces\AnalyticsMetric;

class Retention implements AnalyticsMetric
{
    private $db;
    private $namespace = "analytics:retention";
    private $key;

    public function __construct($db = null)
    {
        if ($db) {
            $this->db = $db;
        } else {
            $this->db = new Core\Data\Call('entities_by_time');
        }

        if (Core\Session::getLoggedinUser()) {
            $this->key = Core\Session::getLoggedinUser()->guid;
        }
    }

    public function setNamespace($namesapce)
    {
        //$this->namespace = $namespace . ":";
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function increment()
    {

        $now = Timestamps::get(['day'])['day'];
        $intervals = [1,3,7,28];
        $timestamps = Timestamps::span(28, 'day');

        foreach($intervals as $x){

            //grab signups from $x days ago
            $startTs = $timestamps[$x-1];
            $signups = $this->db->getRow("analytics:signup:day:$startTs", ['limit'=>10000]);

            //now get active users from each interval after this date
            $endTs =  $timestamps[$x-$x];
            $actives = $this->db->getRow("analytics:active:day:$endTs", ['limit'=>10000]);

            $retained = [];
            foreach($signups as $signup => $ts){
                if(isset($actives[$signup])){
                    $retained[$signup] = time();
                }
            }

            $this->db->insert("{$this->namespace}:$x:$now", $retained);

        }

        return true;
    }

  /**
  * Return a set of analytics for a timespan
  * @param int $span - eg. 3 (will return 3 units, eg 3 day, 3 months)
  * @param string $unit - eg. day, month, year
  * @param int $timestamp (optional) - sets the base to work off
  * @return array
  */
  public function get($span = 1, $unit = 'day', $timestamp = null)
  {
      $intervals = [1,3,7,28];
      $timestamps = Timestamps::span(28, $unit);
      $spans = Timestamps::span($span, $unit);
      $data = [];
      foreach ($spans as $ts) {
          $totals = [];
          $retained = [];
          $signups = [];
          foreach($intervals as $x){
              $retained[$x] = $this->db->countRow("{$this->namespace}:$x:$ts");
              $signups[$x] = $this->db->countRow("analytics:signups:day:" . $timestamps[$x-1]);
              $totals[$x] = $retained[$x] / $signups[$x];
          }
          $data[] = [
            'timestamp' => $ts,
            'date' => date('d-m-Y', $ts),
            'total' => (array_sum($totals) / count($totals)),
            'totals' => $totals
          ];
      }
      return $data;
  }

    public function total()
    {
        return 0;
    }
}
