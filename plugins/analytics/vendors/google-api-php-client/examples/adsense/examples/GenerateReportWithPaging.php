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

// Require the base class
require_once __DIR__ . "/../BaseExample.php";

/**
 * Retrieves a report for the specified ad client.
 *
 * Please only use pagination if your application requires it due to memory or
 * storage constraints.
 * If you need to retrieve more than 5000 rows, please check generateReport, as
 * due to current limitations you will not be able to use paging for large
 * reports.
 * To get ad clients, run getAllAdClients.
 * Tags: reports.generate
 *
 * @author Silvano Luciani <silvano.luciani@gmail.com>
 */

class GenerateReportWithPaging extends BaseExample {
  public function render() {
    $startDate = $this->getSixMonthsBeforeNow();
    $endDate = $this->getNow();
    $optParams = array(
      'metric' => array(
        'PAGE_VIEWS', 'AD_REQUESTS', 'AD_REQUESTS_COVERAGE',
        'CLICKS', 'AD_REQUESTS_CTR', 'COST_PER_CLICK', 'AD_REQUESTS_RPM',
        'EARNINGS'),
      'dimension' => 'DATE',
      'sort' => 'DATE',
      'filter' => array(
        'AD_CLIENT_ID==' . AD_CLIENT_ID
      )
    );
    // Retrieve report in pages and display data as we receive it.
    $startIndex = 0;
    $rowsToObtain = AD_MAX_PAGE_SIZE;
    do {
      $optParams['startIndex'] = $startIndex;
      $optParams['maxResults'] = $rowsToObtain;
      $report = $this->adSenseService->reports
          ->generate($startDate, $endDate, $optParams);

      if ($report['totalMatchedRows'] > AD_ROW_LIMIT) {
        printPaginationError();
        break;
      } else {
        if ($startIndex == 0) {
          printReportTableHeader($report['headers']);
          if ($report['totalMatchedRows'] == 0) {
            printNoResultForTable(count($report['headers']));
            break;
          }
        }
        if (isset($report['rows'])) {
          printReportTableRows($report['rows']);
          $startIndex += count($report['rows']);
        }
      }
    } while ($startIndex < $report['totalMatchedRows']);
    printReportTableFooter();
  }
}

