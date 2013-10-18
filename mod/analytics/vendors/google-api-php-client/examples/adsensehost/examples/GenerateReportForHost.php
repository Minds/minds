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
 * This example retrieves a report for the specified host ad client.
 *
 * To get ad clients, see GetAllAdClientsForHost.php.
 * Tags: reports.generate
 *
 * @author Sérgio Gomes <sgomes@google.com>
 */
class GenerateReportForHost extends BaseExample {
  public function render() {
    $startDate = $this->getSixMonthsBeforeNow();
    $endDate = $this->getNow();
    $optParams = array(
      'metric' => array(
        'PAGE_VIEWS', 'AD_REQUESTS', 'AD_REQUESTS_COVERAGE',
        'CLICKS', 'AD_REQUESTS_CTR', 'COST_PER_CLICK', 'AD_REQUESTS_RPM',
        'EARNINGS'),
      'dimension' => 'DATE',
      'sort' => '+DATE',
      'filter' => array(
        'AD_CLIENT_ID==' . HOST_AD_CLIENT_ID));
    // Retrieve report.
    $report = $this->adSenseHostService->reports
        ->generate($startDate, $endDate, $optParams);

    if (isset($report['rows'])) {
      printReportTableHeader($report['headers']);
      printReportTableRows($report['rows']);
      printReportTableFooter();
    } else {
      printNoResultForTable(count($report['headers']));
    }
  }
}

