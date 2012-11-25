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

class YahooCurl
{

  const VERSION = '1.0.0';

  const GET    = 'GET';
  const PUT    = 'PUT';
  const POST   = 'POST';
  const DELETE = 'DELETE';
  const HEAD   = 'HEAD';


  /**
   * Fetches an HTTP resource
   *
   * @param string $url    The request url
   * @param string $params The request parameters
   * @param string $header The request http headers
   * @param string $method The request HTTP method (GET, POST, PUT, DELETE, HEAD)
   * @param string $post   The request body
   *
   * @return string Response body from the server
   */
  public static function fetch($url, $params, $headers = array(), $method = self::GET, $post = null, $options = array())
  {
    $options = array_merge(array(
      'timeout'         => '10',
      'connect_timeout' => '10',
      'compression'     => true,
      'debug'           => true,
      'log'             => sys_get_temp_dir().'/curl_debug.log',
    ), $options);

    if(!empty($params))
    {
      $url = $url.'?'.http_build_query($params);
    }

    $ch = curl_init($url);

    // return headers
    curl_setopt($ch, CURLOPT_HEADER, true);

    // return body
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // set headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // merge headers $request_headers["Content-Type"], "application/x-www-form-urlencoded"

    // handle http methods
    switch($method) {
      case 'POST':
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      break;

      case 'PUT':
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      break;

      case 'HEAD':
      case 'DELETE':
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      break;
    }

    // set user agent
    curl_setopt($ch, CURLOPT_USERAGENT, sprintf('yos-social-php (%s) / PHP (%s)', self::VERSION, phpversion()));

    // handle redirects
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // handle http compression
    curl_setopt($ch, CURLOPT_ENCODING, '');

    // timeouts
    curl_setopt($ch, CURLOPT_TIMEOUT, $options['timeout']);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $options['timeout']);

    // be nice to dev ssl certs
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FAILONERROR, false);

    // debug curl options
    if($options['debug'])
    {
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_VERBOSE, true);
    }

    $http = array();
    $response = curl_exec($ch);

    if($response === false)
    {
      // something bad happeneed (timeout, dns failure, ???)
      error_log(curl_error($ch));
      return false;
    }

    // process response
    $http = array_merge($http, curl_getinfo($ch));

    // process status code + headers
    list($http['response_header'], $http['response_body']) = explode("\r\n\r\n", $response, 2);
    $response_header_lines = explode("\r\n", $http['response_header']);
    $http_response_line = array_shift($response_header_lines);
    if (preg_match('@^HTTP/[0-9]\.[0-9] ([0-9]{3})@', $http_response_line, $matches)) {
      $http['response_code'] = $matches[1];
    }
    $http['response_headers'] = array();
    foreach ($response_header_lines as $header_line) {
        list($header, $value) = explode(': ', $header_line, 2);
        $http['response_headers'][$header] = $value;
    }

    if($options['debug'])
    {
      // debug info
      error_log(sprintf('Fetching %s, method=%s, headers=%s, response=%s', $url, $method, var_export($headers, true), var_export($http, true)));
    }

    curl_close($ch);

    return $http;
  }

}