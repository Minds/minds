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

// Require the base class.
require_once __DIR__ . "/../BaseExample.php";

/**
 * This example gets the account data for a publisher from their ad client ID.
 *
 * Tags: accounts.list
 *
 * @author Sérgio Gomes <sgomes@google.com>
 */
class GetAccountDataForExistingPublisher extends BaseExample {
  public function render() {
    $adClientId = PUBLISHER_AD_CLIENT_ID;
    $listClass = 'list';
    printListHeader($listClass);
    $pageToken = null;
    do {
      // Retrieve account list, and display it.
      $result = $this->adSenseHostService->accounts->listAccounts(
          array($adClientId));
      $accounts = $result['items'];
      if (isset($accounts)) {
        foreach ($accounts as $account) {
          $frmt = 'Account with ID "%s", name "%s" and status "%s" was found.';
          $content = sprintf($frmt, $account['id'], $account['name'],
              $account['status']);
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

