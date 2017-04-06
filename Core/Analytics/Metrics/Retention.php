<?php
namespace Minds\Core\Analytics\Metrics;

use Minds\Helpers;
use Minds\Core;
use Minds\Core\Analytics\Timestamps;
use Minds\Interfaces\AnalyticsMetric;

/**
 * Retention Metric
 */
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
        $now = Timestamps::span(2, 'day')[0];
        $intervals = [1,3,7,28];
        $timestamps = array_reverse(Timestamps::span(30, 'day'));

        foreach ($intervals as $x) {
            //grab signups from $x days ago
            $startTs = $timestamps[$x+1];
            $signups = [];
            $offset = "";
            echo "\n Gathering signups \n";
            while (true) {
                $data = $this->db->getRow("analytics:signup:day:$startTs", ['limit'=>200, 'offset' => $offset]);
                if(count($data) <= 1)
                    break;
                foreach ($data as $k => $v) {
                    echo "\r $k";
                    $signups[$k] = $v;
                    $offset = $k;
                }
            }
            echo " (done)";

            //now get active users from each interval after this date
            $endTs =  $timestamps[$x-$x+1];
            //echo "[$x]:: actives: " . date('d-m-Y', $endTs) . " signups: " . date('d-m-Y', $startTs) . "\n";
            $offset = "";
            echo "\n Gathering actives \n";
            while (true) {
                $actives = $this->db->getRow("analytics:active:day:$endTs", ['limit'=>200, 'offset' => $offset]);
                if(count($actives) <= 1)
                    break;

                foreach ($signups as $signup => $ts) {
                    if (isset($actives[$signup])) {
                        $this->db->insert("{$this->namespace}:$x:$now", [$signup=>time()]);
                        echo "\r $x: $signup (active) $offset"; 
                    } else {
                        echo "\r $x: $signup (not active) $offset";
                    }
                }
                end($actives);
                $offset = key($actives);
            }
            echo "(done)";

        }

        return true;
    }

    /**
     * Return a set of analytics for a timespan
     * @param  int    $span - eg. 3 (will return 3 units, eg 3 day, 3 months)
     * @param  string $unit - eg. day, month, year
     * @param  int    $timestamp (optional) - sets the base to work off
     * @return array
     */
    public function get($span = 1, $unit = 'day', $timestamp = null)
    {
        $intervals = [1,3,7,28];
        $spans = Timestamps::span($span+29, $unit);
        $timestamps = array_reverse(Timestamps::span(30, $unit));
        $data = [];
        foreach ($spans as $i => $ts) {
            if($i < 28)
                continue;
            $totals = [];
            $total = 0;
            $retained = [];
            $signups = [];
            foreach ($intervals as $x) {
                $retained[$x] = (int) $this->db->countRow("{$this->namespace}:$x:$ts");
                $signups[$x] = (int) $this->db->countRow("analytics:signup:day:{$spans[$i - $x]}");
                $totals[] = [
                    'day' => $x,
                    'span' => $i,
                    'signupDate' => date('d-m-Y', $spans[$i - $x]),
                    'retainedDate' => date('d-m-Y', $ts),
                    'retained' => (int) $retained[$x],
                    'signups' => (int) $signups[$x],
                    'total' => (int) $retained[$x] / ($signups[$x] ?: 1)
                ];
                $total += (int) $retained[$x] / ($signups[$x] ?: 1);
            }
            $data[] = [
                'timestamp' => $ts,
                'date' => date('d-m-Y', $ts),
                'total' => $total / count($totals),
                'totals' => $totals
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
