<?php
require_once(dirname(__FILE__) . '/../../../php-sql-parser.php');
require_once(dirname(__FILE__) . '/../../test-more.php');

$testname = "issue 46, throws exception on error";
try {

    $parser = new PHPSQLParser();
    $sql = "SELECT abc'haha'";  // test code from issue doesn't longer create an exception
    $parser->parse($sql, true);
    
    $p = $parser->parsed;
    print_r($p);
        
    fail($testname);
    
} catch (UnableToCalculatePositionException $e) {
    ok(true, $testname);
}