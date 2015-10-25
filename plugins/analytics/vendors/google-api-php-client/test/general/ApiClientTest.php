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

class TestApiClient extends Google_Client {
  public function prepareService() {
    return parent::prepareService();
  }
};

class ApiClientTest extends BaseTest {
  public function testClient() {
    $client = new Google_Client();
    $client->setAccessType('foo');
    $this->assertEquals('foo', $client->getAuth()->accessType);

    $client->setDeveloperKey('foo');
    $this->assertEquals('foo', $client->getAuth()->developerKey);

    $client->setAccessToken(json_encode(array('access_token' => '1')));
    $this->assertEquals("{\"access_token\":\"1\"}", $client->getAccessToken());
  }

  public function testPrepareService() {
    $client = new TestApiClient();

    $service = $client->prepareService();
    $this->assertEquals("", $service['scope']);

    $client->setScopes(array("scope1", "scope2"));
    $service = $client->prepareService();
    $this->assertEquals("scope1 scope2", $service['scope']);

    $client->setScopes(array("", "scope2"));
    $service = $client->prepareService();
    $this->assertEquals(" scope2", $service['scope']);

    $client->setClientId('test1');
    $client->setRedirectUri('http://localhost/');
    $client->setScopes(array("http://test.com", "scope2"));
    $service = $client->prepareService();
    $this->assertEquals("http://test.com scope2", $service['scope']);
    $this->assertEquals(''
        .  'https://accounts.google.com/o/oauth2/auth'
        . '?response_type=code&redirect_uri=http%3A%2F%2Flocalhost%2F'
        . '&client_id=test1'
        . '&scope=http%3A%2F%2Ftest.com+scope2&access_type=offline'
        . '&approval_prompt=force', $client->createAuthUrl());
  }

  public function testSettersGetters() {
    $client = new Google_Client();
    $client->setClientId("client1");
    $client->setClientSecret('client1secret');
    $client->setState('1');
    $client->setApprovalPrompt('force');
    $client->setAccessType('offline');

    global $apiConfig;
    $this->assertEquals('client1', $apiConfig['oauth2_client_id']);
    $this->assertEquals('client1secret', $apiConfig['oauth2_client_secret']);

    $client->setRedirectUri('localhost');
    $client->setApplicationName('me');
    $client->setUseObjects(false);
    $this->assertEquals('object', gettype($client->getAuth()));
    $this->assertEquals('object', gettype($client->getCache()));
    $this->assertEquals('object', gettype($client->getIo()));


    $client->setAuthClass('Google_AuthNone');
    $client->setAuthClass('Google_OAuth2');

    try {
      $client->setAccessToken(null);
      die('Should have thrown an Google_AuthException.');
    } catch(Google_AuthException $e) {
      $this->assertEquals('Could not json decode the token', $e->getMessage());
    }

    $token = json_encode(array('access_token' => 'token'));
    $client->setAccessToken($token);
    $this->assertEquals($token, $client->getAccessToken());
  }
}
