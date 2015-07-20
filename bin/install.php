<?php
if (PHP_SAPI !== 'cli') {
        echo "You must use the command line to run this script.";
        exit;
}

/**
 * Start the Elgg engine
 */
require_once(dirname(dirname(__FILE__)) . "/vendor/autoload.php");
define('__MINDS_INSTALLING__', true);

echo "Instaling Minds \n";

$minds = new Minds\Core\Minds();
$minds->loadLegacy();

$engine_dir = dirname(dirname(__FILE__)) . '/engine';

/**
 * Get passed options
 */
$args = array(
    'cassandra_server' => '127.0.0.1',
    'cassandra_keyspace' => 'minds',
    'path' => dirname(dirname(__FILE__)) . '/',
    'dataroot' => '/data/',
    'default_site' => 1,
    'site_secret' => md5(rand() . microtime()),
    'site_name' => 'Minds',
    'site_url' => 'http://localhost/',
    'site_email' => 'dev@minds.io',

    'username' => 'minds',
    'password' => 'password',
    'email' => 'dev@minds.io'
);
array_shift($argv);
foreach($argv as $arg){
    $arg = substr($arg, 1);
    $part = split('=',$arg);
    $args[$part[0]] = $part[1];
}

/**
 * Setup config file
 */
$template  = file_get_contents("$engine_dir/settings.example.php");
$params = array();
foreach($args as $k => $v){
    $template = str_replace("{{" . $k . "}}", $v, $template);
}
file_put_contents("$engine_dir/settings.php", $template);


/**
 * Configure plugins
 */
$db = new Minds\Core\Data\Call('plugin', $args['cassandra_keyspace'], array($args['cassandra_server']));
$plugins = array('channel');
foreach($plugins as $plugin){
  $db->insert($plugin, array('type'=>'plugin', 'active'=>1, 'access_id'=>2));
}

echo "minds is complete \n";
