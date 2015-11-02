<?php
/**
 * Pool Factory
 *
 */

namespace Minds\Core\Data;

use phpcassa\Connection\ConnectionPool;
use Minds\Core;
use Minds\Core\config;

class Pool
{
    public static $pools = array();

    public static function build($keyspace, $servers = array('localhost'), $poolsize, $retries = 1, $sendTimeout = 200, $receiveTimeout = 800)
    {
        return  new ConnectionPool($keyspace, $servers, $poolsize, 2, $sendTimeout, $receiveTimeout);

        if (!isset(self::$pools[$keyspace])) {
            self::$pools[$keyspace] = new ConnectionPool($keyspace, $servers, $poolsize, 2, $sendTimeout, $receiveTimeout);
        }

        return self::$pools[$keyspace];
    }
}
