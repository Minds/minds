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

/**
 * Implements the app execution flow.
 * Please load index.php to get the list of available examples.
 *
 * @author Sérgio Gomes <sgomes@google.com>
 * @author Silvano Luciani <silvano.luciani@gmail.com>
 */

// Host ad client ID to use in the examples where needed.
define('HOST_AD_CLIENT_ID', 'INSERT_HOST_AD_CLIENT_ID_HERE');
// Publisher account ID to use in the examples where needed.
define('PUBLISHER_ACCOUNT_ID', 'INSERT_PUBLISHER_ACCOUNT_ID_HERE');
// Publisher ad client ID to use in the examples where needed.
define('PUBLISHER_AD_CLIENT_ID', 'INSERT_PUBLISHER_AD_CLIENT_ID_HERE');
// Max results per page.
define('MAX_PAGE_SIZE', 50);

// Include the dependencies and die if any is not met.
try {
  require_once "AdSenseHostAuth.php";
  require_once "BaseExample.php";
  require_once "htmlHelper.php";
} catch (Exception $e) {
  die('Missing requirement: ' . $e->getMessage() . "\n");
}

try {
  // Build the list of supported actions.
  $actions = getSupportedActions();
  // Go through API authentication.
  $auth = new AdSenseHostAuth();
  $auth->authenticate('sample_user');
  // To get rid of the code in the URL after the authentication.
  if (isset($_GET['code'])) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
  }
  // If the action is set dispatch the action if supported
  if (isset($_GET["action"])) {
    $action = $_GET["action"];
    if (!in_array($action, $actions)) {
      die('Unsupported action:' . $action . "\n");
    }
    // Render the required action.
    require_once 'examples/' . $action . '.php';
    $class = ucfirst($action);
    $example = new $class($auth->getAdSenseHostService());
    $title = actionNameToWords($action) . ' example';
    printHtmlHeader($title);
    $example->render();
    printHtmlFooter();
    $auth->refreshToken();
  } else {
    // Show the list of links to supported actions.
    printHtmlHeader('AdSense Host API PHP usage examples.');
    printIndex($actions);
    printHtmlFooter();
  }
} catch (Exception $e) {
  die('Runtime error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
}

/**
 * Builds an array containing the supported actions.
 * @return array
 */
function getSupportedActions() {
  $actions = array();
  $dirHandler = opendir('examples');
  while ($actionFile = readdir($dirHandler)) {
    if (preg_match('/\.php$/', $actionFile)) {
      $action = preg_replace('/\.php$/', '', $actionFile);
      $actions[] = $action;
    }
  }
  closedir($dirHandler);
  asort($actions);
  return $actions;
}

