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
            return new Call();
        }, ['useFactory'=>true]);
        $this->di->bind('Database\Cassandra\Entities', function($di){
            return new Cassandra\Thrift\Entities(new Call('entities'));
        }, ['useFactory'=>true]); 
        $this->di->bind('Database\Cassandra\Indexes', function($di){
            return new Cassandra\Thrift\Indexes(new Call('entities_by_time'));
        }, ['useFactory'=>true]);
        $this->di->bind('Database\Cassandra\Lookup', function($di){
            return new Cassandra\Thrift\Lookup(new Call('user_index_to_guid'));
        }, ['useFactory'=>true]);
        $this->di->bind('Database\Cassandra\Relationships', function($di){
            return new Cassandra\Thrift\Relationships(new Call('relationships'));
        }, ['useFactory'=>true]);
        $this->di->bind('Database\MongoDB', function($di){
            return new MongoDB\Client();
        }, ['useFactory'=>true]);
        $this->di->bind('Database\Neo4j', function($di){
            return new Neo4j\Client();
        }, ['useFactory'=>true]);
        $this->di->bind('Database\ElasticSearch', function($di){
            return new ElasticSearch\Client();
        }, ['useFactory'=>true]);
    }

}
