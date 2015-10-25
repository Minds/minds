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
 * This example gets all the ad clients in the host account.
 *
 * Tags: adclients.list
 *
 * @author Sérgio Gomes <sgomes@google.com>
 * @author Silvano Luciani <silvano.luciani@gmail.com>
 */
class GetAllAdClientsForHost extends BaseExample {
  public function render() {
    $optParams['maxResults'] = MAX_PAGE_SIZE;
    $listClass = 'clients';
    printListHeader($listClass);
    $pageToken = null;
    do {
      $optParams['pageToken'] = $pageToken;
      // Retrieve ad client list, and display it.
      $result = $this->adSenseHostService->adclients->listAdclients($optParams);
      $adClients = $result['items'];
      if (isset($adClients)) {
        foreach ($adClients as $adClient) {
          $content = array();
          $mainFormat = 'Ad client for product "%s" with ID "%s" was found.';
          $content[] = sprintf(
              $mainFormat, $adClient['productCode'], $adClient['id']);
          $reporting = $adClient['supportsReporting'] ? 'Yes' : 'No';
          $content[] = sprintf('Supports reporting: %s', $reporting);
          printListElementForClients($content);
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

