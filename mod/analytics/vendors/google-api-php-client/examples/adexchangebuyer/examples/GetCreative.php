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
 * This example illustrates how to retrieve a creative out of the system,
 * including its status.
 *
 * Tags: creatives.get
 *
 * @author david.t@google.com (David Torres)
 */
class GetCreative extends BaseExample {
  /**
   * (non-PHPdoc)
   * @see BaseExample::getInputParameters()
   * @return array
   */
  protected function getInputParameters() {
    return array(array('name' => 'account_id',
                       'display' => 'Account id',
                       'required' => true),
                 array('name' => 'ad_group_id',
                       'display' => 'Ad group id',
                       'required' => true),
                 array('name' => 'buyer_creative_id',
                       'display' => 'Buyer creative id',
                       'required' => true));
  }

  /**
   * (non-PHPdoc)
   * @see BaseExample::run()
   */
  public function run() {
    $values = $this->formValues;

    try {
      $creative = $this->service->creatives->get($values['account_id'],
          $values['buyer_creative_id'], $values['ad_group_id']);
      print '<h2>Found creative</h2>';
      $this->printResult($creative);
    } catch (Google_Exception $ex) {
      if ($ex->getCode() == 404 || $ex->getCode() == 403) {
        print '<h1>Creative not found or can\'t access creative</h1>';
      } else {
        throw $ex;
      }
    }
  }

  /**
   * (non-PHPdoc)
   * @see BaseExample::getName()
   * @return string
   */
  public function getName() {
    return 'Get Creative';
  }
}

