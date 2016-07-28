<?php
namespace Minds\Core\Analytics\Metrics;

use Minds\Helpers;
use Minds\Core;
use Minds\Core\Analytics\Timestamps;
use Minds\Interfaces\AnalyticsMetric;

/**
 * Engagement Metric
 */
class Engagement implements AnalyticsMetric
{
    private $db;
    private $namespace = "analytics:active";
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

    /**
     * Sets the current namespace
     * @param string $namesapce
     */
    public function setNamespace($namesapce)
    {
        //$this->namespace = $namespace . ":";
    }

    /**
     * Sets the current key
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Increments metric counter
     * @return bool
     */
    public function increment()
    {
        //not applicable
    }

    /**
     * Return a set of analytics for a timespan
     * @param  int    $span - eg. 3 (will return 3 units, eg 3 day, 3 months)
     * @param  string $unit - eg. day, month, year
     * @param  int    $timestamp (optional) - sets the base to work off
     * @return array
     */
    public function get($span = 3, $unit = 'day', $timestamp = null)
    {
        $month= Timestamps::get(['month'])['month'];
        $timestamps = Timestamps::span($span, 'day');
        $data = [];
        foreach ($timestamps as $ts) {
            $data[] = [
                'timestamp' => $ts,
                'date' => date('d-m-Y', $ts),
                'total' => $this->db->countRow("{$this->namespace}:day:$ts") /  $this->db->countRow("{$this->namespace}:month:$month")
            ];
        }
        return $data;
    }

    /**
     * Returns total metric counter
     * @return int
     */
    public function total()
    {
        return 0;
    }
}
