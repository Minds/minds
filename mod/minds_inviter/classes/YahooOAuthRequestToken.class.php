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

class YahooOAuthRequestToken extends OAuthToken
{
  /*
  RequestToken is a data type that represents an end user via a request token.

  key -- the token
  secret -- the token secret
  expires_in -- authorization expiration from issue
  request_auth_url -- request token authorization url
  */
  public $key                      = null,
         $secret                   = null,
         $expires_in               = null,
         $request_auth_url         = null,
         $oauth_problem            = null;

  public function __construct($key, $secret, $expires_in=null, $request_auth_url=null, $oauth_problem=null)
  {
    $this->key              = $key;
    $this->secret           = $secret;
    $this->expires_in       = $expires_in;
    $this->request_auth_url = $request_auth_url;
    $this->oauth_problem    = $oauth_problem;
  }

  public function to_string()
  {
    return http_build_query(array('oauth_token' => $this->key,
                                  'oauth_token_secret' => $this->secret,
                                  'oauth_expires_in' => $this->expires_in,
                                  'xoauth_request_auth_url' => $this->request_auth_url,
                                  'oauth_problem' => $this->oauth_problem
                             ));
  }

  public static function from_string($token)
  {
    /*
    Returns a token from something like: oauth_token_secret=xxx&oauth_token=xxx
    */
    parse_str(trim($token), $params);

    $key              = isset($params['oauth_token']) ? $params['oauth_token'] : null;
    $secret           = isset($params['oauth_token_secret']) ? $params['oauth_token_secret'] : null;
    $expires_in       = isset($params['oauth_expires_in']) ? $params['oauth_expires_in'] : null;
    $request_auth_url = isset($params['xoauth_request_auth_url']) ? $params['xoauth_request_auth_url'] : null;
    $oauth_problem    = isset($params['oauth_problem']) ? $params['oauth_problem'] : null;

    return new self($key, $secret, $expires_in, $request_auth_url, $oauth_problem);
  }
}