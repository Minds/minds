<?php
/*
 *  Class to integrate with Twitter's API.
 *    Authenticated calls are done using OAuth and require access tokens for a user.
 *    API calls which do not require authentication do not require tokens (i.e. search/trends)
 * 
 *  Full documentation available on github
 *    http://wiki.github.com/jmathai/twitter-async
 * 
 *  @author Jaisen Mathai <jaisen@jmathai.com>
 */
class EpiTwitter extends EpiOAuth
{
  const EPITWITTER_SIGNATURE_METHOD = 'HMAC-SHA1';
  const EPITWITTER_AUTH_OAUTH = 'oauth';
  const EPITWITTER_AUTH_BASIC = 'basic';
  protected $requestTokenUrl= 'https://api.twitter.com/oauth/request_token';
  protected $accessTokenUrl = 'https://api.twitter.com/oauth/access_token';
  protected $authorizeUrl   = 'https://api.twitter.com/oauth/authorize';
  protected $authenticateUrl= 'https://api.twitter.com/oauth/authorize';
  protected $apiUrl         = 'http://api.twitter.com';
  protected $apiVersionedUrl= 'http://api.twitter.com';
  protected $searchUrl      = 'http://search.twitter.com';
  protected $userAgent      = 'EpiTwitter (http://github.com/jmathai/twitter-async/tree/)';
  protected $apiVersion     = '1.1';
  protected $isAsynchronous = false;

  /* OAuth methods */
  public function delete($endpoint, $params = null)
  {
    return $this->request('DELETE', $endpoint, $params);
  }

  public function get($endpoint, $params = null)
  {
    return $this->request('GET', $endpoint, $params);
  }

  public function post($endpoint, $params = null)
  {
    return $this->request('POST', $endpoint, $params);
  }

  /* Basic auth methods */
  public function delete_basic($endpoint, $params = null, $username = null, $password = null)
  {
    return $this->request_basic('DELETE', $endpoint, $params, $username, $password);
  }

  public function get_basic($endpoint, $params = null, $username = null, $password = null)
  {
    return $this->request_basic('GET', $endpoint, $params, $username, $password);
  }

  public function post_basic($endpoint, $params = null, $username = null, $password = null)
  {
    return $this->request_basic('POST', $endpoint, $params, $username, $password);
  }

  public function useApiVersion($version = null)
  {
    $this->apiVersion = $version;
  }

  public function useAsynchronous($async = true)
  {
    $this->isAsynchronous = (bool)$async;
  }

  public function __construct($consumerKey = null, $consumerSecret = null, $oauthToken = null, $oauthTokenSecret = null)
  {
    parent::__construct($consumerKey, $consumerSecret, self::EPITWITTER_SIGNATURE_METHOD);
    $this->setToken($oauthToken, $oauthTokenSecret);
  }

  public function __call($name, $params = null/*, $username, $password*/)
  {
    $parts  = explode('_', $name);
    $method = strtoupper(array_shift($parts));
    $parts  = implode('_', $parts);
    $endpoint   = '/' . preg_replace('/[A-Z]|[0-9]+/e', "'/'.strtolower('\\0')", $parts) . '.json';
    /* HACK: this is required for list support that starts with a user id */
    $endpoint = str_replace('//','/',$endpoint);
    $args = !empty($params) ? array_shift($params) : null;

    // calls which do not have a consumerKey are assumed to not require authentication
    if($this->consumerKey === null)
    {
      $username = null;
      $password = null;

      if(!empty($params))
      {
        $username = array_shift($params);
        $password = !empty($params) ? array_shift($params) : null;
      }

      return $this->request_basic($method, $endpoint, $args, $username, $password);
    }

    return $this->request($method, $endpoint, $args);
  }

  private function getApiUrl($endpoint)
  {
    if(preg_match('@^/search[./]?(?=(json|daily|current|weekly))@', $endpoint))
      return "{$this->searchUrl}{$endpoint}";
    elseif(!empty($this->apiVersion))
      return "{$this->apiVersionedUrl}/{$this->apiVersion}{$endpoint}";
    else
      return "{$this->apiUrl}{$endpoint}";
  }

  private function request($method, $endpoint, $params = null)
  {
    $url = $this->getUrl($this->getApiUrl($endpoint));
    $resp= new EpiTwitterJson(call_user_func(array($this, 'httpRequest'), $method, $url, $params, $this->isMultipart($params)), $this->debug);
    if(!$this->isAsynchronous)
      $resp->response;

    return $resp;
  }

  private function request_basic($method, $endpoint, $params = null, $username = null, $password = null)
  {
    $url = $this->getApiUrl($endpoint);
    if($method === 'GET')
      $url .= is_null($params) ? '' : '?'.http_build_query($params, '', '&');
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $this->requestTimeout);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    if($method === 'POST' && $params !== null)
    {
      if($this->isMultipart($params))
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
      else
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->buildHttpQueryRaw($params));
    }
    if(!empty($username) && !empty($password))
      curl_setopt($ch, CURLOPT_USERPWD, "{$username}:{$password}");

    $resp = new EpiTwitterJson(EpiCurl::getInstance()->addCurl($ch), $this->debug);
    if(!$this->isAsynchronous)
      $resp->response;

    return $resp;
  }
}

class EpiTwitterJson implements ArrayAccess, Countable, IteratorAggregate
{
  private $debug;
  private $__resp;
  public function __construct($response, $debug = false)
  {
    $this->__resp = $response;
    $this->debug  = $debug;
  }

  // ensure that calls complete by blocking for results, NOOP if already returned
  public function __destruct()
  {
    $this->responseText;
  }

  // Implementation of the IteratorAggregate::getIterator() to support foreach ($this as $...)
  public function getIterator ()
  {
    if ($this->__obj) {
      return new ArrayIterator($this->__obj);
    } else {
      return new ArrayIterator($this->response);
    }
  }

  // Implementation of Countable::count() to support count($this)
  public function count ()
  {
    return count($this->response);
  }
  
  // Next four functions are to support ArrayAccess interface
  // 1
  public function offsetSet($offset, $value) 
  {
    $this->response[$offset] = $value;
  }

  // 2
  public function offsetExists($offset) 
  {
    return isset($this->response[$offset]);
  }
  
  // 3
  public function offsetUnset($offset) 
  {
    unset($this->response[$offset]);
  }

  // 4
  public function offsetGet($offset) 
  {
    return isset($this->response[$offset]) ? $this->response[$offset] : null;
  }

  public function __get($name)
  {
    $accessible = array('responseText'=>1,'headers'=>1,'code'=>1);
    $this->responseText = $this->__resp->data;
    $this->headers      = $this->__resp->headers;
    $this->code         = $this->__resp->code;
    if(isset($accessible[$name]) && $accessible[$name])
      return $this->$name;
    elseif(($this->code < 200 || $this->code >= 400) && !isset($accessible[$name]))
      EpiTwitterException::raise($this->__resp, $this->debug);

    // Call appears ok so we can fill in the response
    $this->response     = json_decode($this->responseText, 1);
    $this->__obj        = json_decode($this->responseText);

    if(gettype($this->__obj) === 'object')
    {
      foreach($this->__obj as $k => $v)
      {
        $this->$k = $v;
      }
    }

    if (property_exists($this, $name)) {
      return $this->$name;
    }
    return null;
  }

  public function __isset($name)
  {
    $value = self::__get($name);
    return !empty($name);
  }
}

class EpiTwitterException extends Exception 
{
  public static function raise($response, $debug)
  {
    $message = $response->data;
    switch($response->code)
    {
      case 400:
        throw new EpiTwitterBadRequestException($message, $response->code);
      case 401:
        throw new EpiTwitterNotAuthorizedException($message, $response->code);
      case 403:
        throw new EpiTwitterForbiddenException($message, $response->code);
      case 404:
        throw new EpiTwitterNotFoundException($message, $response->code);
      case 406:
        throw new EpiTwitterNotAcceptableException($message, $response->code);
      case 420:
        throw new EpiTwitterEnhanceYourCalmException($message, $response->code);
      case 500:
        throw new EpiTwitterInternalServerException($message, $response->code);
      case 502:
        throw new EpiTwitterBadGatewayException($message, $response->code);
      case 503:
        throw new EpiTwitterServiceUnavailableException($message, $response->code);
      default:
        throw new EpiTwitterException($message, $response->code);
    }
  }
}
class EpiTwitterBadRequestException extends EpiTwitterException{}
class EpiTwitterNotAuthorizedException extends EpiTwitterException{}
class EpiTwitterForbiddenException extends EpiTwitterException{}
class EpiTwitterNotFoundException extends EpiTwitterException{}
class EpiTwitterNotAcceptableException extends EpiTwitterException{}
class EpiTwitterEnhanceYourCalmException extends EpiTwitterException{}
class EpiTwitterInternalServerException extends EpiTwitterException{}
class EpiTwitterBadGatewayException extends EpiTwitterException{}
class EpiTwitterServiceUnavailableException extends EpiTwitterException{}
