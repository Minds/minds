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
 * Abstracts handling authorization using an opaque storage mechanism.
 * @author Nick Mihailovski (api.nickm@gmail.com)
 */
class AuthHelper {

  /** @var Google_Client $client */
  private $client;

  /** @var apiAnalyticsService $analytics */
  private $analytics;

  /** @var storage $storage */
  private $storage;

  /** @var string $controllerUrl */
  private $controllerUrl;
  private $errorMsg = null;

  /**
   * Constructor.
   * @param Google_Client $client The API client service object. Used for
   *     authorization. This is passed by reference and allows this same
   *     object (once authorized) to be used outside of this class.
   * @param storage $storage The storage mechanism to persist authorization
   *     tokens.
   * @param string $controllerUrl The Url of the main controller.
   */
  function __construct(&$client, &$storage, $controllerUrl) {
    $this->client = $client;
    $this->storage = $storage;
    $this->controllerUrl = $controllerUrl;
  }

  /**
   * Retrieves an access token from the storage object and sets it into the
   * client object.
   */
  public function setTokenFromStorage() {
    $accessToken = $this->storage->get();
    if (isset($accessToken)) {
      $this->client->setAccessToken($accessToken);
    }
  }

  /**
   * Goes through the client authorization routine. This routine both
   * redirects a user to the Google Accounts authorization screen as well as
   * handle the response from the authorization service to retrieve the
   * authorization code then exchange it for an access token. This method
   * also removes the authorization code from the URL to keep things pretty.
   * Details on how the apiClient implements authorization can be found here:
   * http://code.google.com/p/google-api-php-client/source/browse/trunk/src/auth/apiOAuth2.php#84
   * If an authorization error occurs, the exception is caught and the error
   * message is saved in $error.
   */
  public function authenticate() {
    try {
      $accessToken = $this->client->authenticate();
      $this->storage->set($accessToken);

      // Keep things pretty. Removes the auth code from the URL.
      if ($_GET['code']) {
        header("Location: $this->controllerUrl");
      }

    } catch (Google_AuthException $e) {
      $this->errorMsg = $e->getMessage();
    }
  }

  /**
   * Revokes an authorization token. This both revokes the token by making a
   * Google Accounts API request to revoke the token as well as deleting the
   * token from the storage mechanism. If any errors occur, the authorization
   * exception is caught and the message is stored in error.
   */
  public function revokeToken() {
    $accessToken = $this->storage->get();
    if ($accessToken) {
      $tokenObj = json_decode($accessToken);
      try {
        $this->client->revokeToken($tokenObj->refresh_token);
        $this->storage->delete();
      } catch (Google_AuthException $e) {
        $this->errorMsg = $e->getMessage();
      }
    }
    // Keep things pretty. Removes the auth code from the URL.
    header("Location: $this->controllerUrl");
  }

  /**
   * Returns whether the apiClient object has been authorized. If true,
   * the user can make authorized requests to the API.
   * @return bool Whether the client is authorized to make API requests.
   */
  public function isAuthorized() {
    return $this->client->getAccessToken() ? true : false;
  }

  /**
   * @return string Any error messages.
   */
  public function getError() {
    return $this->errorMsg;
  }
}

