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
 * Functions for HTML generation.
 *
 * @author Silvano Luciani <silvano.luciani@gmail.com>
 */

/**
 * Opens the HTML.
 * @param string $title the title of the page
 */
function printHtmlHeader($title) {
  $htmlTitle = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
  print '<!DOCTYPE html>' . "\n";
  print '<html>' . "\n";
  print '<head><title>' . $htmlTitle . '</title></head>' . "\n";
  print '<link rel="stylesheet" href="style.css" type="text/css" />' . "\n";
  print '<body>' . "\n";
}

/**
 * Closes the HTML.
 */
function printHtmlFooter() {
  print '</body>' . "\n";
  print '</html>' . "\n";
}

/**
 * Opens the table that contains the report data and writes the headers.
 * @param array $headers the headers of the table
 */
function printReportTableHeader($headers) {
  print '<table id="report">' . "\n";
  print '<thead>' . "\n";
  print '<tr>' . "\n";
  foreach ($headers as $header) {
    print '<th scope="col">' . $header['name'] . '</th>' . "\n";
  }
  print '</tr>' . "\n";
  print '</thead>' . "\n";
  print '<tbody>' . "\n";
}

/**
 * Prints table rows for the data contained in $rows
 * @param array $rows the content of the rows in the table
 */
function printReportTableRows($rows) {
  foreach ($rows as $row) {
    print '<tr class="highlight">' . "\n";
    foreach ($row as $column) {
      print '<td>' . $column . '</td>' . "\n";
    }
    print '</tr>' . "\n";
  }
}

/**
 * No result row for a table
 * @param string $columnsTotal number of columns in the table
 */
function printNoResultForTable($columnsTotal) {
  print
      '<tr><td colspan="' . $columnsTotal . '">No result was found</td></tr>';
}

/**
 * Closes the table that contains the report data.
 */
function printReportTableFooter() {
  print '</tbody>' . "\n";
  print '<tfoot></tfoot>' . "\n";
  print '</table>' . "\n";
}

/**
 * Opens a list.
 * @param string $list_class CSS class for the list
 */
function printListHeader($list_class) {
  print '<ol class="' . $list_class . '">' . "\n";
}

/**
 * No results line for a list
 */
function printNoResultForList() {
  print '<li>No results found<\li>' . "\n";
}

/**
 * Prints an element of a list
 * @param string $content the content of the element
 */
function printListElement($content) {
  print '<li class="highlight">' . $content . '</li>' . "\n";
}

/**
 * The lines of the clients have a nested list.
 * @param array $content an array containing the contents
 */
function printListElementForClients(array $content) {
  print '<li class="highlight">' . $content[0] . "\n";
  print '<ul>' . "\n";
  for ($i = 1; $i < count($content); $i++) {
    print '<li>' . $content[$i] . '</li>' . "\n";
  }
  print '</ul>' . "\n";
  print '</li>' . "\n";
}

/**
 * Closes the list.
 */
function printListFooter() {
  print '</ol>' . "\n";
}

/**
 * Prints the index with links to the examples.
 * @param array $actions supported actions
 */
function printIndex($actions) {
  print '<ul class="nav">' . "\n";
  foreach ($actions as $action) {
    print '<li><a class="highlight" href=?action=' . $action . '>'
        . actionNameToWords($action) . '</a></li>' . "\n";
  }
  print '</ul>' . "\n";
}

/**
 * Insert spaces between the ProperCase action name.
 * @param string $actionName the name of the action
 * @return string the transformed string
 */
function actionNameToWords($actionName) {
  return preg_replace('/([[:lower:]])([[:upper:]])/', '$1 $2', $actionName);
}

/**
 * Prints an error when a paginated report would contain more than the allowed
 * number of results.
 */
function printPaginationError() {
  print '<p>The number of results for your query exceeded the maximum allowed'
      . ' for paginated reports, that is ' . AD_ROW_LIMIT . ' results.</p>';
  print '<p>Please use the <a href="?action=GenerateReport"> non paginated'
      . ' report</a> instead.</p>';
}

/**
 * Genereate and returns the html code for the chart page.
 * @param mixed $data The data contained in the table
 * @param mixed $columns Description of the table columns
 * @param string $type Required chart type
 * @param mixed $options Options for the chart
 * @return string The html code to that draws the chart
 */
function generateChartHtml($data, $columns, $type, $options) {
  $columnsHtml = '';
  foreach ($columns as $column) {
    $columnsHtml .=
        'data.addColumn(\'' . $column[0] . '\', \'' . $column[1] . '\');';
  }
  $page = <<<CHART
<html>
  <head>
    <title>Pie Chart Example</title>
    <script type="text/javascript"
      src='https://www.google.com/jsapi?autoload=
          {"modules":[{"name":"visualization","version":"1"}]}'>
    </script>
  </head>
  <body>
    <div id="vis_div" style="width: 600px; height: 400px;"></div>
    <script type="text/javascript">
      var data = new google.visualization.DataTable();
      $columnsHtml
      data.addRows($data);
      var wrapper = new google.visualization.ChartWrapper({
        chartType: '$type',
        dataTable: data,
        options: $options,
        containerId: 'vis_div'
      });
      wrapper.draw();
    </script>
  </body>
</html>
CHART;
  return $page;
}

