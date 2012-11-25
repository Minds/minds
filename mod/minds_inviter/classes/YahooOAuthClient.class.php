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

class YahooOAuthClient
{

  # Yahoo! OAuth APIs
  const REQUEST_TOKEN_API_URL = 'https://api.login.yahoo.com/oauth/v2/get_request_token';
  const AUTHORIZATION_API_URL = 'https://api.login.yahoo.com/oauth/v2/request_auth';
  const ACCESS_TOKEN_API_URL  = 'https://api.login.yahoo.com/oauth/v2/get_token';
  const SOCIAL_API_URL        = 'http://social.yahooapis.com/v1';

  # http://developer.yahoo.com/oauth/guide/oauth-auth-flow.html

  public function __construct($request_token_url = self::REQUEST_TOKEN_API_URL, $access_token_url = self::ACCESS_TOKEN_API_URL, $authorization_url = self::AUTHORIZATION_API_URL)
  {
    $this->request_token_url = $request_token_url;
    $this->access_token_url  = $access_token_url;
    $this->authorization_url = $authorization_url;
  }

  public function fetch_request_token($oauth_request)
  {
    $http = YahooCurl::fetch($oauth_request->to_url(), array(), array(), $oauth_request->get_normalized_http_method());

    return YahooOAuthRequestToken::from_string($http['response_body']);
  }

  public function fetch_access_token($oauth_request)
  {
    $http = YahooCurl::fetch($oauth_request->to_url(), array(), array(), $oauth_request->get_normalized_http_method());

    return YahooOAuthAccessToken::from_string($http['response_body']);
  }

  public function authorize_token($oauth_request)
  {
    $http = YahooCurl::fetch($this->authorization_url, array(), array($oauth_request->to_header()), $oauth_request->get_normalized_http_method());

    return $http['response_body'];
  }

  public function access_resource($oauth_request)
  {

    if($oauth_request->get_normalized_http_method() == 'GET')
    {
      $http = YahooCurl::fetch($oauth_request->to_url(), array(), array(), $oauth_request->get_normalized_http_method());
    }
    else
    {
      $http = YahooCurl::fetch($oauth_request->to_url(), array(), array('Content-Type: application/x-www-form-urlencoded', 'Accept: *'), $oauth_request->get_normalized_http_method());
    }

    return $http['response_body'];
  }
}
