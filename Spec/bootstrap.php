<?php

ini_set('memory_limit', '256M');

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
$CONFIG->cassandra->username = 'cassandra';
$CONFIG->cassandra->password = 'cassandra';

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

    public function withCredentials($username, $password)
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

    public static function bigint()
    {

    }

    public static function timestamp()
    {

    }

    public function uuid()
    {
        return (string) $this->a;
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

    public function toFloat()
    {
        return $this->a;
    }

    public function value()
    {
        return (string) $this->a;
    }

    public function values()
    {
        return (array) $this->a;
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

    public static function set(...$args)
    {
        return new Mock(...$args);
    }

    public function add()
    {

    }
}

class MockMap
{
    private $keyType;
    private $valueType;
    private $kv;

    public function __construct($keyType, $valueType)
    {
        $this->keyType = $keyType;
        $this->valueType = $valueType;
    }

    public function set($key, $value)
    {
        $this->kv[(string) $key] = new Mock($value);
        return $this;
    }

    public function values()
    {
        return ($this->kv);
    }
}

class MockCollectionValues implements ArrayAccess
{
    private $values;

    public function __construct($values)
    {
        $this->values = $values;
    }

    public function __get($key)
    {
        $key = md5($key);
        return $this->values[$key];
    }

    public function offsetExists($offset)
    {
        $key = md5($offset);
        return isset($this->values[$key]);
    }

    public function offsetGet($offset)
    {
        $key = md5($offset);
        return $this->values[$key];
    }

    public function offsetSet($offset, $value)
    {

    }
    
    public function offsetUnset($offset)
    {

    }

}

class MockSet
{
    private $valueType;
    private $values = [];

    public function __construct($valueType)
    {
        $this->valueType = $valueType;
    }

    public function add($value)
    {
        $this->values[] = new Mock($value);
        return $this;
    }

    public function values()
    {
        return array_values($this->values);
    }
}

if (!class_exists('Cassandra')) {
    class_alias('Mock', 'Cassandra');
    class_alias('Mock', 'Cassandra\ExecutionOptions');
    class_alias('Mock', 'Cassandra\Varint');
    class_alias('Mock', 'Cassandra\Timestamp');
    class_alias('Mock', 'Cassandra\Type');
    class_alias('MockSet', 'Cassandra\Type\Set');
    class_alias('MockMap', 'Cassandra\Type\Map');
    class_alias('Mock', 'Cassandra\Decimal');
    class_alias('Mock', 'Cassandra\Bigint');
    class_alias('Mock', 'Cassandra\Float_');
    class_alias('Mock', 'Cassandra\Tinyint');
    class_alias('MockSet', 'Cassandra\Set');
    class_alias('MockMap', 'Cassandra\Map');
    class_alias('Mock', 'Cassandra\Uuid');
    class_alias('Mock', 'Cassandra\Timeuuid');
    class_alias('Mock', 'Cassandra\Boolean');
    class_alias('Mock', 'MongoDB\BSON\UTCDateTime');
    class_alias('Mock', 'Cassandra\RetryPolicy\Logging');
    class_alias('Mock', 'Cassandra\RetryPolicy\DowngradingConsistency');
}

Minds\Core\Di\Di::_()->bind('Database\Cassandra\Cql', function($di) {
    return new Mock;
});
