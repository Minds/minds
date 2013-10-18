<?php
/**
 * lexer-splitter.php
 *
 * Defines the characters, which are used to split the given SQL string.
 * Part of PHPSQLParser.
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

class LexerSplitter {

    private static $splitters = array("\r\n", "!=", ">=", "<=", "<>", ":=", "\\", "&&", ">", "<", "|", "=", "^", "(",
                                      ")", "\t", "\n", "'", "\"", "`", ",", "@", " ", "+", "-", "*", "/", ";");
    private $tokenSize;
    private $hashSet;

    public function __construct() {
        $this->tokenSize = strlen(self::$splitters[0]); # should be the largest one
        $this->hashSet = array_flip(self::$splitters);
    }

    public function getMaxLengthOfSplitter() {
        return $this->tokenSize;
    }

    public function isSplitter($token) {
        return isset($this->hashSet[$token]);
    }
}
