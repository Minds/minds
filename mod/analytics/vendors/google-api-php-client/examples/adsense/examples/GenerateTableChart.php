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
 * Generates a Table Chart for a report.
 *
 * @author Silvano Luciani <silvano.luciani@gmail.com>
 */
class GenerateTableChart extends BaseExample {
  public function render() {
    $startDate = $this->getSixMonthsBeforeNow();
    $endDate = $this->getNow();
    $optParams = array(
        'metric' => array('AD_REQUESTS', 'MATCHED_AD_REQUESTS',
            'INDIVIDUAL_AD_IMPRESSIONS'),
        'dimension' => array('AD_CLIENT_ID'),
        'sort' => 'AD_CLIENT_ID'
    );
    // Retrieve report.
    $report = $this->adSenseService->reports
        ->generate($startDate, $endDate, $optParams);
    $data = $report['rows'];
    // We need to convert the metrics to numeric values for the chart.
    foreach ($data as &$row) {
      $row[1] = (int)$row[1];
      $row[2] = (int)$row[2];
      $row[3] = (int)$row[3];
    }
    $data = json_encode($data);
    $columns = array(
      array('string', 'Ad client id'),
      array('number', 'Ad requests'),
      array('number', 'Matched ad requests'),
      array('number', 'Individual ad impressions')
    );
    $type = 'Table';
    $options = json_encode(array());
    print generateChartHtml($data, $columns, $type, $options);
  }
}

