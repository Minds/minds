<?php
/**
 * Data warehouse
 */

/**
 * Start the Elgg engine
 */
require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
error_reporting(E_ALL);
while(true){
    Minds\plugin\notifications\Push::run();
    sleep(1);
}
