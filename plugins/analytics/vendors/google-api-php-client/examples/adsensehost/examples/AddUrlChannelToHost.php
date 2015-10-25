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
 * This example adds a URL channel to a host ad client.
 *
 * To get ad clients, see GetAllAdClientsForHost.php.
 * Tags: urlchannels.insert
 *
 * @author Sérgio Gomes <sgomes@google.com>
 */
class AddUrlChannelToHost extends BaseExample {
  public function render() {
    $adClientId = HOST_AD_CLIENT_ID;

    $urlChannel = new Google_UrlChannel();
    $urlChannel->setUrlPattern(sprintf('www.example.com/%s',
      $this->getUniqueName()));

    // Retrieve URL channels list, and display it.
    $result = $this->adSenseHostService->urlchannels
        ->insert($adClientId, $urlChannel);
    $mainFormat = 'URL channel with id "%s" and URL pattern "%s" was created.';
    $content = sprintf($mainFormat, $result['id'], $result['urlPattern']);
    print $content;
  }
}

