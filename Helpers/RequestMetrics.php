<?php
namespace Minds\Helpers;

use Minds\Core;
use Minds\Core\Data;

/**
 * A helper class to provide request metrics
 */
class RequestMetrics
{
    private static $namespace = "requestmetrics";
    /**
     * Increment
     * @param $metric
     * @return void
     */
    public static function increment($metric = "all")
    {
        $ts = self::buildTS();
        Counters::increment($ts, $metric);
    }

    /**
     * Get request metrics
     * @param $metric
     * @param int $ts
     * @return int - the count
     */
    public static function get($metric = "all", $ts = null)
    {
        $client = Core\Data\Client::build('Cassandra');
        $query = new Core\Data\Cassandra\Prepared\Counters();
        try {
            $result = $client->request($query->get(self::buildTS($ts), $metric));
            if (isset($result[0]) && isset($result[0]['count'])) {
                $count = $result[0]['count'];
            } else {
                $count =  0;
            }
        } catch (\Exception $e) {
            var_dump($e);
            exit;
            return 0;
        }
        return $count;
    }

    /**
     * Get timestamp to nearest 5 minutes
     */
    public static function buildTS($ts = null)
    {
        if (!$ts) {
            $ts = time();
        }
        return ceil($ts/300)*300;
    }
}
