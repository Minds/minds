<?php

global $CONFIG;

date_default_timezone_set('UTC');

$minds = new Minds\Core\Minds();
$minds->loadLegacy();

$CONFIG = Minds\Core\Config::_();
$CONFIG->default_access = 2;
$CONFIG->site_guid = 0;
$CONFIG->cassandra = new stdClass;
$CONFIG->cassandra->keyspace = 'phpspec';
$CONFIG->cassandra->servers = array('127.0.0.1');
$CONFIG->cassandra->cql_servers = array('127.0.0.1:9042');

$CONFIG->payments = [
  'braintree' => [
    'environment' => 'sandbox',
    'merchant_id' => 'foobar',
    'master_merchant_id' => 'foobar',
    'public_key' => 'random',
    'private_key' => 'random_private'
  ]];
