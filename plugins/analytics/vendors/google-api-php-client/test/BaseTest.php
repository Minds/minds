<?php
/*
 * Copyright 2011 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

require_once '../src/Google_Client.php';
class BaseTest extends PHPUnit_Framework_TestCase {
  /**
   * @var Google_Client
   */
  public static $client;
  public function __construct() {
    parent::__construct();
    if (!BaseTest::$client) {
      global $apiConfig;
      $apiConfig['ioFileCache_directory'] = '/tmp/google-api-php-client/tests';

      BaseTest::$client = new Google_Client();
      if (!BaseTest::$client->getAccessToken()) {
        BaseTest::$client->setAccessToken($apiConfig['oauth_test_token']);
      }
    }
  }

  public function __destruct() {
    global $apiConfig;
    $apiConfig['oauth_test_token'] = self::$client->getAccessToken();
  }
}