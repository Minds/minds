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
$CONFIG->cassandra->servers = array('127.0.0.1');
$CONFIG->cassandra->cql_servers = array('127.0.0.1:9042');
