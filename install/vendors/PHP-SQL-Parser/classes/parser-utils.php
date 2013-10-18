<?php
/**
 * parser-utils.php
 *
 * These are utility functions for the PHPSQLParser.
 *
 * Copyright (c) 2010-2012, Justin Swanhart
 * with contributions by André Rothe <arothe@phosco.info, phosco@gmx.de>
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 *
 *   * Redistributions of source code must retain the above copyright notice,
 *     this list of conditions and the following disclaimer.
 *   * Redistributions in binary form must reproduce the above copyright notice,
 *     this list of conditions and the following disclaimer in the documentation
 *     and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
 * EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT
 * SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED
 * TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR
 * BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH
 * DAMAGE.
 */

require_once(dirname(__FILE__) . '/constants.php');

/**
 * This class implements some helper functions.
 * @author arothe
 *
 */
class PHPSQLParserUtils extends PHPSQLParserConstants {

    /**
     * Prints an array only if debug mode is on.
     * @param array $s
     * @param boolean $return, if true, the formatted array is returned via return parameter
     */
    protected function preprint($arr, $return = false) {
        $x = "<pre>";
        $x .= print_r($arr, 1);
        $x .= "</pre>";
        if ($return) {
            return $x;
        } else {
            if (isset($_ENV['DEBUG'])) {
                print $x . "\n";
            }
        }
    }

    /**
     * Ends the given string $haystack with the string $needle?
     * @param string $haystack
     * @param string $needle
     */
    protected function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        $start = $length * -1;
        return (substr($haystack, $start) === $needle);
    }
}
