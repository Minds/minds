<?php
/**
 * Minds dataroot Provider
 */

namespace Minds\Core\Data;

use Minds\Core\Di\Provider;

class DataProvider extends Provider
{

    public function register()
    {
        /**
         * Cache bindings
         */
        $this->di->bind('Cache', function($di){
            return $di->get('Cache\Redis');
        }, ['useFactory'=>true]);
        $this->di->bind('Cache\Redis', function($di){
            return new Cache\Redis();
        }, ['useFactory'=>true]);
        $this->di->bind('Cache\Apcu', function($di){
            return new Cache\Apcu();
        }, ['useFactory'=>true]);
        /**
         * Database bindings
         */
        $this->di->bind('Database', function($di){
           return $di->get('Database\Cassandra');
        }, ['useFactory'=>true]);
        $this->di->bind('Database\Cassandra', function($di){
            return new Data\Call();
        }, ['useFactory'=>true]);
        $this->di->bind('Database\MongoDB', function($di){
            return new Data\MongoDB\Client();
        }, ['useFactory'=>true]);
        $this->di->bind('Database\Neo4j', function($di){
            return new Data\Neo4j\Client();
        }, ['useFactory'=>true]);
        $this->di->bind('Database\ElasticSearch', function($di){
            return new Data\ElasticSearch\Client();
        }, ['useFactory'=>true]);
    }

}
