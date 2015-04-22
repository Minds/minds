<?php
/**
 * Queue Runner
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

if(php_sapi_name() !== 'cli'){
    echo "This script must be run by the command line \n";
    exit;
}

if(!isset($argv[1])){
    echo "No Argument passed. Please pass the runner name \n";
    exit;
}


$runner = $argv[1];
echo "Starting $runner Runner \n";

try{
    echo "Running... \n";
    echo "Press Ctrl + C to cancel \n";
    $runner = Minds\Core\Queue\Runners\Factory::build($runner)->run();
} catch(Exception $e){
    echo "Failed: " . $e->getMessage() . " \n";
}
