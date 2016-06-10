<?php

require_once(dirname(dirname(__FILE__)) . "/vendor/autoload.php");
error_reporting(E_ALL);

array_shift($argv);

$minds = new Minds\Core\Minds();
$minds->loadConfigs();
$minds->loadLegacy();

try{
    Minds\Cli\Factory::build($argv);
} catch (\Exception $e){
    echo "\n[error] " . $e->getMessage() . "\n";
}
