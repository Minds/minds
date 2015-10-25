<?php

global $CONFIG;

date_default_timezone_set('UTC');

$minds = new Minds\Core\Minds();
$minds->loadLegacy();

$CONFIG = Minds\Core\Config::build();
Minds\Core\Config::build()->default_access = 2;
Minds\Core\Config::build()->site_guid = 0;
Minds\Core\Config::build()->cassandra = new stdClass;
Minds\Core\Config::build()->cassandra->keyspace = 'phpspec';
Minds\Core\Config::build()->cassandra->servers = array('127.0.0.1');
Minds\Core\Config::build()->cassandra->cql_servers = array('127.0.0.1:9042');
