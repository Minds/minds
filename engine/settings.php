<?php
/**
 * Defines database credentials.
 *
 * Most of Elgg's configuration is stored in the database.  This file contains the
 * credentials to connect to the database, as well as a few optional configuration
 * values.
 *
 * The Elgg installation attempts to populate this file with the correct settings
 * and then rename it to settings.php.
 *
 * @todo Turn this into something we handle more automatically.
 * @package Elgg.Core
 * @subpackage Configuration
 */

global $CONFIG;
if (!isset($CONFIG)) {
	$CONFIG = new stdClass;
}

/*
 * Standard configuration
 *
 * You will use the same database connection for reads and writes.
 * This is the easiest configuration, and will suit 99.99% of setups. However, if you're
 * running a really popular site, you'll probably want to spread out your database connections
 * and implement database replication.  That's beyond the scope of this configuration file
 * to explain, but if you know you need it, skip past this section.
 */

$CONFIG->dbuser = 'root';


$CONFIG->dbpass = '';

$CONFIG->dbname = 'minds';

$CONFIG->dbhost = 'localhost';

//I AM KEEPING THE ABOVE IN FOR FALLBACK

$CONFIG->dbprefix = 'elgg_';
/**
 * Master/Slave database setup
 */
//$CONFIG->db['split'] = true;

$CONFIG->db['write']->dbuser = 'elgg_user';
$CONFIG->db['write']->dbpass = 'M!/|/d$C0m';
$CONFIG->db['write']->dbname = 'elgg';
$CONFIG->db['write']->dbhost = '10.0.0.43';
$CONFIG->db['write']->dbprefix = 'elgg_';

$CONFIG->db['read'][0]->dbuser = 'elgg_user';
$CONFIG->db['read'][0]->dbpass = 'M!/|/d$C0m';
$CONFIG->db['read'][0]->dbname = 'elgg';
$CONFIG->db['read'][0]->dbhost = '10.0.0.75';
$CONFIG->db['read'][0]->dbprefix = 'elgg_';

/**
 * Memcache setup (optional)
 * This is where you may optionally set up memcache.
 *
 * Requirements:
 * 	1) One or more memcache servers (http://www.danga.com/memcached/)
 *  2) PHP memcache wrapper (http://uk.php.net/manual/en/memcache.setup.php)
 *
 * Note: Multiple server support is only available on server 1.2.1
 * or higher with PECL library > 2.0.0
 */
//$CONFIG->memcache = true;
//
//$CONFIG->memcache_servers = array (
//	array('server1', 11211),
//	array('server2', 11211)
//);


/**
 * Use non-standard headers for broken MTAs.
 *
 * The default header EOL for headers is \r\n.  This causes problems
 * on some broken MTAs.  Setting this to TRUE will cause Elgg to use
 * \n, which will fix some problems sending email on broken MTAs.
 *
 * @global bool $CONFIG->broken_mta
 */
$CONFIG->broken_mta = FALSE;

/**
 * Disable the database query cache
 *
 * Elgg stores each query and its results in a query cache.
 * On large sites or long-running scripts, this cache can grow to be
 * large.  To disable query caching, set this to TRUE.
 *
 * @global bool $CONFIG->db_disable_query_cache
 */
$CONFIG->db_disable_query_cache = FALSE;

/**
 * Minimum password length
 *
 * This value is used when validating a user's password during registration.
 *
 * @global int $CONFIG->min_password_length
 */
$CONFIG->min_password_length = 6;
