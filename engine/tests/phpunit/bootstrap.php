<?php
$engine = dirname(dirname(dirname(__FILE__)));

global $CONFIG;
$CONFIG = new stdClass();
$CONFIG->boot_complete = false;

@mkdir('/tmp/minds_test/', 777);
$CONFIG->dataroot = '/tmp/minds_test/';

//require_once("$engine/settings.php");

$lib_files = array( 'elgglib.php',
	'access.php', 'actions.php', 'admin.php', 'annotations.php', 'cache.php',
	'calendar.php', 'configuration.php', 'cron.php', 'database.php',
	'entities.php', 'export.php', 'extender.php', 'filestore.php', 'group.php',
	'input.php', 'languages.php', 'location.php', 'mb_wrapper.php',
	'memcache.php', 'metadata.php', 'metastrings.php', 'navigation.php',
	'notification.php', 'objects.php', 'opendd.php', 'output.php',
	'pagehandler.php', 'pageowner.php', 'pam.php', 'plugins.php',
	'private_settings.php', 'relationships.php', 'river.php', 'sessions.php',
	'sites.php', 'statistics.php', 'system_log.php', 'tags.php',
	'user_settings.php', 'users.php', 'upgrade.php', 'views.php',
	'web_services.php', 'widgets.php', 'xml.php', 'xml-rpc.php',
);

foreach ($lib_files as $file) {
	$file = "$engine/lib/$file";
	require_once($file);
}

require_once(dirname(__FILE__) .'/Minds_PHPUnit_Framework_TestCase.php');

date_default_timezone_set('America/Los_Angeles');

error_reporting(E_ALL | E_STRICT);

/**
 * Check to see if we are installed. If not, install
 */
try{
	require_once(dirname($engine) . '/install/ElggInstaller.php');
	$db = new DatabaseCall(null, 'minds_test_phpcassa', array('localhost'));
	if($db->keyspaceExists()){
		$db->dropKeyspace(true);
	}
	$db->createKeyspace();
	$db->installSchema();
	
	//bootstrap the cassandra config
	$CONFIG->cassandra = (object) array( 'servers'=> array('localhost'), 'keyspace'=>'minds_test_phpcassa');

	$site = new ElggSite();
	$site->name = 'Minds';
	$site_guid = $site->save();
	
	// bootstrap site info
	$CONFIG->site_guid = $site_guid;
	$CONFIG->site = $site;
}catch(Exception $e){
	var_dump($e);
	exit;
}
