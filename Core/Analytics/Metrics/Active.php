<?php
/**
 * Active Metric
 */
namespace Minds\Core\Analytics\Metrics;

use Minds\Helpers;
use Minds\Core;
use Minds\Core\Analytics\Timestamps;
use Minds\Interfaces\AnalyticsMetric;

class Active implements AnalyticsMetric
{
    private $db;
    private $namespace = "analytics:";
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
        foreach (Timestamps::get(array('day', 'month')) as $p => $ts) {
            $this->db->insert("{$this->namespace}active:$p:$ts", array($this->key => $time()));
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
  public function get($span = 3, $unit = 'day', $timestamp = null)
  {
      $timestamps = Timestamps::span($span, $unit);
      $data = array();
      foreach ($timestamps as $ts) {
          $data[] = array(
        'timestamp' => $ts,
        'date' => date('d-m-Y', $ts),
        'total' => $this->db->countRow("{$this->namespace}active:$unit:$ts")
      );
      }
      return $data;
  }

    public function total()
    {
        return 0;
    }
}
