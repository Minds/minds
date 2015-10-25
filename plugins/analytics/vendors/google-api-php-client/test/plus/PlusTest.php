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

require_once '../src/contrib/Google_PlusService.php';

class AllPlusTests extends PHPUnit_Framework_TestSuite {
  public static function suite() {
    $suite = new PHPUnit_Framework_TestSuite();
    $suite->setName('Google Plus API tests');
    $suite->addTestSuite('PlusTest');
    return $suite;
  }
}

class PlusTest extends BaseTest {
  /** @var Google_PlusService */
  public $plus;
  public function __construct() {
    parent::__construct();
    $this->plus = new Google_PlusService(BaseTest::$client);
  }

  public function testGetPerson() {
    $person = $this->plus->people->get("118051310819094153327");
    $this->assertArrayHasKey('kind', $person);
    $this->assertArrayHasKey('displayName', $person);
    $this->assertArrayHasKey('gender', $person);
    $this->assertArrayHasKey('id', $person);
    $this->assertArrayHasKey('urls', $person);
    $this->assertArrayHasKey('organizations', $person);
  }

  public function testListActivities() {
    $activities = $this->plus->activities
        ->listActivities("118051310819094153327", "public");
    
    $item = $activities['items'][0];
    $this->assertArrayHasKey('kind', $activities);
    $this->assertArrayHasKey('items', $activities);
    $this->assertArrayHasKey('actor', $item);
    $this->assertArrayHasKey('displayName', $item['actor']);
    $this->assertArrayHasKey('url', $item['actor']);
    $this->assertArrayHasKey('object', $item);
    $this->assertArrayHasKey('access', $item);
    $this->assertArrayHasKey('crosspostSource', $item);
    $this->assertArrayHasKey('provider', $item);

  }
}
