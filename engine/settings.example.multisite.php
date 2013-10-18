<?php
	/**
	 * Elgg multisite library.
	 *
	 * @package ElggMultisite
	 * @author Marcus Povey <marcus@marcus-povey.co.uk>
	 * @copyright Marcus Povey 2010
	 * @link http://www.marcus-povey.co.uk/
 	 */

	global $CONFIG;
	
	if (!isset($CONFIG)) 
		$CONFIG = new stdClass;
		
	require_once(dirname(__FILE__) . '/lib/multisite.php');
	
	$CONFIG->multisite = new stdClass;
	
	/**
	 * Configure multisite support here, see
	 * ELGGMULTI.txt for details.
	 */
	$CONFIG->multisite->keyspace = 'elggmultisite';
	$CONFIG->multisite->servers = array('127.0.0.1');
	

	
	/**
	 * Detect the current domain and configure database accordingly.
	 * 
	 * Currently split databases are not supported.
	 */
	$db_settings = elggmulti_get_db_settings();
	$CONFIG->elgg_multisite_settings = $db_settings; // Make multisite settings available to peeps.
	
	$CONFIG->cassandra = new stdClass;
	$CONFIG->cassandra->keyspace = $db_settings->keyspace;
	$CONFIG->cassandra->servers = $db_settings->servers;
        
        // Other defaults
        $CONFIG->wwwroot = $db_settings->wwwroot;
        $CONFIG->dataroot = $db_settings->dataroot;

        // Force hardcoded paths
        $CONFIG->path = dirname(dirname(__FILE__)) . '/';
        $CONFIG->pluginspath = dirname(dirname(__FILE__)) . '/mod/';
        
	// URL
	$CONFIG->url = "";
        
        /**
         * Configure where the main site is, for minds connect on multisite install
         */
        $CONFIG->web_services_url = "http://www.minds.com/services/api/rest/json/";
        /**
         * Where minds is for minds connect. Override in test.
         */
        $CONFIG->minds_url = 'https://www.minds.com';

        /**
        * Overrides default system cache path from inside data root to custom location.
        *
        * @global string $CONFIG->system_cache_path
        * @name $CONFIG->system_cache_path
        */
       //$CONFIG->system_cache_path = '/tmp/elgg_system_cache/';

       /**
        * Elasticsearch Settings
        */
       //server for elasticsearch
       $CONFIG->elasticsearch_server = 'http://107.23.117.9:9200/';
       //namespace
       //$CONFIG->elasticsearch_prefix = 'mehmac_';
       if ($CONFIG->elgg_multisite_settings)
            $CONFIG->elasticsearch_prefix = $CONFIG->elgg_multisite_settings->getDomain();

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

       /**
        * Workaround for NGINX, set session cookie to the correct domain
        */
	$rootDomain = $_SERVER['HTTP_HOST'];
        $currentCookieParams = session_get_cookie_params();

        session_set_cookie_params(
            $currentCookieParams["lifetime"],
            $currentCookieParams["path"],
            $rootDomain,
            $currentCookieParams["secure"],
            $currentCookieParams["httponly"]
        );
