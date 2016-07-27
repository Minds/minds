<?php
namespace Minds\Helpers;

use Minds\Core;
use Minds\Core\Data;

/**
 * Helper for request metric counters
 * @todo Avoid static and use proper DI (check $client at methods)
 * @todo Perhaps this can inherit methods from Helpers\Analytics
 */
class RequestMetrics
{
    private static $namespace = "requestmetrics";

    /**
     * Increments a request counter metric
     * @param  $metric
     * @return void
     */
    public static function increment($metric = "all")
    {
        $ts = self::buildTS();
        Counters::increment($ts, $metric);
    }

    /**
     * Gets a request metric counter
     * @param  string $metric
     * @param  int    $ts
     * @return int
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
     * Gets a 5-minute rounded timestamp
     * @param  int|null $ts        - timestamp. Null for current time.
     * @return int
     */
    public static function buildTS($ts = null)
    {
        if (!$ts) {
            $ts = time();
        }
        return ceil($ts/300)*300;
    }
}
