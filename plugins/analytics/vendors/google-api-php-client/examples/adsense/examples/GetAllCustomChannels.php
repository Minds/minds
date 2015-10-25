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

// Require the base class
require_once __DIR__ . "/../BaseExample.php";

/**
 * Gets all custom channels in an ad client.
 *
 * To get ad clients, run getAllAdClients.
 * Tags: customchannels.list
 *
 * @author Silvano Luciani <silvano.luciani@gmail.com>
 */
class GetAllCustomChannels extends BaseExample {
  public function render() {
    $adClientId = AD_CLIENT_ID;
    $optParams['maxResults'] = AD_MAX_PAGE_SIZE;
    $listClass = 'list';
    printListHeader($listClass);
    $pageToken = null;
    do {
      $optParams['pageToken'] = $pageToken;
      // Retrieve custom channels list, and display it.
      $result = $this->adSenseService->customchannels
          ->listCustomchannels($adClientId, $optParams);
      $customChannels = $result['items'];
      if (isset($customChannels)) {
        foreach ($customChannels as $customChannel) {
          $content = array();
          $mainFormat =
              'Custom channel with code "%s" and name "%s" was found.';
          $content[] = sprintf($mainFormat, $customChannel['code'],
              $customChannel['name']);
          if($customChannel['targetingInfo']) {
            $targetingInfo = $customChannel['targetingInfo'];
            if($targetingInfo['adsAppearOn']) {
              $content[] = sprintf('Ads appear on: %s',
                $targetingInfo['adsAppearOn']);
            }
            if($targetingInfo['location']) {
              $content[] = sprintf('Location: %s', $targetingInfo['location']);
            }
            if($targetingInfo['description']) {
              $content[] = sprintf('Description: %s',
                $targetingInfo['description']);
            }
            if($targetingInfo['siteLanguage']) {
              $content[] = sprintf('Site language: %s',
                $targetingInfo['siteLanguage']);
            }
          }
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

