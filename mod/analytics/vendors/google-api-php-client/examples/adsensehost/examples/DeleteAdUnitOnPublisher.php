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

// ID of the ad unit to be deleted.
define('PUBLISHER_AD_UNIT_ID', 'INSERT_AD_UNIT_ID_HERE');

/**
 * This example deletes an ad unit on a publisher ad client.
 *
 * To get ad clients, see GetAllAdClientsForPublisher.php.
 * To get ad units, see GetAllAdUnitsForPublisher.php.
 *
 * Tags: accounts.adunits.delete
 *
 * @author Sérgio Gomes <sgomes@google.com>
 */
class DeleteAdUnitOnPublisher extends BaseExample {
  public function render() {
    $accountId = PUBLISHER_ACCOUNT_ID;
    $adClientId = PUBLISHER_AD_CLIENT_ID;
    $adUnitId = PUBLISHER_AD_UNIT_ID;

    // Retrieve custom channels list, and display it.
    $result = $this->adSenseHostService->accounts_adunits
        ->delete($accountId, $adClientId, $adUnitId);
    $mainFormat = 'Ad unit with ID "%s" was deleted.';
    $content = sprintf($mainFormat, $result['id']);
    print $content;
  }
}

