<?php

global $CONFIG;

date_default_timezone_set('UTC');

$minds = new Minds\Core\Minds();
$minds->loadLegacy();

$CONFIG = Minds\Core\Di\Di::_()->get('Config');
$CONFIG->default_access = 2;
$CONFIG->site_guid = 0;
$CONFIG->cassandra = new stdClass;
$CONFIG->cassandra->keyspace = 'phpspec';
$CONFIG->cassandra->servers = ['127.0.0.1'];
$CONFIG->cassandra->cql_servers = ['127.0.0.1'];

$CONFIG->payments = [
  'braintree' => [
    'default' => [
      'environment' => 'sandbox',
      'merchant_id' => 'foobar',
      'master_merchant_id' => 'foobar',
      'public_key' => 'random',
      'private_key' => 'random_private'
    ],
    'merchants' => [
      'environment' => 'sandbox',
      'merchant_id' => 'foobar',
      'master_merchant_id' => 'foobar',
      'public_key' => 'random',
      'private_key' => 'random_private'
    ],
  ]];

class Mock 
{

    private $a;

    const BATCH_COUNTER = null;
    const BATCH_UNLOGGED = 1;
    const CONSISTENCY_ALL = 1;
    const CONSISTENCY_QUORUM = 2;

    public function __construct($a = null)
    {
        $this->a = $a;
    }

    public function withLatencyAwareRouting()
    {
        return $this;
    }

    public function withDefaultConsistency()
    {
        return $this;
    }

    public function withRetryPolicy()
    {
        return $this;
    }

    public static function collection()
    {
        return new Mock();
    }


    public function request()
    {

    }

    public function create()
    {
      
    }

    public function withContactPoints()
    {
        return $this;
    }

    public function withPort()
    {
        return $this;
    }

    public static function text()
    {
      
    }

    public static function varint()
    {

    }

    public function time()
    {
        return (int) $this->a;
    }

    public function toInt()
    {
        return (int) $this->a;
    }

    public function toDouble()
    {
        return (double) $this->a;
    }

    public function value()
    {
        return (string) $this->a;
    }

    public function __toString()
    {
        return (string) $this->a;
    }

    public static function cluster()
    {
        return new Mock();
    }

    public static function build()
    {
        return new Mock();
    }

    public static function connect()
    {
        return new Mock();
    }

    public static function prepare()
    {
        return new Mock();
    }

    public static function executeAsync()
    {
        return new Mock();
    }

    public static function get()
    {

    }

    public static function boolean()
    {

    }

    public static function set()
    {

    }

    public function add()
    {

    }
}

class_alias('Mock', 'Cassandra');
class_alias('Mock', 'Cassandra\ExecutionOptions');
class_alias('Mock', 'Cassandra\Varint');
class_alias('Mock', 'Cassandra\Timestamp');
class_alias('Mock', 'Cassandra\Type');
class_alias('Mock', 'Cassandra\Type\Set');
class_alias('Mock', 'Cassandra\Type\Map');
class_alias('Mock', 'Cassandra\Decimal');
class_alias('Mock', 'Cassandra\Bigint');
class_alias('Mock', 'Cassandra\Tinyint');
class_alias('Mock', 'Cassandra\Set');
class_alias('Mock', 'Cassandra\Map');
class_alias('Mock', 'MongoDB\BSON\UTCDateTime');
class_alias('Mock', 'Cassandra\RetryPolicy\Logging');
class_alias('Mock', 'Cassandra\RetryPolicy\DowngradingConsistency');

Minds\Core\Di\Di::_()->bind('Database\Cassandra\Cql', function($di) {
    return new Mock;
});
