<?php
/**
 * Class for performing HTTP-requests.
 *
 * @package phpcrawl
 * @internal
 */
class PHPCrawlerHTTPRequest
{
  /**
   * The user-agent-string
   */
  public $userAgentString = "PHPCrawl";
  
  /**
   * Timeout-value for socket-connection
   */
  public $socketConnectTimeout = 5;
  
  /**
   * Socket-read-timeout
   */
  public $socketReadTimeout = 2;
  
  /**
   * Limit for content-size to receive
   *
   * @var int The kimit n bytes
   */
  protected $content_size_limit = 0;
  
  /**
   * Global counter for traffic this instance of the HTTPRequest-class caused.
   *
   * @vat int Traffic in bytes
   */
  protected $global_traffic_count = 0;
  
  /**
   * The time it took te receive data-packets for the request.
   *
   * @vat float time in seconds and milliseconds.
   */
  protected $data_transfer_time = 0;
  
  /**
   * Contains all rules defining the content-types that should be received
   *
   * @var array Numeric array conatining the regex-rules
   */
  protected $receive_content_types = array();
  
  /**
   * Contains all rules defining the content-types of pages/files that should be streamed directly to
   * a temporary file (instead of to memory)
   *
   * @var array Numeric array conatining the regex-rules
   */
  protected $receive_to_file_content_types = array();
  
  /**
   * Contains all rules defining the content-types defining which documents shoud get checked for links.
   *
   * @var array Numeric array conatining the regex-rules
   */
  protected $linksearch_content_types = array("#text/html# i");
  
  /**
   * The TMP-File to use when a page/file should be streamed to file.
   *
   * @var string
   */
  protected $tmpFile = "phpcrawl.tmp";
  
  /**
   * The URL for the request as PHPCrawlerURLDescriptor-object
   *
   * @var PHPCrawlerURLDescriptor
   */
  protected $UrlDescriptor;
  
  /**
   * The parts of the URL for the request as returned by PHPCrawlerUtils::splitURL()
   *
   * @var array
   */
  protected $url_parts = array();
  
  /**
   * DNS-cache
   *
   * @var PHPCrawlerDNSCache
   */
  public $DNSCache;
  
  /**
   * Link-finder object
   *
   * @var PHPCrawlerLinkFinder
   */
  protected $LinkFinder;
  
  /**
   * The last response-header this request-instance received.
   */
  protected $lastResponseHeader;
  
  /**
   * Array containing cookies to send with the request
   *
   * @array
   */
  protected $cookie_array = array();
  
  /**
   * Array containing POST-data to send with the request
   *
   * @var array
   */
  protected $post_data = array();
  
  /**
   * The proxy to use
   *
   * @var array Array containing the keys "proxy_host", "proxy_port", "proxy_username", "proxy_password".
   */
  protected $proxy;
  
  /**
   * The socket used for HTTP-requests
   */
  protected $socket;
  
  protected $header_check_callback_function = null;
  
  public function __construct()
  {
    // Init LinkFinder
    if (!class_exists("PHPCrawlerLinkFinder")) include_once(dirname(__FILE__)."/PHPCrawlerLinkFinder.class.php");
    $this->LinkFinder = new PHPCrawlerLinkFinder();
    
    // Init DNS-cache
    if (!class_exists("PHPCrawlerDNSCache")) include_once(dirname(__FILE__)."/PHPCrawlerDNSCache.class.php");
    $this->DNSCache = new PHPCrawlerDNSCache();
    
    // Cookie-Descriptor
    if (!class_exists("PHPCrawlerCookieDescriptor")) include_once(dirname(__FILE__)."/PHPCrawlerCookieDescriptor.class.php");
    
    // ResponseHeader-class
    if (!class_exists("PHPCrawlerResponseHeader")) include_once(dirname(__FILE__)."/PHPCrawlerResponseHeader.class.php");
  }
  
  /**
   * Sets the URL for the request.
   *
   * @param PHPCrawlerURLDescriptor $UrlDescriptor An PHPCrawlerURLDescriptor-object containing the URL to request
   */
  public function setUrl(PHPCrawlerURLDescriptor $UrlDescriptor)
  {
    $this->UrlDescriptor = $UrlDescriptor;
    
    // Split the URL into its parts
    $this->url_parts = PHPCrawlerUtils::splitURL($UrlDescriptor->url_rebuild);
  }
  
  /**
   * Adds a cookie to send with the request.
   *
   * @param string $name Cookie-name
   * @param string $value Cookie-value
   */
  public function addCookie($name, $value)
  {
    $this->cookie_array[$name] = $value;
  }
  
  /**
   * Adds a cookie to send with the request.
   *
   * @param PHPCrawlerCookieDescriptor $Cookie
   */
  public function addCookieDescriptor(PHPCrawlerCookieDescriptor $Cookie)
  {
    //var_dump($Cookie);
    $this->addCookie($Cookie->name, $Cookie->value);
  }
  
  /**
   * Adds a bunch of cookies to send with the request
   *
   * @param array $cookies Numeric array containins cookies as PHPCrawlerCookieDescriptor-objects
   */
  public function addCookieDescriptors($cookies)
  {
    $cnt = count($cookies);
    for ($x=0; $x<$cnt; $x++)
    {
      $this->addCookieDescriptor($cookies[$x]);
    }
  }
  
  /**
   * Removes all cookies to send with the request.
   */
  public function clearCookies()
  {
    $this->cookie_array = array();
  }
  
  /**
   * Sets the html-tags from which to extract/find links from.
   *
   * @param array $tag_array Numeric array containing the tags, i.g. array("href", "src", "url", ...)
   * @return bool
   */
  public function setLinkExtractionTags($tag_array)
  {
    if (!is_array($tag_array)) return false;
    
    $this->LinkFinder->extract_tags = $tag_array;
    return true;
  }
  
  /**
   * Specifies whether redirect-links set in http-headers should get searched for.
   *
   * @return bool
   */
  public function setFindRedirectURLs($mode)
  {
    if (!is_bool($mode)) return false;
    
    $this->LinkFinder->find_redirect_urls = $mode;
    
    return true;
  }
  
  /**
   * Adds post-data to send with the request.
   */
  public function addPostData($key, $value)
  {
    $this->post_data[$key] = $value;
  }
  
  /**
   * Removes all post-data to send with the request.
   */
  public function clearPostData()
  {
    $this->post_data = array();
  }
  
  public function setProxy($proxy_host, $proxy_port, $proxy_username = null, $proxy_password = null)
  {
    $this->proxy = array();
    $this->proxy["proxy_host"] = $proxy_host;
    $this->proxy["proxy_port"] = $proxy_port;
    $this->proxy["proxy_username"] = $proxy_username;
    $this->proxy["proxy_password"] = $proxy_password;
  }
  
  /**
   * Sets basic-authentication login-data for protected URLs.
   */
  public function setBasicAuthentication($username, $password)
  {
    $this->url_parts["auth_username"] = $username;
    $this->url_parts["auth_password"] = $password;
  }
  
  /**
   * Enables/disables aggresive linksearch
   *
   * @param bool $mode
   * @return bool
   */
  public function enableAggressiveLinkSearch($mode)
  {
    if (!is_bool($mode)) return false;
    
    $this->LinkFinder->aggressive_search = $mode;
    return true;
  }
  
  public function setHeaderCheckCallbackFunction(&$obj, $method_name)
  {
    $this->header_check_callback_function = array($obj, $method_name);
  }
  
  /**
   * Sends the HTTP-request and receives the page/file.
   *
   * @return A PHPCrawlerDocumentInfo-object containing all information about the received page/file
   */
  public function sendRequest()
  {
    // Prepare LinkFinder
    $this->LinkFinder->resetLinkCache();
    $this->LinkFinder->setSourceUrl($this->UrlDescriptor);
    
    // Initiate the Response-object and pass base-infos
    $PageInfo = new PHPCrawlerDocumentInfo();
    $PageInfo->url = $this->UrlDescriptor->url_rebuild;
    $PageInfo->protocol = $this->url_parts["protocol"];
    $PageInfo->host = $this->url_parts["host"];
    $PageInfo->path = $this->url_parts["path"];
    $PageInfo->file = $this->url_parts["file"];
    $PageInfo->query = $this->url_parts["query"];
    $PageInfo->port = $this->url_parts["port"];
    
    
    // Create header to send
    $request_header_lines = $this->buildRequestHeader();
    $header_string = trim(implode("", $request_header_lines));
    $PageInfo->header_send = $header_string;
    
    // Open socket
    $this->openSocket($PageInfo->error_code, $PageInfo->error_string);
    
    // If error occured
    if ($PageInfo->error_code != null)
    {
      // If proxy-error -> throw exception
      if ($PageInfo->error_code ==  PHPCrawlerRequestErrors::ERROR_PROXY_UNREACHABLE)
      {
        throw new Exception("Unable to connect to proxy '".$this->proxy["proxy_host"]."' on port '".$this->proxy["proxy_port"]."'");
      }
      
      $PageInfo->error_occured = true;
      return $PageInfo; 
    }
    
    // Send request
    $this->sendRequestHeader($request_header_lines);
    
    // Read response-header
    $response_header = $this->readResponseHeader($PageInfo->error_code, $PageInfo->error_string);
    
    // If error occured
    if ($PageInfo->error_code != null)
    {
      $PageInfo->error_occured = true;
      return $PageInfo; 
    }
    
    // Set header-infos
    $this->lastResponseHeader = new PHPCrawlerResponseHeader($response_header, $this->UrlDescriptor->url_rebuild);
    $PageInfo->responseHeader = $this->lastResponseHeader;
    $PageInfo->header = $this->lastResponseHeader->header_raw;
    $PageInfo->http_status_code = $this->lastResponseHeader->http_status_code;
    $PageInfo->content_type = $this->lastResponseHeader->content_type;
    $PageInfo->cookies = $this->lastResponseHeader->cookies;
    
    // Referer-Infos
    if ($this->UrlDescriptor->refering_url != null)
    {
      $PageInfo->referer_url = $this->UrlDescriptor->refering_url;
      $PageInfo->refering_linkcode = $this->UrlDescriptor->linkcode;
      $PageInfo->refering_link_raw = $this->UrlDescriptor->link_raw;
      $PageInfo->refering_linktext = $this->UrlDescriptor->linktext;
    }
      
    // Call header-check-callback
    $ret = 0;
    if ($this->header_check_callback_function != null)
      $ret = call_user_func($this->header_check_callback_function, $this->lastResponseHeader);
    
    // Check if content should be received
    $receive = $this->decideRecevieContent($this->lastResponseHeader);
    
    if ($ret < 0 || $receive == false)
    {
      @fclose($this->socket);
      $PageInfo->received = false;
      $PageInfo->links_found_url_descriptors = $this->LinkFinder->getAllURLs(); // Maybe found a link/redirect in the header
      $PageInfo->meta_attributes = $this->LinkFinder->getAllMetaAttributes();
      return $PageInfo;
    }
    else
    {
      $PageInfo->received = true;
    }
    
    // Check if content should be streamd to file
    $stream_to_file = $this->decideStreamToFile($response_header);
                    
    // Read content
    $response_content = $this->readResponseContent($stream_to_file, $PageInfo->error_code, $PageInfo->error_string, $PageInfo->received_completely, $PageInfo->bytes_received);

    // If error occured
    if ($PageInfo->error_code != null)
    {
      $PageInfo->error_occured = true;
    }
    
    @fclose($this->socket);
    
    // Complete ResponseObject
    $PageInfo->content = $PageInfo->source = $response_content;
    $PageInfo->received_completly = $PageInfo->received_completely;
    $PageInfo->data_transfer_time = $this->data_transfer_time;
    $PageInfo->data_transfer_rate = $PageInfo->bytes_received / $this->data_transfer_time;
    
    if ($stream_to_file == true)
    {
      $PageInfo->received_to_file = true;
      $PageInfo->content_tmp_file = $this->tmpFile;
    }
    else $PageInfo->received_to_memory = true;
    
    $PageInfo->links_found_url_descriptors = $this->LinkFinder->getAllURLs();
    $PageInfo->meta_attributes = $this->LinkFinder->getAllMetaAttributes();
    $PageInfo->setLinksFoundArray();
    
    return $PageInfo;
  }
  
  /**
   * Opens the socket to the host.
   *
   * @param  int    &$error_code   Error-code by referenct if an error occured.
   * @param  string &$error_string Error-string by reference
   * @return bool   TRUE if socket could be opened, otherwise FALSE.
   */
  protected function openSocket(&$error_code, &$error_string)
  {
    PHPCrawlerBenchmark::start("connecting_server");
    
    // SSL or not?
    if ($this->url_parts["protocol"] == "https://") $protocol_prefix = "ssl://";
    else $protocol_prefix = "";
    
    // If SSL-request, but openssl is not installed
    if ($protocol_prefix == "ssl://" && !extension_loaded("openssl"))
    {
      $error_code = PHPCrawlerRequestErrors::ERROR_SSL_NOT_SUPPORTED;
      $error_string = "Error connecting to ".$this->url_parts["protocol"].$this->url_parts["host"].": SSL/HTTPS-requests not supported, extension openssl not installed.";
    }
    
    // Get IP for hostname
    $ip_address = $this->DNSCache->getIP($this->url_parts["host"]);
    
    // Open socket
    if ($this->proxy != null)
    {
      //$this->socket = @fsockopen ($this->proxy["proxy_host"], $this->proxy["proxy_port"], $error_code, $error_str, $this->socketConnectTimeout);
      $this->socket = @stream_socket_client($this->proxy["proxy_host"].":".$this->proxy["proxy_port"], $error_code, $error_str,
                                           $this->socketConnectTimeout, STREAM_CLIENT_CONNECT);
    }
    else
    {
      //$this->socket = @fsockopen ($protocol_prefix.$ip_address, $this->url_parts["port"], $error_code, $error_str, $this->socketConnectTimeout);
      
      // If ssl -> perform Server name indication
      if ($this->url_parts["protocol"] == "https://")
        $context = stream_context_create(array('ssl' => array('SNI_server_name' => $this->url_parts["host"])));
      else
        $context = stream_context_create(array());
      
      $this->socket = @stream_socket_client($protocol_prefix.$ip_address.":".$this->url_parts["port"], $error_code, $error_str,
                                           $this->socketConnectTimeout, STREAM_CLIENT_CONNECT, $context);
    }
    
    PHPCrawlerBenchmark::stop("connecting_server");
    
    // If socket not opened -> throw error
    if ($this->socket == false)
    {
      // If proxy not reachable
      if ($this->proxy != null)
      {
        $error_code = PHPCrawlerRequestErrors::ERROR_PROXY_UNREACHABLE;
        $error_string = "Error connecting to proxy ".$this->proxy["proxy_host"].": Host unreachable (".$error_str.").";
        return false;
      }
      else
      {
        $error_code = PHPCrawlerRequestErrors::ERROR_HOST_UNREACHABLE;
        $error_string = "Error connecting to ".$this->url_parts["protocol"].$this->url_parts["host"].": Host unreachable (".$error_str.").";
        return false;
      }
    }
    else return true;
  }
  
  /**
   * Send the request-header.
   */
  protected function sendRequestHeader($request_header_lines)
  {
    PHPCrawlerBenchmark::start("sending_header");
    
    // Header senden
    $cnt = count($request_header_lines);
    for ($x=0; $x<$cnt; $x++)
    {
      fputs($this->socket, $request_header_lines[$x]);
    }
    
    PHPCrawlerBenchmark::stop("sending_header");
  }
  
  /**
   * Reads the response-header.
   *
   * @param  int    &$error_code   Error-code by reference if an error occured.
   * @param  string &$error_string Error-string by reference
   * @return string  The response-header or NULL if an error occured
   */
  protected function readResponseHeader(&$error_code, &$error_string)
  {
    PHPCrawlerBenchmark::start("server_response_time");
    PHPCrawlerBenchmark::start("data_transfer_time", true);
    
    $status = socket_get_status($this->socket);
    $source_read = "";
    $header = "";
    $server_responded = false;
    
    while ($status["eof"] == false)
    {
      socket_set_timeout($this->socket, $this->socketReadTimeout);
      
      // Read from socket
      $line_read = fgets($this->socket, 1024); // Das @ ist da um die blöde "SSL fatal protocol error"-Warnung zu unterdrücken, 
                                               // die keinen Sinn macht
      if ($server_responded == false)
      {
        $server_responded = true;
        PHPCrawlerBenchmark::stop("server_response_time");
        PHPCrawlerBenchmark::start("retreiving_header");
      }
      
      $source_read .= $line_read;
      
      $this->global_traffic_count += strlen($line_read);
      
      $status = socket_get_status($this->socket);
      
      // Socket timed out
      if ($status["timed_out"] == true)
      {
        $error_code = PHPCrawlerRequestErrors::ERROR_SOCKET_TIMEOUT;
        $error_string = "Socket-stream timed out (timeout set to ".$this->socketReadTimeout." sec).";
        return $header;
      }
      
      // No "HTTP" at beginnig of response
      if (strtolower(substr($source_read, 0, 4)) != "http")
      {
        $error_code = PHPCrawlerRequestErrors::ERROR_NO_HTTP_HEADER;
        $error_string = "HTTP-protocol error.";
        return $header;
      }
      
      if (substr($source_read, -4, 4) == "\r\n\r\n")
      {
        $header = substr($source_read, 0, strlen($source_read)-2);
        
        // Search for links (redirects) in the header
        $this->LinkFinder->processHTTPHeader($header);
        
        PHPCrawlerBenchmark::stop("retreiving_header");
        PHPCrawlerBenchmark::stop("data_transfer_time");
        return $header;
      }
    }
    
    // No header found
    if ($header == "")
    {
      $error_code = PHPCrawlerRequestErrors::ERROR_NO_HTTP_HEADER;
      $error_string = "Host doesn't respond with a HTTP-header.";
      return null;
    }
  }
  
  /**
   * Reads the response-content.
   * 
   * @param bool    $stream_to_file If TRUE, the content will be streamed diretly to the temporary file and
   *                                this method will not return the content as a string.                            
   * @param int     &$error_code    Error-code by reference if an error occured.
   * @param &string &$error_string  Error-string by reference
   * @param &string &$document_received_completely Flag indicatign whether the content was received completely passed by reference
   * @param &string &$bytes_received Number of bytes received, passed by reference
   * @return string  The response-content/source. May be emtpy if an error ocdured or data was streamed to the tmp-file.
   */
  protected function readResponseContent($stream_to_file = false, &$error_code, &$error_string, &$document_received_completely, &$bytes_received)
  {
    PHPCrawlerBenchmark::start("retreiving_content");
    PHPCrawlerBenchmark::start("data_transfer_time", true);
    
    // If content should be streamed to file
    if ($stream_to_file == true)
    {
      $fp = @fopen($this->tmpFile, "w");
      
      if ($fp == false)
      {
        $error_code = PHPCrawlerRequestErrors::ERROR_TMP_FILE_NOT_WRITEABLE;
        $error_string = "Couldn't open the temporary file ".$this->tmpFile." for writing.";
        return "";
      }
    }
    
    // Init
    $status = socket_get_status($this->socket);
    $source_portion = "";
    $source_complete = "";
    $bytes_received = 0;
    $document_received_completely = true;
    $stop_receving = false;
    
    while ($stop_receving == false)
    {
      socket_set_timeout($this->socket, $this->socketReadTimeout);
      
      // Read from socket
      $line_read = @fread($this->socket, 1024); // Das @ ist da um die blöde "SSL fatal protocol error"-Warnung zu unterdrücken, 
                                                // die keinen Sinn macht
      
      // Check socket-status
      $status = socket_get_status($this->socket);
      
      // Check for EOF
      if ($status["eof"] == true) $stop_receving = true;
      
      // Socket timed out
      if ($status["timed_out"] == true)
      {
        $stop_receving = true;
        $error_code = PHPCrawlerRequestErrors::ERROR_SOCKET_TIMEOUT;
        $error_string = "Socket-stream timed out (timeout set to ".$this->socketReadTimeout." sec).";
        $document_received_completely = false;
      }
      else
      {
        $source_portion .= $line_read;
        $bytes_received += strlen($line_read);
        $this->global_traffic_count += strlen($line_read);
        
        // Stream to file or store source in memory
        if ($stream_to_file == true)
        {
          @fwrite($fp, $line_read);
        }
        else
        {
          $source_complete .= $line_read;
        }
      }
      
      // Check if content-length stated in the header is reached
      if ($this->lastResponseHeader->content_length == $bytes_received)
      {
        $stop_receving = true;
      }
      
      // Check if contentsize-limit is reached
      if ($this->content_size_limit > 0 && $this->content_size_limit <= $bytes_received)
      {
        $stop_receving = true;
      }
                
      // Find links in portion of the source
      if (strlen($source_portion) >= 100000 || $stop_receving == true)
      {
        if (PHPCrawlerUtils::checkStringAgainstRegexArray($this->lastResponseHeader->content_type, $this->linksearch_content_types))
        {
          PHPCrawlerBenchmark::stop("retreiving_content");
          PHPCrawlerBenchmark::stop("data_transfer_time");
          
          $this->LinkFinder->findLinksInHTMLChunk($source_portion);
          $source_portion = substr($source_portion, -1500);
          
          PHPCrawlerBenchmark::start("retreiving_content");
          PHPCrawlerBenchmark::start("data_transfer_time", true);
        }
      }

    }
    
    if ($stream_to_file == true) @fclose($fp);
    
    PHPCrawlerBenchmark::stop("retreiving_content");
    PHPCrawlerBenchmark::stop("data_transfer_time");
    
    $this->data_transfer_time = PHPCrawlerBenchmark::getElapsedTime("data_transfer_time");
    PHPCrawlerBenchmark::reset("data_transfer_time");
    
    return $source_complete;
  }
  
  /**
   * Builds the request-header from the given settings.
   *
   * @return array  Numeric array containing the lines of the request-header
   */
  protected function buildRequestHeader()
  {
    // Create header
    $headerlines = array();
    
    // Methode(GET or POST)
    if (count($this->post_data) > 0) $request_type = "POST";
    else $request_type = "GET";
    
    if ($this->proxy != null)
    {
      // A Proxy needs the full qualified URL in the GET or POST headerline.
      $headerlines[] = $request_type." ".$this->UrlDescriptor->url_rebuild ." HTTP/1.0\r\n";
    }
    else
    {
      $query = $this->prepareHTTPRequestQuery($this->url_parts["path"].$this->url_parts["file"].$this->url_parts["query"]);
      $headerlines[] = $request_type." ".$query." HTTP/1.0\r\n";
    }
    
    $headerlines[] = "HOST: ".$this->url_parts["host"]."\r\n";
    
    $headerlines[] = "User-Agent: ".str_replace("\n", "", $this->userAgentString)."\r\n";
    
    // Referer
    if ($this->UrlDescriptor->refering_url != null)
    {
      $headerlines[] = "Referer: ".$this->UrlDescriptor->refering_url."\r\n";
    }
    
    // Cookies
    $headerlines[] = $this->buildCookieHeader();
    
    // Authentication
    if ($this->url_parts["auth_username"] != "" && $this->url_parts["auth_password"] != "")
    {
      $auth_string = base64_encode($this->url_parts["auth_username"].":".$this->url_parts["auth_password"]);
      $headerlines[] = "Authorization: Basic ".$auth_string."\r\n";
    }
    
    // Proxy authentication
    if ($this->proxy != null && $this->proxy["proxy_username"] != null)
    {
      $auth_string = base64_encode($this->proxy["proxy_username"].":".$this->proxy["proxy_password"]);
      $headerlines[] = "Proxy-Authorization: Basic ".$auth_string."\r\n";
    }
    
    $headerlines[] = "Connection: close\r\n";
    
    // Wenn POST-Request
    if ($request_type == "POST")
    {
      // Post-Content bauen
      $post_content = $this->buildPostContent();
      
      $headerlines[] = "Content-Type: multipart/form-data; boundary=---------------------------10786153015124\r\n";
      $headerlines[] = "Content-Length: ".strlen($post_content)."\r\n\r\n";
      $headerlines[] = $post_content;
    }
    else
    {
      $headerlines[] = "\r\n";
    }

    return $headerlines;
  }
  
  /**
   * Prepares the given HTTP-query-string for the HTTP-request.
   *
   * HTTP-query-strings always should be utf8-encoded and urlencoded afterwards.
   * So "/path/file?test=tatütata" will be converted to "/path/file?test=tat%C3%BCtata":
   *
   * @param stirng The quetry-string (like "/path/file?test=tatütata")
   * @return string
   */
  protected function prepareHTTPRequestQuery($query)
  {
    // If string already is a valid URL -> do nothing
    if (PHPCrawlerUtils::isValidUrlString($query))
    {
      return $query;
    }
    
    // Decode query-string (for URLs that are partly urlencoded and partly not)
    $query = rawurldecode($query);
    
    // if query is already utf-8 encoded -> simply urlencode it,
    // otherwise encode it to utf8 first.
    if (PHPCrawlerUtils::isUTF8String($query) == true)
    {
      $query = rawurlencode($query);
    }
    else
    {
      $query = rawurlencode(utf8_encode($query));
    }
    
    // Replace url-specific signs back
    $query = str_replace("%2F", "/", $query);
    $query = str_replace("%3F", "?", $query);
    $query = str_replace("%3D", "=", $query);
    $query = str_replace("%26", "&", $query);
   
    return $query;
  }
  
  /**
   * Builds the post-content from the postdata-array for the header to send with the request (MIME-style)
   *
   * @return array  Numeric array containing the lines of the POST-part for the header
   */
  protected function buildPostContent()
  {
    $post_content = "";
    
    // Post-Data
    @reset($this->post_data);
    while (list($key, $value) = @each($this->post_data))
    {
      $post_content .= "-----------------------------10786153015124\r\n";
      $post_content .= "Content-Disposition: form-data; name=\"".$key."\"\r\n\r\n";
      $post_content .= $value."\r\n";
    }
    
    $post_content .= "-----------------------------10786153015124\r\n";
    
    return $post_content;
  }
  
  /**
   * Builds the cookie-header-part for the header to send.
   *
   * @return string  The cookie-header-part, i.e. "Cookie: test=bla; palimm=palaber"
   */
  protected function buildCookieHeader()
  {
    $cookie_string = "";
    
    @reset($this->cookie_array);
    while(list($key, $value) = @each($this->cookie_array))
    {
      $cookie_string .= "; ".$key."=".$value."";
    }
    
    if ($cookie_string != "")
    {
      return "Cookie: ".substr($cookie_string, 2)."\r\n";
    }
    else
    {
      return "";
    }
  }
  
  /**
   * Checks whether the content of this page/file should be received (based on the content-type
   * and the applied rules)
   *
   * @param PHPCrawlerResponseHeader $responseHeader The response-header as an PHPCrawlerResponseHeader-object
   * @return bool TRUE if the content should be received
   */
  protected function decideRecevieContent(PHPCrawlerResponseHeader $responseHeader)
  {
    // Get Content-Type from header
    $content_type = $responseHeader->content_type;
    
    // No Content-Type given
    if ($content_type == null) return false;
    
    // Check against the given rules
    $receive = PHPCrawlerUtils::checkStringAgainstRegexArray($content_type, $this->receive_content_types);
    
    return $receive;
  }
  
  /**
   * Checks whether the content of this page/file should be streamed directly to file.
   *
   * @param string $response_header The response-header
   * @return bool TRUE if the content should be streamed to TMP-file
   */
  protected function decideStreamToFile($response_header)
  {
    if (count($this->receive_to_file_content_types) == 0) return false;
    
    // Get Content-Type from header
    $content_type = PHPCrawlerUtils::getHeaderValue($response_header, "content-type");
    
    // No Content-Type given
    if ($content_type == null) return false;
    
    // Check against the given rules
    $receive = PHPCrawlerUtils::checkStringAgainstRegexArray($content_type, $this->receive_to_file_content_types);
    
    return $receive;
  }
  
  /**
   * Adds a rule to the list of rules that decides which pages or files - regarding their content-type - should be received
   *
   * If the content-type of a requested document doesn't match with the given rules, the request will be aborted after the header
   * was received.
   *
   * @param string $regex The rule as a regular-expression
   * @return bool TRUE if the rule was added to the list.
   *              FALSE if the given regex is not valid.
   */
  public function addReceiveContentType($regex)
  {
    $check = PHPCrawlerUtils::checkRegexPattern($regex); // Check pattern
    
    if ($check == true)
    {
      $this->receive_content_types[] = trim(strtolower($regex));
    }
    return $check;
  }
  
  /**
   * Adds a rule to the list of rules that decides what types of content should be streamed diretly to the temporary file.
   *
   * If a content-type of a page or file matches with one of these rules, the content will be streamed directly into the temporary file
   * given in setTmpFile() without claiming local RAM.
   * 
   * @param string $regex The rule as a regular-expression
   * @return bool         TRUE if the rule was added to the list and the regex is valid.
   */
  public function addStreamToFileContentType($regex)
  {
    $check = PHPCrawlerUtils::checkRegexPattern($regex); // Check pattern
    
    if ($check == true)
    {
      $this->receive_to_file_content_types[] = trim($regex);
    }
    return $check;
  }
  
  /**
   * Sets the temporary file to use when content of found documents should be streamed directly into a temporary file.
   *
   * @param string $tmp_file The TMP-file to use.
   */
  public function setTmpFile($tmp_file)
  {
    //Check if writable
    $fp = @fopen($tmp_file, "w");
    
    if (!$fp)
    {
      return false;
    }
    else
    {
      fclose($fp);
      $this->tmpFile = $tmp_file;
      return true;
    }
  }
  
  /**
   * Sets the size-limit in bytes for content the request should receive.
   *
   * @param int $bytes
   * @return bool
   */
  public function setContentSizeLimit($bytes)
  {
    if (preg_match("#^[0-9]*$#", $bytes))
    {
      $this->content_size_limit = $bytes;
      return true;
    }
    else return false;
  }
  
  /**
   * Returns the global traffic this instance of the HTTPRequest-class caused so far.
   *
   * @return int The traffic in bytes.
   */
  public function getGlobalTrafficCount()
  {
    return $this->global_traffic_count;
  }
  
  /**
   * Adds a rule to the list of rules that decide what kind of documents should get
   * checked for links in (regarding their content-type)
   *
   * @param string $regex Regular-expression defining the rule
   * @return bool         TRUE if the rule was successfully added
   */
  function addLinkSearchContentType($regex)
  {
    $check = PHPCrawlerUtils::checkRegexPattern($regex); // Check pattern
    if ($check == true)
    {
      $this->linksearch_content_types[] = trim($regex);
    }
    return $check;
  }
}
?>