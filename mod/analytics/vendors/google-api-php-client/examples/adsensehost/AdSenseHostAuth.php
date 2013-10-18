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

// Error if PDO and PDO_SQLITE not present
if (!extension_loaded('pdo') || !extension_loaded('pdo_sqlite')) {
  throw new Exception('The sample code needs PDO and PDO_SQLITE PHP extension');
}

/**
 * Include the library files for the api client and AdSense service class.
 */
require_once "../../src/Google_Client.php";
require_once "../../src/contrib/Google_AdsensehostService.php";

/**
 * Handles authentication and OAuth token storing.
 * Assumes the presence of a sqlite database called './examples.sqlite'
 * containing a table called 'auth' composed of two VARCHAR(255) fields called
 * 'user' and 'token'.
 *
 * @author Sérgio Gomes <sgomes@google.com>
 * @author Silvano Luciani <silvano.luciani@gmail.com>
 */

class AdSenseHostAuth {
  protected $apiClient;
  protected $adSenseHostService;
  private $user;

  /**
   * Create the dependencies.
   * (Inject them in a real world app!!)
   */
  public function __construct() {
    // Create the Google_Client instance.
    // You can set your credentials in the config.php file, included under the
    // src/ folder in your client library install.
    $this->apiClient = new Google_Client();
    // Create the api AdsensehostService instance.
    $this->adSenseHostService = new Google_AdsensehostService($this->apiClient);
  }

  /**
   * Check if a token for the user is already in the db, otherwise perform
   * authentication.
   * @param string $user The user to authenticate
   */
  public function authenticate($user) {
    $this->user = $user;
    $dbh = new PDO('sqlite:examples.sqlite');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $dbh->prepare('CREATE TABLE IF NOT EXISTS auth ' .
        '(user VARCHAR(255), token VARCHAR(255))');
    $stmt->execute();
    $token = $this->getToken($dbh);
    if (isset($token)) {
      // I already have the token.
      $this->apiClient->setAccessToken($token);
    } else {
      // Override the scope to use the readonly one
      $this->apiClient->setScopes(
          array("https://www.googleapis.com/auth/adsensehost"));
      // Go get the token
      $this->apiClient->setAccessToken($this->apiClient->authenticate());
      $this->saveToken($dbh, false, $this->apiClient->getAccessToken());
    }
    $dbh = null;
  }

  /**
   * Return the AdsenseService instance (to be used to retrieve data).
   * @return apiAdsenseService the authenticated apiAdsenseService instance
   */
  public function getAdSenseHostService() {
    return $this->adSenseHostService;
  }

  /**
   * During the request, the access code might have been changed for another.
   * This function updates the token in the db.
   */
  public function refreshToken() {
    if ($this->apiClient->getAccessToken() != null) {
      $dbh = new PDO('sqlite:examples.sqlite');
      $this->saveToken($dbh, true, $this->apiClient->getAccessToken());
    }
  }

  /**
   * Insert/update the auth token for the user.
   * @param PDO $dbh a PDO object for the local authentication db
   * @param bool $userExists true if the user already exists in the db
   * @param string $token the auth token to be saved
   */
  private function saveToken($dbh, $userExists, $token) {
    if ($userExists) {
      $stmt = $dbh->prepare('UPDATE auth SET token=:token WHERE user=:user');
    } else {
      $stmt = $dbh
          ->prepare('INSERT INTO auth (user, token) VALUES (:user, :token)');
    }
    $stmt->bindParam(':user', $this->user);
    $stmt->bindParam(':token', $this->apiClient->getAccessToken());
    $stmt->execute();
  }

  /**
   * Retrieves token for use.
   * @param PDO $dbh a PDO object for the local authentication db
   * @return string a JSON object representing the token
   */
  private function getToken($dbh) {
    $stmt = $dbh->prepare('SELECT token FROM auth WHERE user= ?');
    $stmt->execute(array($this->user));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['token'];
  }
}

