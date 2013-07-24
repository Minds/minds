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
 * This sample illustrates how to do a sparse update of some of the account
 * attributes.
 *
 * Tags: accounts.patch
 *
 * @author david.t@google.com (David Torres)
 */
class UpdateAccount extends BaseExample {
  /**
   * (non-PHPdoc)
   * @see BaseExample::getInputParameters()
   * @return array
   */
  protected function getInputParameters() {
    return array(array('name' => 'account_id',
                       'display' => 'Account id to update',
                       'required' => true),
                 array('name' => 'cookie_matching_url',
                       'display' => 'New cookie matching URL',
                       'required' => true));
  }

  /**
   * (non-PHPdoc)
   * @see BaseExample::run()
   */
  public function run() {
    $values = $this->formValues;
    $account = new Google_Account();
    $account->setId($values['account_id']);
    $account->setCookieMatchingUrl($values['cookie_matching_url']);

    $account = $this->service->accounts->patch($values['account_id'],
        $account);
    print '<h2>Submitted account</h2>';
    $this->printResult($account);
  }

  /**
   * (non-PHPdoc)
   * @see BaseExample::getName()
   * @return string
   */
  public function getName() {
    return 'Update Account';
  }
}
