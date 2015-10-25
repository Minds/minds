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
require_once '../../src/contrib/Google_PagespeedonlineService.php';

$client = new Google_Client();
$client->setApplicationName("PageSpeedOnline_Example_App");
$service = new Google_PagespeedonlineService($client);

if (isset($_GET['url'])) {
  $result = $service->pagespeedapi->runpagespeed($_GET['url']);
}
?>
<!doctype html>
<html>
<head><link rel='stylesheet' href='style.css' /></head>
<body>
<header><h1>Google Page Speed Sample App</h1></header>
<div class="box">
  <div id="search">
    <form id="url" method="GET" action="index.php">
      <input name="url" class="url" type="text">
      <input type="submit" value="Analyze Performance">
    </form>
  </div>

  <?php if (isset($result)): ?>
    <div class="result">
      <h3>Summary</h3>
      <?php print $result['title']; ?> got a PageSpeed Score of <b><?php print $result['score']; ?></b> (out of 100).
      <div>Title: <?php print $result['title']; ?></div>
      <div>Score: <?php print $result['score']; ?></div>
      <div>Number of Resources: <?php print $result['pageStats']['numberResources']; ?></div>
      <div>Number of Hosts: <?php print $result['pageStats']['numberHosts']; ?></div>
      <div>Total Request Bytes: <?php print $result['pageStats']['totalRequestBytes']; ?></div>
      <div>Number of Static Resources: <?php print $result['pageStats']['numberStaticResources']; ?></div>
      <pre><?php var_dump($result); ?></pre>
    </div>
  <? endif ?>
</div>
</body></html>