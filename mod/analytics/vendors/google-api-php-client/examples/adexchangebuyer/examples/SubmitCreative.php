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

// Require the base class.
require_once __DIR__ . "/../BaseExample.php";

/**
 * This example illustrates how to submit a new creative to the Google's
 * verification pipeline.
 *
 * Tags: creatives.insert
 *
 * @author david.t@google.com (David Torres)
 */
class SubmitCreative extends BaseExample {
  protected function getInputParameters() {
    return array(array('name' => 'account_id',
                       'display' => 'Account id',
                       'required' => true),
                 array('name' => 'ad_group_id',
                       'display' => 'Ad group id',
                       'required' => true),
                 array('name' => 'buyer_creative_id',
                       'display' => 'Buyer creative id',
                       'required' => true),
                 array('name' => 'advertiser_name',
                       'display' => 'Advertiser name',
                       'required' => true),
                 array('name' => 'html_snippet',
                       'display' => 'HTML Snippet',
                       'required' => true),
                 array('name' => 'click_through_urls',
                       'display' => 'Click through URLs',
                       'required' => true),
                 array('name' => 'width',
                       'display' => 'Width',
                       'required' => true),
                 array('name' => 'height',
                       'display' => 'Height',
                       'required' => true));
  }

  public function run() {
    $values = $this->formValues;

    $creative = new Google_Creative();
    $creative->setAccountId($values['account_id']);
    $creative->setAdgroupId($values['ad_group_id']);
    $creative->setBuyerCreativeId($values['buyer_creative_id']);
    $creative->setAdvertiserName($values['advertiser_name']);
    $creative->setHTMLSnippet($values['html_snippet']);
    $creative->setClickThroughUrl(explode(',', $values['click_through_urls']));
    $creative->setWidth($values['width']);
    $creative->setHeight($values['height']);

    $creative = $this->service->creatives->insert($creative);
    print '<h2>Submitted creative</h2>';
    $this->printResult($creative);
  }

  /**
   * (non-PHPdoc)
   * @see BaseExample::getName()
   * @return string
   */
  public function getName() {
    return 'Submit Creative';
  }
}

