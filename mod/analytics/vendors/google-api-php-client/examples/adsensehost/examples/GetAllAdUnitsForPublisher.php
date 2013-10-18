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
 * This example gets all ad units in a publisher ad client.
 *
 * To get ad clients, see GetAllAdClientsForPublisher.php.
 * Tags: accounts.adunits.list
 *
 * @author Sérgio Gomes <sgomes@google.com>
 */
class GetAllAdUnitsForPublisher extends BaseExample {
  public function render() {
    $adClientId = PUBLISHER_AD_CLIENT_ID;
    $accountId = PUBLISHER_ACCOUNT_ID;
    $optParams['maxResults'] = MAX_PAGE_SIZE;
    $listClass = 'list';
    printListHeader($listClass);
    $pageToken = null;
    do {
      $optParams['pageToken'] = $pageToken;
      // Retrieve ad unit list, and display it.
      $result = $this->adSenseHostService->accounts_adunits
          ->listAccountsAdunits($accountId, $adClientId, $optParams);
      if (isset($result['items'])) {
        $adUnits = $result['items'];
        foreach ($adUnits as $adUnit) {
          $content = sprintf('Ad unit with ID "%s", code "%s", name "%s" and ' .
              'status "%s" was found.',
              $adUnit['id'], $adUnit['code'], $adUnit['name'],
              $adUnit['status']);
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

