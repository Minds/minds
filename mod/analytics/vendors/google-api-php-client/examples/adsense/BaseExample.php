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

/**
 * Include the AdSense service class and the HTML generation functions.
 */
require_once "../../src/contrib/Google_AdsenseService.php";
require_once "htmlHelper.php";

/**
 * Uses an instance of apiAdsenseService to retrieve the data and renders
 * the screens.
 *
 * @author Silvano Luciani <silvano.luciani@gmail.com>
 */
abstract class BaseExample {
  protected $adSenseService;
  protected $dateFormat = 'Y-m-d';

  /**
   * Inject the dependency.
   * @param Google_AdsenseService $adSenseService an authenticated instance
   *     of Google_AdsenseService
   */
  public function __construct(Google_AdsenseService $adSenseService) {
    $this->adSenseService = $adSenseService;
  }

  /**
   * Get the date for the instant of the call.
   * @return string the date in the format expressed by $this->dateFormat
   */
  protected function getNow() {
    $now = new DateTime();
    return $now->format($this->dateFormat);
  }

  /**
   * Get the date six month before the instant of the call.
   * @return string the date in the format expressed by $this->dateFormat
   */
  protected function getSixMonthsBeforeNow() {
    $sixMonthsAgo = new DateTime('-6 months');
    return $sixMonthsAgo->format($this->dateFormat);
  }

  /**
   * Implemented in the specific example class.
   */
  abstract public function render();

}

