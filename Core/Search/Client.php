<?php
/**
 * Search events listeners
 */
namespace Minds\Core\Search;

use Minds\Core\Config;
use Elasticsearch\ClientBuilder;

class Client
{
    
    private static $client;

    public static function build()
    {
        if (!self::$client) {
            $hosts = Config::_()->elasticsearch_server ?: 'localhost';

            if (!is_array($hosts)) {
                $hosts = [ $hosts ];
            }
        
            self::$client = ClientBuilder::create()
               ->setHosts($hosts)
               ->build();
        }
        return self::$client;
    }
}
