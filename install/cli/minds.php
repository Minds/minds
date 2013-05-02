<?php
/**
 * Sample cli installer script
 */

$enabled = false;

// Do not edit below this line. //////////////////////////////


if (!$enabled) {
	echo "To enable this script, change \$enabled to true.\n";
	echo "You *must* disable this script after a successful installation.\n";
	exit;
}

if (PHP_SAPI !== 'cli') {
	echo "You must use the command line to run this script.";
	exit;
}

require_once(dirname(dirname(__FILE__)) . "/ElggInstaller.php");

$installer = new ElggInstaller();

// none of the following may be empty
$params = array(
	// database parameters
	'dbuser' => '',
	'dbpassword' => '',
	'dbname' => '',

	// site settings
	'sitename' => '',
	'siteemail' => '',
	'wwwroot' => '',
	'dataroot' => '',

	// admin account
	'displayname' => '',
	'email' => '',
	'username' => '',
	'password' => '',
);

// install and create the .htaccess file
$installer->batchInstall($params, TRUE);

// at this point installation has completed (otherwise an exception halted execution).

// at this point installation has completed (otherwise an exception halted execution).
function minds_setup_default(){
	elgg_generate_plugin_entities();
	$installed_plugins = elgg_get_plugins('any');
	$running_plugins = elgg_get_plugins('active');
	foreach($running_plugins as $plugin){
		$plugin->deactivate();
	}
	/**
	 * Default plugins to install, ordering included
	 */
	$defaults = array(	'uservalidationbyemail', 
						'htmlawed',
						'logbrowser',
						'logrotate',
						'oauth2', 
						'oauth_api', 
						'channel', 
						'groups',
						'wall',
						'tidpics', 
						'archive', 
						'embed',
						'embed_extender',
						'thumbs',
						'minds_search', 
						'minds_comments',
						'minds_social',
						'minds_webservices',
						'persona',
						'notifications',
						'bootcamp',
						'mobile',
						'minds'
					);
	foreach($defaults as $priority => $plugin_id){
		$plugin = elgg_get_plugin_from_id($plugin_id);
		$plugin->setPriority('last');
		$plugin->enable();
	}
}

minds_setup_default();

// try to rewrite the script to disable it.
if (is_writable(__FILE__)) {
	$code = file_get_contents(__FILE__);
	if (preg_match('~\\$enabled\\s*=\\s*(true|1)\\s*;~i', $code)) {
		// looks safe to rewrite
		$code = preg_replace('~\\$enabled\\s*=\\s*(true|1)\\s*;~i', '$enabled = false;', $code);
		file_put_contents(__FILE__, $code);

		echo "\nNote: This script has been disabled for your safety.\n";
		exit;
	}
}

echo "\nWarning: You *must* disable this script by setting \$enabled = false;.\n";
echo "Leaving this script enabled could endanger your installation.\n";
