<?php
error_reporting(E_STRICT);
require_once '../EpiCurl.php';
require_once '../EpiOAuth.php';
require_once '../EpiTwitter.php';
require_once 'PHPUnit/Framework.php';

class EpiTwitterTest extends PHPUnit_Framework_TestCase
{
  public $consumer_key = 'jdv3dsDhsYuJRlZFSuI2fg';
  public $consumer_secret = 'NNXamBsBFG8PnEmacYs0uCtbtsz346OJSod7Dl94';
  public $token = '25451974-uakRmTZxrSFQbkDjZnTAsxDO5o9kacz2LT6kqEHA';
  public $secret= 'CuQPQ1WqIdSJDTIkDUlXjHpbcRao9lcKhQHflqGE8';
  public $id = '25451974';
  public $screenName = 'jmathai_test';
  public $twitterUsername = 'jmathai_test';
  public $twitterPassword = 'jmathai_test';
  function setUp()
  {
    // key and secret for a test app (don't really care if this is public)
    $this->twitterObj = new EpiTwitter($this->consumer_key, $this->consumer_secret, $this->token, $this->secret);
    $this->twitterObjUnAuth = new EpiTwitter($this->consumer_key, $this->consumer_secret);
    $this->twitterObjBadAuth = new EpiTwitter('foo', 'bar', 'foo', 'bar');
    // these 3 lines turn on asynchronous calls
    $this->twitterObj->useAsynchronous(true);
    $this->twitterObjUnAuth->useAsynchronous(true);
    $this->twitterObjBadAuth->useAsynchronous(true);
  }

  function testGetAuthenticateurl()
  {
    $aUrl = $this->twitterObjUnAuth->getAuthenticateUrl();
    $this->assertTrue(strstr($aUrl, 'https://api.twitter.com/oauth/authenticate') !== false, 'Authenticate url did not contain member definition from EpiTwitter class');

    $aUrl = $this->twitterObjUnAuth->getAuthenticateUrl(null, array('force_login'=>'true'));
    $this->assertTrue(strstr($aUrl, 'https://api.twitter.com/oauth/authenticate') !== false, 'Authenticate url did not contain member definition from EpiTwitter class');
    $this->assertTrue(strstr($aUrl, 'force_login=true') !== false, 'Authenticate url did not contain member definition from EpiTwitter class');
  }

  function testGetAuthorizeUrl()
  {
    $aUrl = $this->twitterObjUnAuth->getAuthorizeUrl($this->token);
    $this->assertTrue(strstr($aUrl, 'https://api.twitter.com/oauth/authorize') !== false, 'Authorize url did not contain member definition from EpiTwitter class');
  }

  function testGetRequestToken()
  {
    $resp = $this->twitterObjUnAuth->getRequestToken();
    $this->assertTrue(strlen($resp->oauth_token) > 0, "oauth_token is longer than 0");
    $this->assertTrue(strlen($resp->oauth_token_secret) > 0, "oauth_token_secret is longer than 0");
    $this->assertTrue($resp->oauth_callback_confirmed == 'true', "oauth_callback is not = true");

    $resp = $this->twitterObjUnAuth->getRequestToken(array('oauth_callback' => urlencode('http://www.yahoo.com')));
    $this->assertTrue(strlen($resp->oauth_token) > 0, "oauth_token is longer than 0");
    $this->assertTrue(strlen($resp->oauth_token_secret) > 0, "oauth_token_secret is longer than 0");
    $this->assertTrue($resp->oauth_callback_confirmed == 'true', "oauth_callback is not = true");
  }

  function testBooleanResponse()
  {
    $resp = $this->twitterObj->get('/friendships/exists.json', array('user_a' => 'jmathai_test','user_b' => 'jmathai'));
    $this->assertTrue(gettype($resp->response) === 'boolean', 'response should be a boolean for friendship exists');
    $this->assertTrue($resp->response, 'response should be true for friendship exists');
    // __call
    $resp = $this->twitterObj->get_friendshipsExists(array('user_a' => 'jmathai_test','user_b' => 'jmathai'));
    $this->assertTrue(gettype($resp->response) === 'boolean', 'response should be a boolean for friendship exists');
    $this->assertTrue($resp->response, 'response should be true for friendship exists');
  }

  function testGetVerifyCredentials()
  {
    $resp = $this->twitterObj->get('/account/verify_credentials.json');
    $this->assertTrue(strlen($resp->responseText) > 0, 'responseText was empty');
    $this->assertTrue($resp instanceof EpiTwitterJson, 'response is not an array');
    $this->assertTrue(!empty($resp->screen_name), 'member property screen_name is empty');
    $this->assertFalse($resp->protected, 'protected is false');
    // __call
    $resp = $this->twitterObj->get_accountVerify_credentials();
    $this->assertTrue(strlen($resp->responseText) > 0, 'responseText was empty');
    $this->assertTrue($resp instanceof EpiTwitterJson, 'response is not an array');
    $this->assertTrue(!empty($resp->screen_name), 'member property screen_name is empty');
    $this->assertFalse($resp->protected, 'protected is false');
  }

  function testGetWithParameters()
  {
    $resp = $this->twitterObj->get('/statuses/friends_timeline.json', array('since_id' => 1));
    $this->assertTrue(!empty($resp->response[0]['user']['screen_name']), 'first status has no screen name');
    // __call
    $resp = $this->twitterObj->get_statusesFriends_timeline(array('since_id' => 1));
    $this->assertTrue(!empty($resp->response[0]['user']['screen_name']), 'first status has no screen name');
  }

  function testGetFollowers()
  {
    $resp = $this->twitterObj->get('/statuses/followers.json');
    $this->assertTrue(count($resp) > 0, 'Count of followers is not greater than 0');
    $this->assertTrue(!empty($resp[0]), 'array access for resp is empty');
    foreach($resp as $k => $v)
    {
      $this->assertTrue(!empty($v->screen_name), 'screen name for one of the resp nodes is empty');
    }
    $this->assertTrue($k > 0, 'test did not properly loop over followers');
    // __call
    $resp = $this->twitterObj->get_statusesFollowers();
    $this->assertTrue(count($resp) > 0, 'Count of followers is not greater than 0');
    $this->assertTrue(!empty($resp[0]), 'array access for resp is empty');
    foreach($resp as $k => $v)
    {
      $this->assertTrue(!empty($v->screen_name), 'screen name for one of the resp nodes is empty');
    }
    $this->assertTrue($k > 0, 'test did not properly loop over followers');
  }

  function testPostStatus()
  {
    $statusText = 'Testing really weird chars "~!@#$%^&*()-+\[]{}:\'>?<≈ç∂´ß©ƒ˙˙∫√√ƒƒ∂∂†¥∆∆∆ (time: ' . time() . ')';
    $resp = $this->twitterObj->post('/statuses/update.json', array('status' => $statusText));
    $this->assertEquals($resp->text, str_replace(array('<','>'),array('&lt;','&gt;'),$statusText), 'The status was not updated correctly');
    // starting with @ addresses gh-40 (basic)
    $statusText = '@ (start with an at sign) at time of ' . time();
    $resp = $this->twitterObj->post('/statuses/update.json', array('status' => $statusText));
    $this->assertEquals($resp->text, str_replace(array('<','>'),array('&lt;','&gt;'),$statusText), 'The status was not updated correctly when starting witn an @ sign');

    // __call
    $statusText = '_call version of the randomness ∂´ßƒƒ∂∂†¥©ƒ˙˙∫√√"~!@#$%^&*()-+\[]{}:\'>?<≈ç∆∆∆ (time: ' . time() . ')';
    $resp = $this->twitterObj->post_statusesUpdate(array('status' => $statusText));
    $this->assertEquals($resp->text, str_replace(array('<','>'),array('&lt;','&gt;'),$statusText), 'The status was not updated correctly for __call');
    
    $statusText = 'Testing a random status (time: ' . time() . ')';
    $resp = $this->twitterObj->post_statusesUpdate(array('status' => $statusText));
    $this->assertEquals($resp->text, $statusText, 'The status was not updated correctly_ for call w/o randomness');
    // reply to it
    $statusText = 'Testing a random status with reply to id (reply to: ' . $resp->id . ')';
    $resp = $this->twitterObj->post_statusesUpdate(array('status' => $statusText, 'in_reply_to_status_id' => "{$resp->id}"));
    $this->assertEquals($resp->text, $statusText, 'The status with reply to id was not updated correctly for __call');
  }

  function testRetweet()
  {
    srand((float) microtime() * 10000000);
    $input = array("hello", "matrix", "twitter", "internet", "textbook");
    $rand_key = array_rand($input);
    $term = $input[$rand_key];
    $statusText = 'This test is to check if the retweet functionality is working (time: ' . time() . ')';
    $resp = $this->twitterObj->post('/statuses/update.json', array('status' => $statusText));
    $this->assertEquals($resp->text, $statusText, 'The status was not updated correctly prior to checking retweet');
    // get a public status and retweet it
    $public = $this->twitterObj->get('/search.json', array('q' => $term));
    $resp = $this->twitterObj->post("/statuses/retweet/{$public->results[rand(0,5)]->id_str}.json");
    $this->assertEquals('RT', substr($resp->text, 0, 2), 'Retweet response text did not start with RT');
    // reply to it
    $statusText = 'This is a random reply to a retweet test with a replyto status id (reply to: ' . $resp->id . ')';
    $resp = $this->twitterObj->post('/statuses/update.json', array('status' => $statusText, 'in_reply_to_status_id' => "{$resp->id}"));
    $this->assertEquals($resp->text, $statusText, 'The status with reply to id was not updated correctly when replying to an update');
  }

  function testPostStatusUnicode()
  {
    $statusText = rand(0,1000) . ' Testing a random status with unicode בוקר טוב áéíóúção (' . time() . ')';
    $resp = $this->twitterObj->post('/statuses/update.json', array('status' => $statusText));
    $this->assertEquals($resp->text, $statusText, 'The status was not updated correctly');
    // __call
    $statusText = rand(0,1000) . ' Testing a random status with unicode בוקר טוב áéíóúção (' . time() . ')';
    $resp = $this->twitterObj->post_statusesUpdate(array('status' => $statusText));
    $this->assertEquals($resp->text, $statusText, 'The status was not updated correctly');
  }

  function testDirectMessage()
  {
    $resp = $this->twitterObj->post('/direct_messages/new.json',  array ( 'user' => $this->screenName, 'text' => "@username that's dirt cheap man, good looking out. I shall buy soon.You still play Halo at all? " . rand(0,1000)));
    $this->assertTrue(!empty($resp->response['id']), "response id is empty");
    // __call
    $resp = $this->twitterObj->post_direct_messagesNew( array ( 'user' => $this->screenName, 'text' => "@username that's dirt cheap man, good looking out. I shall buy soon.You still play Halo at all? " . rand(0,1000)));
    $this->assertTrue(!empty($resp->response['id']), "response id is empty");
  }

  function testPassingInTokenParams()
  {
    $this->twitterObj->setToken(null, null);
    $token = $this->twitterObj->getRequestToken();
    $authenticateUrl = $this->twitterObj->getAuthorizationUrl($token);
    $this->assertEquals($token->oauth_token, substr($authenticateUrl, (strpos($authenticateUrl, '=')+1)), "token does not equal the one which was passed in");
  }

  function testResponseAccess()
  {
    $resp = $this->twitterObj->get('/statuses/followers.json');
    $this->assertTrue(!empty($resp[0]), 'array access for resp is empty');
    $this->assertEquals($resp[0], $resp->response[0], 'array access for resp is empty');
    foreach($resp as $k => $v)
    {
      $this->assertTrue(!empty($v->screen_name), 'screen name for one of the resp nodes is empty');
    }
    $this->assertTrue($k > 0, 'test did not properly loop over followers');
    // __call
    $resp = $this->twitterObj->get_statusesFollowers();
    $this->assertTrue(!empty($resp[0]), 'array access for resp is empty');
    $this->assertEquals($resp[0], $resp->response[0], 'array access for resp is empty');
    foreach($resp as $k => $v)
    {
      $this->assertTrue(!empty($v->screen_name), 'screen name for one of the resp nodes is empty');
    }
    $this->assertTrue($k > 0, 'test did not properly loop over followers');
  }

  function testSearch()
  {
    $resp = $this->twitterObj->get('/search.json', array('q' => 'hello'));
    $this->assertTrue(is_array($resp->response['results']));
    $this->assertTrue(!empty($resp->results[0]->text), "search response is not an array {$resp->results[0]->text}");
    $resp = $this->twitterObj->get('/search.json', array('geocode' => '40.757929,-73.985506,25km', 'rpp' => 10));
    $this->assertTrue(is_array($resp->response['results']));
    $this->assertTrue(!empty($resp->results[0]->text), "search response is not an array {$resp->results[0]->text}");
    // __call
    $resp = $this->twitterObj->get_search(array('q' => 'hello'));
    $this->assertTrue(is_array($resp->response['results']));
    $this->assertTrue(!empty($resp->results[0]->text), "search response is not an array {$resp->results[0]->text}");
    $resp = $this->twitterObj->get_search(array('geocode' => '40.757929,-73.985506,25km', 'rpp' => 10));
    $this->assertTrue(is_array($resp->response['results']));
    $this->assertTrue(!empty($resp->results[0]->text), "search response is not an array {$resp->results[0]->text}");
  }

  function testTrends()
  {
    $resp = $this->twitterObj->get('/trends.json');
    $this->assertTrue(is_array($resp->response['trends']), "trends is empty");
    $this->assertTrue(!empty($resp->trends[0]->name), "current trends is not an array " . $resp->trends[0]->name);

    $resp = $this->twitterObj->get('/trends/current.json');
    $this->assertTrue(is_array($resp->response['trends']), "current trends is empty");
    // __call
    $resp = $this->twitterObj->get_trends();
    $this->assertTrue(is_array($resp->response['trends']), "trends is empty");
    $this->assertTrue(!empty($resp->trends[0]->name), "current trends is not an array " . $resp->trends[0]->name);

    $resp = $this->twitterObj->get_trendsCurrent();
    $this->assertTrue(is_array($resp->response['trends']), "current trends is empty");
  }

  function testGetTrendsAvailable()
  {
    $trends = $this->twitterObj->get('/trends/available.json');
    $this->assertTrue($trends->response[0]['woeid'] > 0, 'woeid should be < 0');;
  }

  function testSSl()
  {
    $this->twitterObj->useSSL(true);
    $resp = $this->twitterObj->get('/account/verify_credentials.json');
    $this->assertTrue(strlen($resp->responseText) > 0, 'responseText was empty');
    $this->assertTrue($resp instanceof EpiTwitterJson, 'response is not an array');
    $this->assertTrue(!empty($resp->screen_name), 'member property screen_name is empty');
    $this->twitterObj->useSSL(false);
    // __call
    $this->twitterObj->useSSL(true);
    $resp = $this->twitterObj->get_accountVerify_credentials();
    $this->assertTrue(strlen($resp->responseText) > 0, 'responseText was empty');
    $this->assertTrue($resp instanceof EpiTwitterJson, 'response is not an array');
    $this->assertTrue(!empty($resp->screen_name), 'member property screen_name is empty');
    $this->twitterObj->useSSL(false);
  }

  function testCount()
  {
    $screenName = ucwords(strtolower($this->screenName));
    $resp = $this->twitterObj->get("/statuses/followers/{$screenName}.json");
    $this->assertTrue(count($resp) > 0, "Count for followers was not larger than 0");
    // __call
    $method = "get_statusesFollowers{$screenName}";
    $resp = $this->twitterObj->$method();
    $this->assertTrue(count($resp) > 0, "Count for followers was not larger than 0");
  }

  function testFavorites()
  {
    // get favs
    $resp = $this->twitterObj->get("/favorites/{$this->screenName}.json");
    $this->assertEquals(count($resp), 0, "Favorites should be length 0");
    // create fav
    $public = $this->twitterObj->get('/search.json', array('q' => 'hello'));
    $resp = $this->twitterObj->post("/favorites/create/{$public->results[0]->id_str}.json");
    $this->assertEquals($resp->id_str, $public->results[0]->id_str, "Created fav should have same id");
    // destroy fav
    $resp = $this->twitterObj->post("/favorites/destroy/{$public->results[0]->id_str}.json");
    $this->assertEquals($resp->id_str, $public->results[0]->id_str, "Destroy fav should have same id");
    $resp = $this->twitterObj->get("/favorites/{$this->screenName}.json");
    $this->assertEquals(count($resp), 0, "Favorites should be length 0 after destroying");
    
    // clean up
    $resp = $this->twitterObj->get("/favorites/{$this->screenName}.json");
    foreach($resp as $r)
    {
      $del = $this->twitterObj->post("/favorites/destroy/{$r->id_str}.json");
      $del->response;
    }
  }

  function testUpdateAvatar()
  {
    $file = dirname(__FILE__) . '/avatar_test_image.jpg';
    $resp = $this->twitterObj->post('/account/update_profile_image.json', array('@image' => "@{$file}"));
    // api seems to be a bit behind and doesn't respond with the new image url - use code instead for now
    $this->assertEquals($resp->code, 200, 'Response code was not 200');

    // __call
    $file = dirname(__FILE__) . '/avatar_test_image.jpg';
    $resp = $this->twitterObj->post_accountUpdate_profile_image(array('@image' => "@{$file}"));
    // api seems to be a bit behind and doesn't respond with the new image url - use code instead for now
    $this->assertEquals($resp->code, 200, 'Response code was not 200');
  }

  function testUpdateBackground()
  {
    $file = dirname(__FILE__) . '/avatar_test_image.jpg';
    $resp = $this->twitterObj->post('/account/update_profile_background_image.json', array('@image' => "@{$file}", 'tile' => 'true'));
    // api seems to be a bit behind and doesn't respond with the new image url - use code instead for now
    $this->assertEquals($resp->code, 200, 'Response code was not 200');

    // __call
    $file = dirname(__FILE__) . '/avatar_test_image.jpg';
    $resp = $this->twitterObj->post_accountUpdate_profile_background_image(array('@image' => "@{$file}"));
    // api seems to be a bit behind and doesn't respond with the new image url - use code instead for now
    $this->assertEquals($resp->code, 200, 'Response code was not 200');
  }

  function testCreateFriendship()
  {
    // check if friendship exists
    $exists = $this->twitterObj->get('/friendships/exists.json', array('user_a' => $this->screenName, 'user_b' => 'pbct_test'));
    if($exists->response)
    {
      $destroy = $this->twitterObj->post('/friendships/destroy.json', array('id' => 'pbct_test'));
      $destroy->responseText;
    }

    // perform checks now that env is set up
    $exists = $this->twitterObj->get('/friendships/exists.json', array('user_a' => $this->screenName, 'user_b' => 'pbct_test'));
    $this->assertFalse($exists->response, 'Friendship already exists and should not for create test');
    $create = $this->twitterObj->post('/friendships/create.json', array('id' => 'pbct_test'));
    $this->assertTrue($create->id > 0, 'ID is empty from create friendship call');

    // __call
    // check if friendship exists
    $exists = $this->twitterObj->get_friendshipsExists(array('user_a' => $this->screenName, 'user_b' => 'pbct_test'));
    if($exists->response)
    {
      $destroy = $this->twitterObj->post_friendshipsDestroy(array('id' => 'pbct_test'));
      $destroy->responseText;
    }

    // perform checks now that env is set up
    $exists = $this->twitterObj->get_friendshipsExists(array('user_a' => $this->screenName, 'user_b' => 'pbct_test'));
    $this->assertFalse($exists->response, 'Friendship already exists and should not for create test');
    $create = $this->twitterObj->post_friendshipsCreate(array('id' => 'pbct_test'));
    $this->assertTrue($create->id_str > 0, 'ID is empty from create friendship call');
  }

  function testDestroyFriendship()
  {
    // check if friendship exists
    $exists = $this->twitterObj->get('/friendships/exists.json', array('user_a' => $this->screenName, 'user_b' => 'pbct_test'));
    if(!$exists->response)
    {
      $create = $this->twitterObj->post('/friendships/create.json', array('id' => 'pbct_test'));
      $create->responseText;
    }
    
    // perform checks now that env is set up
    $exists = $this->twitterObj->get('/friendships/exists.json', array('user_a' => $this->screenName, 'user_b' => 'pbct_test'));
    $this->assertTrue($exists->response, 'Friendship does not exist to be destroyed');
    $destroy = $this->twitterObj->post('/friendships/destroy.json', array('id' => 'pbct_test'));
    $this->assertTrue($destroy->id_str > 0, 'ID is empty from destroy friendship call');

    //__call
    // check if friendship exists
    $exists = $this->twitterObj->get_friendshipsExists(array('user_a' => $this->screenName, 'user_b' => 'pbct_test'));
    if(!$exists->response)
    {
      $create = $this->twitterObj->post_friendshipsCreate(array('id' => 'pbct_test'));
      $create->responseText;
    }
    
    // perform checks now that env is set up
    $exists = $this->twitterObj->get_friendshipsExists(array('user_a' => $this->screenName, 'user_b' => 'pbct_test'));
    $this->assertTrue($exists->response, 'Friendship does not exist to be destroyed');
    $destroy = $this->twitterObj->post_friendshipsDestroy(array('id' => 'pbct_test'));
    $this->assertTrue($destroy->id_str > 0, 'ID is empty from destroy friendship call');
  }

  function testGetFriendsIds()
  {
    $twitterFriends = $this->twitterObj->get('/friends/ids.json', array('screen_name' => $this->screenName));
    $this->assertTrue(count($twitterFriends->response) > 0, 'Count of get friend ids is 0');;
    $this->assertTrue(!empty($twitterFriends[0]), 'First result in get friend ids is empty');;

    $twitterFriends = $this->twitterObj->get('/friends/ids.json', array('user_id' => $this->id));
    $this->assertTrue(count($twitterFriends->response) > 0, 'Count of get friend ids is 0');;
    $this->assertTrue(!empty($twitterFriends[0]), 'First result in get friend ids is empty');;

    // __call
    $twitterFriends = $this->twitterObj->get_friendsIds(array('screen_name' => $this->screenName));
    $this->assertTrue(count($twitterFriends->response) > 0, 'Count of get friend ids is 0');;
    $this->assertTrue(!empty($twitterFriends[0]), 'First result in get friend ids is empty');;

    $twitterFriends = $this->twitterObj->get_friendsIds(array('user_id' => $this->id));
    $this->assertTrue(count($twitterFriends->response) > 0, 'Count of get friend ids is 0');;
    $this->assertTrue(!empty($twitterFriends[0]), 'First result in get friend ids is empty');;
  }

  function testGetStatusesFriends()
  {
    $twitterFriends = $this->twitterObj->get('/statuses/friends.json', array('screen_name' => $this->screenName));
    $this->assertTrue(count($twitterFriends->response) > 0, 'Count of get statuses friends is 0');;
    $this->assertTrue(!empty($twitterFriends[0]), 'First result in get statuses friends is empty');;

    $twitterFriends = $this->twitterObj->get('/statuses/friends.json', array('user_id' => $this->id));
    $this->assertTrue(count($twitterFriends->response) > 0, 'Count of get statuses friends is 0');;
    $this->assertTrue(!empty($twitterFriends[0]), 'First result in get statuses friends is empty');;

    // __call
    $twitterFriends = $this->twitterObj->get_statusesFriends(array('screen_name' => $this->screenName));
    $this->assertTrue(count($twitterFriends->response) > 0, 'Count of get statuses friends is 0');;
    $this->assertTrue(!empty($twitterFriends[0]), 'First result in get statuses friends is empty');;

    $twitterFriends = $this->twitterObj->get_statusesFriends(array('user_id' => $this->id));
    $this->assertTrue(count($twitterFriends->response) > 0, 'Count of get statuses friends is 0');;
    $this->assertTrue(!empty($twitterFriends[0]), 'First result in get statuses friends is empty');;
  }

  /*function testCreateAndDeleteList()
  {
    // create the list
    $name = 'test list ' . rand(0,1000);
    $resp = $this->twitterObj->post("/{$this->twitterUsername}/lists.json", array('name' => $name, 'mode' => 'public', 'description' => "List {$name}"));
    $this->assertEquals($resp->name, $name, "List name is not {$name} but rather {$resp->list}");
    // delete the list
    $respDel = $this->twitterObj->delete("/{$this->twitterUsername}/lists/{$resp->id}.json");
    $this->assertEquals($respDel->id, $resp->id, "Deleted list id doesn't match the id of the list we just created");
    // verify the delete worked
    $respGet = $this->twitterObj->get("/{$this->twitterUsername}/lists/{$resp->id}.json");
    $this->assertEquals($respGet->code, '404', "Getting the previously deleted list should return 404 and not {$respGet->code}");
  }*/

  function testDestructor()
  {
    $status = 'Testing destructor ' . time();
    $resp1 = $this->twitterObj->post_statusesUpdate(array('status' => $status));
    unset($resp1);
    $resp2 = $this->twitterObj->get_accountVerify_credentials();
    $this->assertEquals($status, $resp2->status->text, 'The destructor did not ensure that the status was updated');
  }

  function testHeaders()
  {
    $resp = $this->twitterObj->get_statusesFollowers();
    $this->assertTrue(!empty($resp->headers['Status']), 'header status response should not be empty');
  }

  /**
  * @expectedException EpiTwitterForbiddenException
  */
  function testNoRequiredParameter()
  {
    $resp = $this->twitterObj->post_direct_messagesNew( array ( 'user' => $this->screenName, 'text' => ''));
    $resp->response;
  }

  /**
  * @expectedException EpiTwitterNotAuthorizedException
  */
  function testBadCredentials()
  {
    $resp = $this->twitterObjBadAuth->post_direct_messagesNew( array ( 'user' => $this->screenName, 'text' => 'hello world'));
    $resp->response;
  }

  /**
  * @expectedException EpiTwitterNotFoundException
  */
  function testNonExistantUser()
  {
    $resp = $this->twitterObj->post_direct_messagesNew( array ( 'user' => 'jaisen_does_not_exist_and_dont_create_or_this_will_break', 'text' => 'seriously'));
    $resp->response;
  }
}
