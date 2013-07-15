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
 * This example illustrates how to retrieve all direct deals associated to the
 * user.
 *
 * Tags: directDeals.list
 *
 * @author David Torres <david.t@google.com>
 */
class GetDirectDeals extends BaseExample {
  public function run() {
    $result = $this->service->directDeals->listDirectDeals();

    printf('<h2>Listing of user associated direct deals</h2>');

    if (!isset($result['direct_deals']) || !count($result['direct_deals'])) {
      print '<p>No direct deals found</p>';
      return;
    }
    foreach ($result['direct_deals'] as $directDeal) {
      $this->printResult($directDeal);
    }
  }

  /**
   * (non-PHPdoc)
   * @see BaseExample::getName()
   * @return string
   */
  public function getName() {
    return "Get Direct Deals";
  }
}

