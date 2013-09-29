<?php
/**
 * exceptions.php
 *
 * This file implements some expection classes which are used within the
 * PHPSQLParser package.
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

class UnableToCreateSQLException extends Exception {

    protected $part;
    protected $partkey;
    protected $entry;
    protected $entrykey;

    public function __construct($part, $partkey, $entry, $entrykey) {
        $this->part = $part;
        $this->partkey = $partkey;
        $this->entry = $entry;
        $this->entrykey = $entrykey;
        parent::__construct("unknown " . $entrykey . " in " . $part . "[" . $partkey . "] " . $entry[$entrykey], 15);
    }

    public function getEntry() {
        return $this->entry;
    }

    public function getEntryKey() {
        return $this->entrykey;
    }

    public function getSQLPart() {
        return $this->part;
    }

    public function getSQLPartKey() {
        return $this->partkey;
    }
}

class UnsupportedFeatureException extends Exception {

    protected $key;

    public function __construct($key) {
        $this->key = $key;
        parent::__construct($key . " not implemented.", 20);
    }

    public function getKey() {
        return $this->key;
    }
}

class InvalidParameterException extends InvalidArgumentException {

    protected $argument;

    public function __construct($argument) {
        $this->argument = $argument;
        parent::__construct("no SQL string to parse: \n" . $argument, 10);
    }

    public function getArgument() {
        return $this->argument;
    }
}

class UnableToCalculatePositionException extends Exception {

    protected $needle;
    protected $haystack;

    public function __construct($needle, $haystack) {
        $this->needle = $needle;
        $this->haystack = $haystack;
        parent::__construct("cannot calculate position of " . $needle . " within " . $haystack, 5);
    }

    public function getNeedle() {
        return $this->needle;
    }

    public function getHaystack() {
        return $this->haystack;
    }
}
