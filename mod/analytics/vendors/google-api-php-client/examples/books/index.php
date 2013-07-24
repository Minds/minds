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
session_start();

require_once '../../src/Google_Client.php';
require_once '../../src/contrib/Google_BooksService.php';

// Include the boilerplate markup.
include 'interface.html';

$client = new Google_Client();
// Visit https://code.google.com/apis/console to generate your client's Developer Key.
//$client->setDeveloperKey('insert_your_developer_key');
$client->setApplicationName("Books_Example_App");
$service = new Google_BooksService($client);

/**
 * Echo the list of videos in the specified feed.
 *
 * @param array
 * @return void
 */
function echoBookList($results) {
  print <<<HTML
  <table><tr><td id="resultcell">
  <div id="searchResults">
    <table class="volumeList"><tbody>
HTML;
  foreach ($results['items'] as $result) {
    $volumeInfo = $result['volumeInfo'];
    $title = $volumeInfo['title'];
    if (isset($volumeInfo['imageLinks']['smallThumbnail'])) {
      $thumbnail = $volumeInfo['imageLinks']['smallThumbnail'];
    } else {
      $thumbnail = null;
    }

    if (isset($volumeInfo['authors'])) {
      $creators = implode(", ", $volumeInfo['authors']);
      if ($creators) $creators = "by " . $creators;
    }

    $preview = $volumeInfo['previewLink'];
    $previewLink = '';
    if ($result['accessInfo']['embeddable'] == true) {
      $previewLink = ""
          . "<a href=\"javascript:load_viewport('${preview}','viewport');\">"
          . "<img class='previewbutton' src='http://code.google.com/apis/books/images/gbs_preview_button1.png' />"
          . "</a><br>";
    }

    $thumbnailImg = ($thumbnail)
        ? "<a href='${preview}'><img alt='$title' src='${thumbnail}'/></a>"
        : '';
    print <<<HTML
    <tr>
    <td><div class="thumbnail">${thumbnailImg}</div></td>
    <td width="100%">
        <a href="${preview}">$title</a><br>
        ${creators}<br>
        ${previewLink}
    </td></tr>
HTML;
  }
  print <<<HTML
  </table></div></td>
      <td width=50% id="previewcell"><div id="viewport"></div>&nbsp;
  </td></tr></table><br></body></html>
HTML;
}

/*
 * The main controller logic of the Books volume browser demonstration app.
 */
$queryType = isset($_GET['queryType']) ? $_GET['queryType'] : null;
if ($queryType != null) {
  $volumes = $service->volumes;
  $optParams = array();

  /* display a list of volumes */
  if (isset($_GET['searchTerm'])) {
    $searchTerm = $_GET['searchTerm'];
  }
  if (isset($_GET['startIndex'])) {
    $optParams['startIndex'] = $_GET['startIndex'];
  }
  if (isset($_GET['maxResults'])) {
    $optParams['maxResults'] = $_GET['maxResults'];
  }

  /* check for one of the restricted feeds, or list from 'all' videos */
  if ($queryType == 'full_view') {
    $optParams['filter'] = 'full';
  }
  else if ($queryType == 'partial_view') {
    $optParams['filter'] = 'partial';
  }

  $results = $volumes->listVolumes($searchTerm, $optParams);
  echoBookList($results);
}
