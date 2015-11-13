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

// Error if PDO and PDO_SQLITE not present
if (!extension_loaded('pdo') || !extension_loaded('pdo_sqlite')) {
  throw new Exception('The sample code needs PDO and PDO_SQLITE PHP extension');
}

/**
 * Include the library files for the api client and AdSense service class.
 */
require_once "../../Google_Client.php";
require_once "../../contrib/Google_AdsenseService.php";

/**
 * Handles authentication and OAuth token storing.
 * Assumes the presence of a sqlite database called './examples.sqlite'
 * containing a table called 'auth' composed of two VARCHAR(255) fields called
 * 'user' and 'token'.
 *
 * @author Silvano Luciani <silvano.luciani@gmail.com>
 */

class AdSenseAuth {
  protected $apiClient;
  protected $adSenseService;
  private $user;

  /**
   * Create the dependencies.
   * (Inject them in a real world app!!)
   */
  public function __construct() {
    // Create the apiClient instances.
    $this->apiClient = new Google_Client();
    // Visit https://code.google.com/apis/console?api=adsense to
    // generate your oauth2_client_id, oauth2_client_secret, and to
    // register your oauth2_redirect_uri.
    $this->apiClient->setClientId('YOUR_CLIENT_ID_HERE');
    $this->apiClient->setClientSecret('YOUR_CLIENT_SECRET_HERE');
    $this->apiClient->setDeveloperKey('YOUR_DEVELOPER_KEY_HERE');
    // Point the oauth2_redirect_uri to index.php.
    $this->apiClient->setRedirectUri('http://localhost/index.php');
    // Create the api AdsenseService instance.
    $this->adSenseService = new Google_AdsenseService($this->apiClient);
  }

  /**
   * Check if a token for the user is already in the db, otherwise perform
   * authentication.
   * @param string $user The user to authenticate
   */
  public function authenticate($user) {
    $this->user = $user;
    $dbh = new PDO('sqlite:examples.sqlite');
    $token = $this->getToken($dbh);
    if (isset($token)) {
      // I already have the token.
      $this->apiClient->setAccessToken($token);
    } else {
      // Override the scope to use the readonly one
      $this->apiClient->setScopes(
          array("https://www.googleapis.com/auth/adsense.readonly"));
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
  public function getAdSenseService() {
    return $this->adSenseService;
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

