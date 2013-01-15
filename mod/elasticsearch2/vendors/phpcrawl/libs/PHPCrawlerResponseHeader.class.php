<?php
/**
 * Describes an HTTP response-header within the phpcrawl-system.
 *
 * @package phpcrawl
 */
class PHPCrawlerResponseHeader
{
  /**
   * Initiates an new PHPCrawlerResponseHeader.
   *
   * @param string $header_string A complete response-header as it was send by the server
   * @param string $source_url    The URL of the website the header was recevied from.
   * @internal
   */
  public function __construct($header_string, $source_url)
  {
    $this->header_raw = $header_string;
    $this->source_url = $source_url;
    
    $this->http_status_code = PHPCrawlerUtils::getHTTPStatusCode($header_string);
    $this->content_type = strtolower(PHPCrawlerUtils::getHeaderValue($header_string, "content-type"));
    $this->content_length = strtolower(PHPCrawlerUtils::getHeaderValue($header_string, "content-length"));
    $this->cookies = PHPCrawlerUtils::getCookiesFromHeader($header_string, $source_url);
  }
  
  /**
   * The raw HTTP-header as it was send by the server
   *
   * @var string
   */
  public $header_raw;
  
  /**
   * The HTTP-statuscode
   *
   * @var int
   */
  public $http_status_code;
  
  /**
   * The content-type
   *
   * @var string
   */
  public $content_type;
  
  /**
   * The content-length as stated in the header.
   *
   * @var int
   */
  public $content_length;
  
  /**
   * All cookies found in the header
   *
   * @var array Numeric array containing all cookies as {@link PHPCrawlerCookieDescriptor}-objects
   */
  public $cookies = array();
  
  /**
   * The URL of the website the header was recevied from.
   *
   * @var string
   */
  public $source_url;
}
?>