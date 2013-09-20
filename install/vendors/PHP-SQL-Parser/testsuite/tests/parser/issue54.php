<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$errorNumber = 0;

function issue54ErrorHandler($errno, $errstr, $errfile, $errline) {
    global $errorNumber;
    $errorNumber = $errno;    
    return true;
}
$old_error_handler = set_error_handler("issue54ErrorHandler");


$parser = new PHPSQLParser();
$sql = "SELECT schema.`table`.c as b, sum(id + 5 * (5 + 5)) as p FROM schema.table WHERE a=1 GROUP BY c HAVING p > 10 ORDER BY p DESC";
$parser->parse($sql);
$p = $parser->parsed;
ok($errorNumber === 0, 'No notice should be thrown');