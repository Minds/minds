<?php
namespace Minds\Helpers;

use Minds\Core;
use Minds\Core\Data;

/**
 * Helper to provide request metrics
 * @todo Avoid static and use proper DI
 */
class Analytics
{
    /**
     * Get MAU or DAU
     * @param  string $metric
     * @param  string $reference - eg. daily, monthly
     * @param  int    $ts        - timestamp
     * @return int
     */
    public static function get($metric = "active", $reference = "day", $ts = null)
    {
        $db = new Core\Data\Call('entities_by_time');
        $ts = self::buildTS($reference, $ts);
        return $db->countRow("analytics:$metric:$reference:$ts");
    }

    /**
     * Increments a metric
     * @param  string     $metric
     * @param  int        $ts        - timestamp
     * @param  mixed|null $user_guid - acting user. Null for current.
     * @return null
     */
    public static function increment($metric = "active", $ts = null, $user_guid = null)
    {
        if (!$user_guid) {
            $user_guid = Core\Session::getLoggedinUser()->guid;
        }
        $db = new Core\Data\Call('entities_by_time');
        $ts = self::buildTS("day", $ts);
        $db->insert("analytics:$metric:day:$ts", [$user_guid => time()]);
        $ts = self::buildTS("month", $ts);
        $db->insert("analytics:$metric:month:$ts", [$user_guid => time()]);
    }

    /**
     * Gets a timestamp based on a string, and optionally on the
     * passed timestamp.
     * @param  string   $reference
     * @param  int|null $ts        - timestamp. Null for current time.
     * @return int
     */
    public static function buildTS($reference = "day", $ts = null)
    {
        date_default_timezone_set('UTC');
        if (!$ts) {
            $ts = time();
        }
        switch ($reference) {
          case "hour":
            return $ts - ($ts % 3600);
            break;
          case "day":
            $reference = "midnight";
            break;
          case "yesterday":
            $reference = "yesterday";
            break;
          case "month":
            $reference = "midnight first day of this month";
            break;
          case "last-month":
            $reference = "midnight first day of last month";
            break;
        }
        return strtotime($reference, $ts);
    }
}
