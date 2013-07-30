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
 * This example adds a custom channel to a host ad client.
 *
 * To get ad clients, see GetAllAdClientsForHost.php.
 * Tags: customchannels.insert
 *
 * @author Sérgio Gomes <sgomes@google.com>
 */
class AddCustomChannelToHost extends BaseExample {
  public function render() {
    $adClientId = HOST_AD_CLIENT_ID;

    $customChannel = new Google_CustomChannel();
    $customChannel->setName(sprintf('Sample Channel #%s',
        $this->getUniqueName()));

    // Retrieve custom channels list, and display it.
    $result = $this->adSenseHostService->customchannels
        ->insert($adClientId, $customChannel);
    $mainFormat =
        'Custom channel with ID "%s", code "%s" and name "%s" was created.';
    $content = sprintf($mainFormat, $result['id'], $result['code'],
        $result['name']);
    print $content;
  }
}

