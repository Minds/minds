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

// Require the base class.
require_once __DIR__ . "/../BaseExample.php";

/**
 * This example illustrates how to retrieve all accounts associated to the user.
 *
 * Tags: accounts.list
 *
 * @author david.t@google.com (David Torres)
 */
class GetAllAccounts extends BaseExample {
  /**
   * (non-PHPdoc)
   * @see BaseExample::run()
   */
  public function run() {
    $result = $this->service->accounts->listAccounts();

    print '<h2>Listing of user associated accounts</h2>';
    foreach ($result['items'] as $account) {
      $this->printResult($account);
    }
  }

  /**
   * (non-PHPdoc)
   * @see BaseExample::getName()
   * @return string
   */
  public function getName() {
    return 'Get All Accounts';
  }
}

