<?php
/*
 * Copyright 2012 Google Inc.
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

// Require the base class
require_once __DIR__ . "/../BaseExample.php";

/**
 * Gets a specific account for the logged in user.
 * This includes the full tree of sub-accounts.
 *
 * Tags: accounts.get
 *
 * @author Silvano Luciani <silvano.luciani@gmail.com>
 */
class GetAccountTree extends BaseExample {
  public function render() {
    $accountId = ACCOUNT_ID;
    $optParams = array('tree' => true);
    // Retrieve account with sub accounts.
    $account = $this->adSenseService->accounts->get($accountId, $optParams);
    $data = array();
    $this->buildTree($account, &$data, null);
    $data = json_encode($data);
    $columns = array(
      array('string', 'Account ID'),
      array('string', 'Parent'),
      array('number', 'Weight')
    );
    $type = 'TreeMap';
    $options = json_encode(
      array('title' => 'Account treemap')
    );
    print generateChartHtml($data, $columns, $type, $options);
  }

  /**
   * Builds the data structure to represent the tree from the API response.
   * @param array $account The response of the API
   * @param array $data The data structure that represent the tree
   * @param string $parent The parent for the current node
   */
  private function buildTree($account, $data, $parent) {
    $data[] = array($account['name'], null, 1);
    if ($account['subAccounts']) {
      foreach($account['subAccounts'] as $subAccount) {
        $this->buildTree($subAccount, $data, $account['name']);
      }
    }
  }
}

