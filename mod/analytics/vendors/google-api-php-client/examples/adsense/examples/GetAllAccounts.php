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
 * Gets all accounts for the logged in user.
 *
 * Tags: accounts.list
 *
 * @author Silvano Luciani <silvano.luciani@gmail.com>
 */
class GetAllAccounts extends BaseExample {
  public function render() {
    $optParams['maxResults'] = AD_MAX_PAGE_SIZE;
    $listClass = 'list';
    printListHeader($listClass);
    $pageToken = null;
    do {
      $optParams['pageToken'] = $pageToken;
      // Retrieve account list, and display it.
      $result = $this->adSenseService->accounts->listAccounts($optParams);
      $accounts = $result['items'];
      if (isset($accounts)) {
        foreach ($accounts as $account) {
          $format = 'Account with ID "%s" and name "%s" was found.';
          $content = sprintf(
              $format, $account['id'], $account['name']);
          printListElement($content);
        }
        $pageToken = isset($result['nextPageToken']) ? $result['nextPageToken']
            : null;
      } else {
        printNoResultForList();
      }
    } while ($pageToken);
    printListFooter();
  }
}

