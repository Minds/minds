<?php
/**
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

require_once "../src/Google_Client.php";
require_once "BaseTest.php";
require_once "io/Google_REST.php";

class ApiOAuth2Test extends BaseTest {

  public function testSign() {
    $oauth = new Google_OAuth2();

    $oauth->developerKey = "devKey";
    $oauth->clientId = "clientId1";
    $oauth->clientSecret = "clientSecret1";
    $oauth->redirectUri = "http://localhost";
    $oauth->approvalPrompt = 'force';
    $oauth->accessType = "offline";

    $req = new Google_HttpRequest('http://localhost');
    $req = $oauth->sign($req);

    $this->assertEquals('http://localhost?key=devKey', $req->getUrl());

    // test accessToken
    $oauth->token = array(
        'access_token' => 'ACCESS_TOKEN',
        'created' => time(),
        'expires_in' => '3600'
    );

    $req = $oauth->sign($req);
    $auth = $req->getRequestHeader('authorization');
    $this->assertEquals('Bearer ACCESS_TOKEN', $auth);
  }

  public function testCreateAuthUrl() {
    $oauth = new Google_OAuth2();

    $oauth->developerKey = "devKey";
    $oauth->clientId = "clientId1";
    $oauth->clientSecret = "clientSecret1";
    $oauth->redirectUri = "http://localhost";
    $oauth->approvalPrompt = 'force';
    $oauth->accessType = "offline";

    $authUrl = $oauth->createAuthUrl("http://googleapis.com/scope/foo");
    $expected = "https://accounts.google.com/o/oauth2/auth"
        . "?response_type=code"
        . "&redirect_uri=http%3A%2F%2Flocalhost"
        . "&client_id=clientId1"
        . "&scope=http%3A%2F%2Fgoogleapis.com%2Fscope%2Ffoo"
        . "&access_type=offline"
        . "&approval_prompt=force";
    $this->assertEquals($expected, $authUrl);
  }
}
 
