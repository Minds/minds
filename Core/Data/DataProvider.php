<?php
/**
 * Minds dataroot Provider
 */

namespace Minds\Core\Data;

use Minds\Core\Data\Locks;
use Minds\Core\Di\Provider;
use PDO;

class DataProvider extends Provider
{
    public function register()
    {
        /**
         * Cache bindings
         */
        $this->di->bind('Cache', function ($di) {
            return cache\factory::build('Redis');
        }, ['useFactory'=>true]);
        $this->di->bind('Cache\Redis', function ($di) {
            return new cache\Redis();
        }, ['useFactory'=>true]);
        $this->di->bind('Cache\Apcu', function ($di) {
            return new cache\apcu();
        }, ['useFactory'=>true]);
        /**
         * Database bindings
         */
        $this->di->bind('Database', function ($di) {
            return $di->get('Database\Cassandra');
        }, ['useFactory'=>true]);
        $this->di->bind('Database\Cassandra', function ($di) {
            return new Call();
        }, ['useFactory'=>true]);
        $this->di->bind('Database\Cassandra\Cql', function ($di) {
            return new Cassandra\Client();
        }, ['useFactory'=>true]);
        $this->di->bind('Database\Cassandra\Entities', function ($di) {
            return new Call('entities');
        }, ['useFactory'=>false]);
        $this->di->bind('Database\Cassandra\UserIndexes', function ($di) {
            return new Call('user_index_to_guid');
        }, ['useFactory'=>false]);
        $this->di->bind('Database\Cassandra\Indexes', function ($di) {
            return new Cassandra\Thrift\Indexes(new Call('entities_by_time'));
        }, ['useFactory'=>false]);
        $this->di->bind('Database\Cassandra\Lookup', function ($di) {
            return new Cassandra\Thrift\Lookup(new Call('user_index_to_guid'));
        }, ['useFactory'=>false]);
        $this->di->bind('Database\Cassandra\Data\Lookup', function ($di) {
            return new lookup();
        }, ['useFactory'=>false]);
        $this->di->bind('Database\Cassandra\Relationships', function ($di) {
            return new Cassandra\Thrift\Relationships(new Call('relationships'));
        }, ['useFactory'=>false]);
        $this->di->bind('Database\MongoDB', function ($di) {
            return new MongoDB\Client();
        }, ['useFactory'=>true]);
        $this->di->bind('Database\Neo4j', function ($di) {
            return new Neo4j\Client();
        }, ['useFactory'=>true]);
        $this->di->bind('Database\ElasticSearch', function ($di) {
            return new ElasticSearch\Client();
        }, ['useFactory'=>true]);
        $this->di->bind('Database\PDO', function ($di) {
            $config = $di->get('Config')->get('database');
            $host = isset($config['host']) ? $config['host'] : 'cockroachdb';
            $port = isset($config['port']) ? $config['port'] : 26257;
            $name = isset($config['name']) ? $config['name'] : 'minds';
            $sslmode = isset($config['sslmode']) ? $config['sslmode'] : 'disable';
            $username = isset($config['username']) ? $config['username'] : 'php';
            return new PDO("pgsql:host=$host;port=$port;dbname=$name;sslmode=$sslmode",
                $username,
                null, 
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => true,
                    PDO::ATTR_PERSISTENT => isset($config['persistent']) ? $config['persistent'] : false,
                ]);
        }, ['useFactory'=>true]);
        /**
         * Locks
         */
        $this->di->bind('Database\Locks\Cassandra', function ($di) {
            return new Locks\Cassandra();
        }, ['useFactory' => false]);
        $this->di->bind('Database\Locks\Redis', function ($di) {
            return new Locks\Redis();
        }, ['useFactory' => false]);
        $this->di->bind('Database\Locks', function ($di) {
            return $di->get('Database\Locks\Redis');
        }, ['useFactory' => false]);
        /**
         * PubSub bindings
         */
        $this->di->bind('PubSub\Redis', function ($di) {
            return new PubSub\Redis\Client();
        }, ['useFactory'=>true]);
        /**
         * Prepared statements
         */
        $this->di->bind('Prepared\MonetizationLedger', function ($di) {
            return new Cassandra\Prepared\MonetizationLedger();
        });
    }
}
