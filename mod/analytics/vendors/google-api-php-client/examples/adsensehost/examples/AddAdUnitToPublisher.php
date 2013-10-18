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
 * This example adds a new ad unit to a publisher ad client.
 *
 * To get ad clients, see GetAllAdClientsForPublisher.php.
 *
 * Tags: accounts.adunits.insert
 *
 * @author Sérgio Gomes <sgomes@google.com>
 */
class AddAdUnitToPublisher extends BaseExample {
  public function render() {
    $accountId = PUBLISHER_ACCOUNT_ID;
    $adClientId = PUBLISHER_AD_CLIENT_ID;

    $adUnit = new Google_AdUnit();
    $adUnit->setName(sprintf('Ad Unit #%s', $this->getUniqueName()));

    $contentAdsSettings = new Google_AdUnitContentAdsSettings();
    $backupOption = new Google_AdUnitContentAdsSettingsBackupOption();
    $backupOption->setType('COLOR');
    $backupOption->setColor('ffffff');
    $contentAdsSettings->setBackupOption($backupOption);
    $contentAdsSettings->setSize('SIZE_200_200');
    $contentAdsSettings->setType('TEXT');
    $adUnit->setContentAdsSettings($contentAdsSettings);

    $customStyle = new Google_AdStyle();
    $colors = new Google_AdStyleColors();
    $colors->setBackground('ffffff');
    $colors->setBorder('000000');
    $colors->setText('000000');
    $colors->setTitle('000000');
    $colors->setUrl('0000ff');
    $customStyle->setColors($colors);
    $customStyle->setCorners('SQUARE');
    $font = new AdStyleFont();
    $font->setFamily('ACCOUNT_DEFAULT_FAMILY');
    $font->setSize('ACCOUNT_DEFAULT_SIZE');
    $customStyle->setFont($font);
    $adUnit->setCustomStyle($customStyle);

    // Retrieve custom channels list, and display it.
    $result = $this->adSenseHostService->accounts_adunits
        ->insert($accountId, $adClientId, $adUnit);
    $mainFormat =
        'Ad unit w/ ID "%s", type "%s", name "%s" and status "%s" was created.';
    $content = sprintf($mainFormat, $result['id'],
    $result['contentAdsSettings']['type'], $result['name'],
        $result['status']);
    print $content;
  }
}

