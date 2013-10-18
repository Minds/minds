<?php
/*******************************************************************\
 *                        PROJECT INFORMATION                        *
 *                                                                   *
 *  Project:  Apache-Test                                            *
 *  URL:      http://perl.apache.org/Apache-Test/                    *
 *  Notice:   Copyright (c) 2006 The Apache Software Foundation      *
 *                                                                   *
 *********************************************************************
 *                        LICENSE INFORMATION                        *
 *                                                                   *
 *  Licensed under the Apache License, Version 2.0 (the "License");  *
 *  you may not use this file except in compliance with the          *
 *  License. You may obtain a copy of the License at:                *
 *                                                                   *
 *  http://www.apache.org/licenses/LICENSE-2.0                       *
 *                                                                   *
 *  Unless required by applicable law or agreed to in writing,       *
 *  software distributed under the License is distributed on an "AS  *
 *  IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either  *
 *  express or implied. See the License for the specific language    *
 *  governing permissions and limitations under the License.         *
 *                                                                   *
 *********************************************************************
 *                        MODULE INFORMATION                         *
 *                                                                   *
 *  This is a PHP implementation of Test::More:                      *
 *                                                                   *
 *  http://search.cpan.org/dist/Test-Simple/lib/Test/More.pm         *
 *                                                                   *
 *********************************************************************
 *                              CREDITS                              *
 *                                                                   *
 *  Originally inspired by work from Andy Lester. Written and        *
 *  maintained by Chris Shiflett. For contact information, see:      *
 *                                                                   *
 *  http://shiflett.org/contact                                      *
 *                                                                   *
 \*******************************************************************/

header('Content-Type: text/plain');
register_shutdown_function('_test_end');

$_no_plan = FALSE;
$_num_failures = 0;
$_num_skips = 0;
$_test_num = 0;
$_currTestScript = "";

function plan($plan) {
    /*
     plan('no_plan');
     plan('skip_all');
     plan(array('skip_all' => 'My reason is...'));
     plan(23);
     */

    global $_no_plan;
    global $_skip_all;

    switch ($plan) {
    case 'no_plan':
        $_no_plan = TRUE;
        break;

    case 'skip_all':
        echo "1..0\n";
        break;

    default:
        if (is_array($plan)) {
            echo "1..0 # Skip {$plan['skip_all']}\n";
            exit;
        }

        echo "1..$plan\n";
        break;
    }
}

function get_origin() {

    global $_currTestScript;

    $res = array();
    $caller = debug_backtrace();

    $thisFile = __FILE__;

    $i = 0;
    while (strstr($caller[$i]['file'], $thisFile)) {
        $i++;
    }

    $res['file'] = $caller[$i]['file'];
    $res['line'] = $caller[$i]['line'];

    if (isset($_SERVER['SERVER_ROOT'])) {
        $res['file'] = str_replace($_SERVER['SERVER_ROOT'], 't', $res['file']);
    }

    if ($res['file'] !== $_currTestScript) {
        $_currTestScript = $res['file'];
        echo "\nexecuting tests within " . $_currTestScript . "\n\n";
    }

    return $res;
}

function ok($pass, $test_name = '') {
    global $_test_num;
    global $_num_failures;
    global $_num_skips;

    $_test_num++;

    if ($_num_skips) {
        $_num_skips--;
        return TRUE;
    }

    if (!empty($test_name) && $test_name[0] != '#') {
        $test_name = "- $test_name";
    }

    $origin = get_origin();
    $file = $origin['file'];
    $line = $origin['line'];

    if ($pass) {
        echo "ok $_test_num $test_name\n";
    } else {
        diag("    Failed test (at line $line)");
        echo "not ok $_test_num $test_name\n";
        $_num_failures++;
    }

    return $pass;
}

function is($this, $that, $test_name = '') {
    $pass = ($this == $that);

    ok($pass, $test_name);

    if (!$pass) {
        diag("         got: '$this'");
        diag("    expected: '$that'");
    }

    return $pass;
}

function isnt($this, $that, $test_name = '') {
    $pass = ($this != $that);

    ok($pass, $test_name);

    if (!$pass) {
        diag("    '$this'");
        diag('        !=');
        diag("    '$that'");
    }

    return $pass;
}

function like($string, $pattern, $test_name = '') {
    $pass = preg_match($pattern, $string);

    ok($pass, $test_name);

    if (!$pass) {
        diag("                  '$string'");
        diag("    doesn't match '$pattern'");
    }

    return $pass;
}

function unlike($string, $pattern, $test_name = '') {
    $pass = !preg_match($pattern, $string);

    ok($pass, $test_name);

    if (!$pass) {
        diag("                  '$string'");
        diag("          matches '$pattern'");
    }

    return $pass;
}

function cmp_ok($this, $operator, $that, $test_name = '') {
    eval("\$pass = (\$this $operator \$that);");

    ob_start();
    var_dump($this);
    $_this = trim(ob_get_clean());

    ob_start();
    var_dump($that);
    $_that = trim(ob_get_clean());

    ok($pass, $test_name);

    if (!$pass) {
        diag("         got: $_this");
        diag("    expected: $_that");
    }

    return $pass;
}

function can_ok($object, $methods) {
    $pass = TRUE;
    $errors = array();

    foreach ($methods as $method) {
        if (!method_exists($object, $method)) {
            $pass = FALSE;
            $errors[] = "    method_exists(\$object, $method) failed";
        }
    }

    if ($pass) {
        ok(TRUE, "method_exists(\$object, ...)");
    } else {
        ok(FALSE, "method_exists(\$object, ...)");
        diag($errors);
    }

    return $pass;
}

function isa_ok($object, $expected_class, $object_name = 'The object') {
    $got_class = get_class($object);

    if (version_compare(phpversion(), '5', '>=')) {
        $pass = ($got_class == $expected_class);
    } else {
        $pass = ($got_class == strtolower($expected_class));
    }

    if ($pass) {
        ok(TRUE, "$object_name isa $expected_class");
    } else {
        ok(FALSE, "$object_name isn't a '$expected_class' it's a '$got_class'");
    }

    return $pass;
}

function pass($test_name = '') {
    return ok(TRUE, $test_name);
}

function fail($test_name = '') {
    return ok(FALSE, $test_name);
}

function diag($message) {
    if (is_array($message)) {
        foreach ($message as $current) {
            echo "# $current\n";
        }
    } else {
        echo "# $message\n";
    }
}

function include_ok($module) {
    $pass = ((include $module) == 1);
    return ok($pass);
}

function require_ok($module) {
    $pass = ((require $module) == 1);
    return ok($pass);
}

function skip($message, $num) {
    global $_num_skips;

    if ($num < 0) {
        $num = 0;
    }

    for ($i = 0; $i < $num; $i++) {
        pass("# SKIP $message");
    }

    $_num_skips = $num;
}

function eq_array($arrA, $arrB, $testName) {
    $result = serialize($arrA) === serialize($arrB);
    if ($result) {
        pass($testName);
    } else {
        fail($testName);
    }
}

/*

TODO:

function todo()
{
}

function todo_skip()
{
}

function is_deeply()
{
}

function eq_array()
{
}

function eq_hash()
{
}

function eq_set()
{
}

 */

function _test_end() {
    global $_no_plan;
    global $_num_failures;
    global $_test_num;

    if ($_no_plan) {
        echo "1..$_test_num\n";
    }

    if ($_num_failures) {
        echo "\n";
        diag("Looks like you failed $_num_failures tests of $_test_num.");
    }
}

/**
 * Helper function for getting the expected array
 * from a file as serialized string.
 * Returns an unserialized value from the given file.
 *
 * @param String $filename
 */
function getExpectedValue($path, $filename, $unserialize = true) {
    $path = explode(DIRECTORY_SEPARATOR, $path);
    $content = file_get_contents(dirname(__FILE__) . "/expected/" . array_pop($path) . "/" . $filename);
    return ($unserialize ? unserialize($content) : $content);
}

?>

