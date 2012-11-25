<?php

/**
 * Yahoo! PHP5 SDK
 *
 *  * Yahoo! Query Language
 *  * Yahoo! Social API
 *
 * Find documentation and support on Yahoo! Developer Network: http://developer.yahoo.com
 *
 * Hosted on GitHub: http://github.com/yahoo/yos-social-php5/tree/master
 *
 * @package    yos-social-php5
 * @subpackage yahoo
 *
 * @author     Dustin Whittle <dustin@yahoo-inc.com>
 * @author     Zach Graves <zachg@yahoo-inc.com>
 * @copyright  Copyrights for code authored by Yahoo! Inc. is licensed under the following terms:
 * @license    BSD Open Source License
 *
 *   Permission is hereby granted, free of charge, to any person obtaining a copy
 *   of this software and associated documentation files (the "Software"), to deal
 *   in the Software without restriction, including without limitation the rights
 *   to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *   copies of the Software, and to permit persons to whom the Software is
 *   furnished to do so, subject to the following conditions:
 *
 *   The above copyright notice and this permission notice shall be included in
 *   all copies or substantial portions of the Software.
 *
 *   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *   IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *   AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *   LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *   OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *   THE SOFTWARE.
 **/

require_once 'YahooCurl.class.php';
require_once 'YahooYQLQuery.class.php';
require_once 'YahooOAuthApplicationException.class.php';
require_once 'YahooOAuthAccessToken.class.php';
require_once 'YahooOAuthRequestToken.class.php';
require_once 'YahooOAuthClient.class.php';


class YahooOAuthApplication
{
  public function __construct($consumer_key, $consumer_secret, $application_id, $callback_url = null, $token = null, $options = array(), $client = null)
  {
    $this->client = is_null($client) ? new YahooOAuthClient() : $client;

    $this->consumer_key               = $consumer_key;
    $this->consumer_secret            = $consumer_secret;
    $this->application_id             = $application_id;
    $this->callback_url               = $callback_url;
    $this->token                      = $token;
    $this->options                    = $options;

    $this->consumer                   = new OAuthConsumer($this->consumer_key, $this->consumer_secret);
    $this->signature_method_plaintext = new OAuthSignatureMethod_PLAINTEXT();
    $this->signature_method_hmac_sha1 = new OAuthSignatureMethod_HMAC_SHA1();
  }
  
  public function getGUID()
  {
     if($this->token) {
        return $this->token->yahoo_guid;  
     }
  }

  public function getOpenIDUrl($return_to = false, $lang = 'en', $openIdEndpoint = 'https://open.login.yahooapis.com/openid/op/auth')
  {
    $openid_request = array(
      'openid.ns'                => 'http://specs.openid.net/auth/2.0',
      'openid.claimed_id'        => 'http://specs.openid.net/auth/2.0/identifier_select',
      'openid.identity'          => 'http://specs.openid.net/auth/2.0/identifier_select',
      'openid.realm'             =>  $this->callback_url,
      'openid.ui.mode'           => 'popup',
      'openid.return_to'         =>  $return_to,
      'openid.mode'              => 'checkid_setup',
      'openid.assoc_handle'      => session_id(),
      'openid.ns.ui'             => 'http://specs.openid.net/extensions/ui/1.0',
      'openid.ui.icon'           => 'true',
      'openid.ui.language'       =>  $lang,
      'openid.ns.ext1'           => 'http://openid.net/srv/ax/1.0',
      'openid.ext1.mode'         => 'fetch_request',
      'openid.ext1.type.email'   => 'http://axschema.org/contact/email',
      'openid.ext1.type.first'   => 'http://axschema.org/namePerson/first',
      'openid.ext1.type.last'    => 'http://axschema.org/namePerson/last',
      'openid.ext1.type.country' => 'http://axschema.org/contact/country/home',
      'openid.ext1.type.lang'    => 'http://axschema.org/pref/language',
      'openid.ext1.required'     => 'email,first,last,country,lang',
      'openid.ns.oauth'          => 'http://specs.openid.net/extensions/oauth/1.0',
      'openid.oauth.consumer'    => $this->consumer_key,
      'openid.oauth.scope'       => '',
      'xopenid_lang_pref'        => $lang,
   );

    return $openIdEndpoint.'?'.http_build_query($openid_request);
  }

  public function getRequestToken($callback = "oob")
  {
    $parameters = array('xoauth_lang_pref' => 'en', 'oauth_callback' => $callback);
    $oauth_request = OAuthRequest::from_consumer_and_token($this->consumer, null, 'GET', YahooOAuthClient::REQUEST_TOKEN_API_URL, $parameters);
    $oauth_request->sign_request($this->signature_method_hmac_sha1, $this->consumer, null);
    return $this->client->fetch_request_token($oauth_request);
  }

  public function getAuthorizationUrl($oauth_request_token)
  {
    // $oauth_request = OAuthRequest::from_consumer_and_token($this->consumer, $oauth_request_token, 'GET', YahooOAuthClient::AUTHORIZATION_API_URL);
    // $oauth_request->sign_request($this->signature_method_hmac_sha1, $this->consumer, $oauth_request_token);
    // return $oauth_request->to_url();

    if(isset($oauth_request_token->request_auth_url) && !empty($oauth_request_token->request_auth_url))
    {
       $auth_url = $oauth_request_token->request_auth_url;
    }
    else
    {
       $auth_url = sprintf("%s?oauth_token=%s", YahooOAuthClient::AUTHORIZATION_API_URL, $oauth_request_token->key);
    }

    return $auth_url;
  }

  public function getAccessToken($oauth_request_token, $verifier = null)
  {
    if ($verifier == null)
    {
      $parameters = array();
    }
    else
    {
      $parameters = array('oauth_verifier' => $verifier);
    }

    if(isset($oauth_request_token->session_handle) && !empty($oauth_request_token->session_handle))
    {
       $parameters["oauth_session_handle"] = $oauth_request_token->session_handle;
    }

    $oauth_request = OAuthRequest::from_consumer_and_token($this->consumer, $oauth_request_token, 'GET', YahooOAuthClient::ACCESS_TOKEN_API_URL, $parameters);
    $oauth_request->sign_request($this->signature_method_hmac_sha1, $this->consumer, $oauth_request_token);
    $this->token = $this->client->fetch_access_token($oauth_request);

    return $this->token;
  }

  public function refreshAccessToken($oauth_access_token)
  {
    $parameters = array('oauth_session_handle' => $oauth_access_token->session_handle);
    $oauth_request = OAuthRequest::from_consumer_and_token($this->consumer, $oauth_access_token, 'GET', YahooOAuthClient::ACCESS_TOKEN_API_URL, $parameters);
    $oauth_request->sign_request($this->signature_method_hmac_sha1, $this->consumer, $oauth_access_token);
    $this->token = $this->client->fetch_access_token($oauth_request);

    return $this->token;
  }

  public static function fromYAP($consumer_key, $consumer_secret, $application_id)
  {
    $is_canvas = (isset($_POST['yap_appid']) && isset($_POST['yap_view']) && isset($_POST['oauth_signature']));
    if($is_canvas === false) {
       throw new YahooOAuthApplicationException('YAP application environment not found in request.');
    }

    $yap_consumer_key = $_POST['yap_consumer_key'];
    if($consumer_key != $yap_consumer_key) {
       throw new YahooOAuthApplicationException(sprintf('Provided consumer key does not match yap_consumer_key: (%s)', $yap_consumer_key));
    }

    $consumer    = new OAuthConsumer($consumer_key, $consumer_secret);
    $token       = new YahooOAuthAccessToken($_POST['yap_viewer_access_token'], $_POST['yap_viewer_access_token_secret'], null, null, null, $_POST['yap_viewer_guid']);
    $application = new YahooOAuthApplication($consumer->key, $consumer->secret, $application_id, null, $token);

    $signature_valid = $application->signature_method_hmac_sha1->check_signature(OAuthRequest::from_request(), $consumer, $token, $_POST['oauth_signature']);
    if($signature_valid === false) {
       // temporary fix to allow newer versions of OAuth.php to work with YAP.
       // return false;
    }

    return $application;
  }

  public function getIdentity($yid)
  {
    $rsp = $this->yql(sprintf('SELECT * FROM yahoo.identity where yid="%s"', $yid));
    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function getProfile($guid = null)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }
    
    $rsp = $this->yql(sprintf('SELECT * FROM social.profile where guid="%s"', $guid));

    return isset($rsp->query->results) ? $rsp->query->results : false;
  }
  
  public function getProfileImages($guid = null, $size = null) 
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }
    
    if($size) {
	  $query = sprintf('SELECT * FROM social.profile.image WHERE guid="%s" and size="%s"', $guid, $size);
    } else {
	  $query = sprintf('SELECT * FROM social.profile.image WHERE guid="%s"', $guid);
    }

    $rsp = $this->yql($query);

    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function getStatus($guid = null)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }
    
    $rsp = $this->yql(sprintf('SELECT * FROM social.profile.status WHERE guid="%s"', $guid));
	
    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function setStatus($guid = null, $status)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }
    
    $rsp = $this->yql(sprintf('UPDATE social.profile.status SET status="%s" WHERE guid="%s"', $status, $guid), array(), YahooCurl::PUT);
    
    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function getConnections($guid = null, $offset = 0, $limit = 10)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }
    
    $rsp = $this->yql(sprintf('SELECT * FROM social.connections(%s,%s) WHERE owner_guid="%s"', $offset, $limit, $guid));
    
    return isset($rsp->query->results) ? $rsp->query->results : false;
  }
  
  public function getRelationships($guid = null, $offset = 0, $limit = 10)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }
    
    $rsp = $this->yql(sprintf('SELECT * FROM social.relationships(%s,%s) WHERE owner_guid="%s"', $offset, $limit, $guid));
    
    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function getContacts($guid = null, $offset = 0, $limit = 10)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }
    
    $rsp = $this->yql(sprintf('SELECT * FROM social.contacts(%s,%s) WHERE guid="%s"', $offset, $limit, $guid));
    
    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function getContact($guid = NULL, $cid)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }

    $rsp = $this->yql(sprintf('SELECT * from social.contacts WHERE guid="%s" AND contact_id="%s";', $guid, $cid));
    
    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function getContactSync($guid = null, $rev = 0)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }
    
    $rsp = $this->yql(sprintf('SELECT * from social.contacts.sync WHERE guid="%s" AND rev="%s";', $guid, $rev));
    
    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function syncContacts($guid = null, $contactsync)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }

    $url = sprintf(YahooOAuthClient::SOCIAL_API_URL.'/user/%s/contacts', $guid);
    $parameters = array('format' => 'json');

    $data = array('contactsync' => $contactsync);
    $body = json_encode($data);

    $oauth_request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, 'PUT', $url, $parameters);
    $oauth_request->sign_request($this->signature_method_hmac_sha1, $this->consumer, $this->token);

    $http = YahooCurl::fetch($oauth_request->to_url(), array(), array('Content-Type: application/json', 'Accept: *'), $oauth_request->get_normalized_http_method(), $body);

    return $http['response_body'];
  }
  
  public function addSimpleContact($guid = null, $givenName, $familyName, $email, $nickname) 
  {
     if($guid == null && !is_null($this->token))
     {
       $guid = $this->token->yahoo_guid;
     }
     
     $query = sprintf('INSERT INTO social.contacts (owner_guid, givenName, familyName, email, nickname) VALUES ("%s", "%s", "%s", "%s", "%s")', $guid, $givenName, $familyName, $email, $nickname);
     $rsp = $this->yql($query, array(), YahooCurl::PUT);

     return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function addContact($guid = null, $contact)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }

    $url = sprintf(YahooOAuthClient::SOCIAL_API_URL.'/user/%s/contacts', $guid);
    $parameters = array('format' => 'json');

    $data = array('contact' => $contact);
    $body = json_encode($data);

    $oauth_request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, 'POST', $url, $parameters);
    $oauth_request->sign_request($this->signature_method_hmac_sha1, $this->consumer, $this->token);

    $http = YahooCurl::fetch($oauth_request->to_url(), array(), array('Content-Type: application/json', 'Accept: *'), $oauth_request->get_normalized_http_method(), $body);

    return $http['response_body'];
  }

  public function getConnectionUpdates($guid = null, $offset = 0, $limit = 10)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }

    $rsp = $this->yql(sprintf('SELECT * FROM social.connections.updates(%s, %s) WHERE guid="%s"', $offset, $limit, $guid));

    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function getUpdates($guid = null, $offset = 0, $limit = 10)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }
    
    $rsp = $this->yql(sprintf('SELECT * FROM social.updates(%s, %s) WHERE guid="%s"', $offset, $limit, $guid));
    
    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function insertUpdate($params)
  {
     $guid = $this->token->yahoo_guid;
     
     $defaults = array(
        'collectionID' => $guid,
        'collectionType' => 'guid',
        'class' => 'app',
        'suid' => uniqid(mt_rand()),
        'pubDate' => (string)time(),
        'source' => 'APP.'.$this->application_id,
        'type' => 'appActivity3',
        'link' => ''
     );
     
     $update = array_merge($defaults, $params);
     $body = array('updates' => array($update));
     
     $url = sprintf("http://social.yahooapis.com/v1/user/%s/updates/%s/%s", $update['collectionID'], $update['source'], $update['suid']);
     
     $oauth_request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, YahooCurl::PUT, $url);
     $oauth_request->sign_request($this->signature_method_hmac_sha1, $this->consumer, $this->token);
     
     $parameters = json_encode($body);
     $headers = array('Content-Type: application/json', 'Accept: application/json', $oauth_request->to_header());
     
     $http = YahooCurl::fetch($oauth_request->get_normalized_http_url(), array(), $headers, $oauth_request->get_normalized_http_method(), $parameters);
     
     return ($http) ? json_decode($http['response_body']) : false;
  }

  public function getSocialGraph($guid = null, $offset = 0, $limit = 10)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }
    
    $query = sprintf('SELECT * FROM social.profile where guid in (SELECT guid from social.relationships (%s, %s) WHERE owner_guid="%s");', $offset, $limit, $guid);
    $rsp = $this->yql($query);

    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function getProfileLocation($guid = null)
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }
    
    $rsp = $this->yql(sprintf('SELECT * FROM geo.places WHERE text IN (SELECT location FROM social.profile WHERE guid="%s");', $guid));

    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function getGeoPlaces($location)
  {
    $rsp = $this->yql(sprintf('SELECT * FROM geo.places where text="%s"', $location));
    return isset($rsp->query->results) ? $rsp->query->results : false;
  }
  
  public function setSmallView($guid = null, $content) 
  {
    if($guid == null && !is_null($this->token))
    {
      $guid = $this->token->yahoo_guid;
    }
    
    $rsp = $this->yql(sprintf('UPDATE yap.setsmallview SET content="%s" where guid="%s" and ck="%s" and cks="%s";', 
		$content, $guid, $this->consumer->key, $this->consumer->secret), array(), YahooCurl::PUT);
    
    return isset($rsp->query->results) ? $rsp->query->results : false;
  }

  public function yql($query, $parameters = array(), $method = YahooCurl::GET)
  {
    if(is_array($query))
    {
      // handle multi queries
      $query = sprintf('SELECT * FROM query.multi WHERE queries="%s"', implode(';', str_replace('"', "'", $query)));
    }

    $parameters = array_merge(array('q' => $query, 'format' => 'json', 'env' => YahooYQLQuery::DATATABLES_URL), $parameters);

    $oauth_request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, YahooYQLQuery::OAUTH_API_URL, $parameters);
    $oauth_request->sign_request($this->signature_method_hmac_sha1, $this->consumer, $this->token);

    return json_decode($this->client->access_resource($oauth_request));
  }
}

function _yql_insert_quotes($value)
{
   return "'$value'";
}