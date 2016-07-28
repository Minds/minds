<?php
namespace Minds\Core\Analytics;

use \DateTime;

/**
 * Analytics timestamps helper
 */
class Timestamps
{
    /**
     * Return a rooted timestamp for the relevant period
     * @param  array $period (eg. day, month, year)
     * @param  int   $ts (optional) - the timestamp to base off
     * @return array - A key value array of period => timestamp
     */
    public static function get($periods, $ts = null)
    {
        if (!$ts) {
            $ts = time();
        }

        $time = array();
        foreach ($periods as $period) {
            switch ($period) {
                case "day":
                $ref =  "midnight";
                break;
                case "month":
                $ref = "midnight first day of this month";
                break;
                case "year":
                $ref = "year";
                break;
            }

            $time[$period] = strtotime($ref, $ts);
        }

        return $time;
    }

    /**
     * Return multiple timestamps for a period of a  givien unit, eg. 3 days, 6 months
     * @param  int    $span
     * @param  string $unit - eg. day
     * @param  int    $timestamp (optional)
     * @return array
     */
    public static function span($span, $unit, $ts =  null)
    {
        $op = "+";
        if ($span < 0) {
            $op = "-";
        }

        switch ($unit) {
            case "day":
            $time = (new DateTime('midnight'))->modify("-$span days");
            break;
            case "month":
            $time = (new DateTime('midnight first day of this month'))->modify("-$span months");
            break;
            default:
            throw new \Exception("$unit is not an accepted unit");
        }

        $clone = clone $time;
        $max = $clone->modify("+$span {$unit}s")->getTimestamp();

        $timestamps = array();
        while ($time->getTimestamp() < $max) {
            $timestamps[] = $time->modify("+1 {$unit}s")->getTimestamp();
        }
        return $timestamps;
    }
}
