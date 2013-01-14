<?php
/**
 * Contains information about a page or file the crawler found and received during the crawling-process.
 *
 * @package phpcrawl
 */
class PHPCrawlerDocumentInfo
{ 
  /**
   * The complete, full qualified URL of the page or file, e.g. "http://www.foo.com/bar/page.html?x=y".
   *
   * @var string
   * @section 1 URL-related information
   */
  public $url = "";
  
  /**
   * The protocol-part of the URL of the page or file, e.g. "http://"
   *
   * @var string
   * @section 1 URL-related information
   */
  public $protocol = "";
  
  /**
   * The host-part of the URL of the requested page or file, e.g. "www.foo.com".
   *
   * @var string
   * @section 1 URL-related information
   */
  public $host = "";
  
  /**
   * The path in the URL of the requested page or file, e.g. "/page/".
   *
   * @var string
   * @section 1 URL-related information
   */
  public $path = "";
  
  /**
   * The name of the requested page or file, e.g. "page.html".
   *
   * @var string
   * @section 1 URL-related information
   */
  public $file = "";
  
  /**
   * The query-part of the URL of the requested page or file, e.g. "?x=y".
   *
   * @var string
   * @section 1 URL-related information
   */
  public $query = "";
  
  /**
   * The port of the URL the request was send to, e.g. 80
   *
   * @var int
   * @section 1 URL-related information
   */
  public $port;
  
  /**
   * The complete HTTP-header the webserver responded with this page or file.
   *
   * @var string
   * @section 2 Content-related information
   */
  public $header = "";
  
  /**
   * The complete HTTP-header the webserver responded with this page or file as a PHPCrawlerResponseHeader-object.
   *
   * @var PHPCrawlerResponseHeader
   * @section 2 Content-related information
   */
  public $responseHeader;
    
  /**
   * The complete HTTP-request-header the crawler sent to the server (debugging info).
   *
   * @var string
   */
  public $header_send = "";
  
  /**
   * Flag indicating whether content was received from the page or file.
   *
   * @var bool TRUE if the crawler received at least some source/content of this page or file.
   * @section 2 Content-related information
   */
  public $received = false;
  
  /**
   * Flag indicating whether content was completely received from the page or file.
   *
   * The conten of the current document may not be received comepletely due to settings made
   * with {@link PHPCrawler::setContentSizeLimit()) and/or {@link PHPCrawler::setTrafficLimit()}.
   *
   * @var bool TRUE if the crawler received the complete source/content of this page or file.
   * @section 2 Content-related information
   */
  public $received_completely = false;
  
  /**
   * Alias for received_completely, was spelled wrong in prevoius versions of phpcrawl.
   *
   * @deprecated
   * @section 11 Deprecated
   */
  public $received_completly = false;
  
  /**
   * Will be true if the content was received into local memory.
   *
   * You will have access to the content of the current page or file through $pageInfo->source.
   *
   * @section 2 Content-related information
   * @var bool
   */
  public $received_to_memory = false;
  
  /**
   * Will be true if the content was received into temporary file.
   *
   * The content is stored in the temporary file $pageInfo->content_tmp_file in this case.
   *
   * @section 2 Content-related information
   * @var bool
   */
  public $received_to_file = false;
  
  /**
   * The number of bytes the crawler received of the content of the document.
   *
   * @var int Received bytes
   * @section 2 Content-related information
   */
  public $bytes_received = 0;  
  
  /**
   * The content-type of the page or file, e.g. "text/html" or "image/gif".
   *
   * @var string The content-type
   * @section 2 Content-related information
   */
  public $content_type = "";
  
  /**
   * The content of the requested document (html-sourcecode or content of file).
   *
   * Will be empty if "received" is FALSE and the source won't be complete if "received_completly" is FALSE!
   *
   * @var string
   * @section 2 Content-related information
   */
  public $content = "";
  
  /**
   * Same as "content", the content of the requested document.
   *
   * @var string
   * @section 2 Content-related information
   */
  public $source = "";
  
  /**
   * The temporary file to which the content was received.
   *
   * Will be NULL if the content wasn't received to the temporary file.
   *
   * @var string
   * @section 2 Content-related information
   */
  public $content_tmp_file = null;
  
  /**
   * The HTTP-statuscode the webserver responded for the request, e.g. 200 (OK) or 404 (file not found).
   *
   * @var int
   * @section 2 Content-related information
   */
  public $http_status_code = null;
  
  /**
   * Cookies send by the server.
   *
   * @var array Numeric array containing all send cookies as {@link PHPCrawlerCookieDescriptor}-objects.
   * @section 2 Content-related information
   */
  public $cookies = array();
  
  /**
   * An numeric array containing information about all links that were found in the source of the page.
   *
   * Every element of that numeric array contains the following keys again:
   *
   * link_raw - contains the raw link as it was found
   * url_rebuild - contains the full qualified URL the link leads to
   * linkcode - the html-codepart that contained the link.
   * linktext - the linktext the link was layed over (may be empty).
   *
   * So e.g $page_data["links_found"][5]["link_raw"] contains the fifth link that was found in the current page.
   * (May be something like "../../foo.html").
   *
   * @var array
   * @section 3 Information about found links
   */
  public $links_found = array();
  
  /**
   * An numeric array containing a PHPCrawlerURLDescriptor-object for every link that was found in the page.
   *
   * Example: Printing the second raw link that was found on the page
   * <code>
   * echo $PageInfo->links_found_url_descriptors[2]->link_raw;
   * </code>
   *
   * @var array Numneric array containing {@link PHPCrawlerURLDescriptor}-objects
   * @section 3 Information about found links
   */
  public $links_found_url_descriptors = array();
  
  /**
   * The complete URL of the page that contained the link to this document.
   *
   * @var string
   * @section 7 Referer information
   */
  public $referer_url = null;
  
  /**
   * The html-sourcecode that contained the link to the current document.
   *
   * (E.g. <a href="../foo.html">LINKTEXT</a>)
   *
   * @var string
   * @section 7 Referer information
   */
  public $refering_linkcode = null;
  
  /**
   * Contains the raw link as it was found in the content of the refering URL. (E.g. "../foo.html")
   *
   * @var string
   * @section 7 Referer information
   */
  public $refering_link_raw = null;
  
  /**
   * The linktext of the link that "linked" to this document.
   *
   * E.g. if the refering link was <a href="../foo.html">LINKTEXT</a>, the refering linktext is "LINKTEXT".
   * May contain html-tags of course. 
   *
   * @var string
   * @section 7 Referer information
   */
  public $refering_linktext = null;
  
  /**
   * Indicates whether an error occured while requesting/receiving the document.
   *
   * @var bool TRUE if an error occured.
   * @section 8 Error-handling
   */
  public $error_occured = false;
  
  /**
   * The code of the error that perhaps occured while requesting/receiving the document.
   * (See PHPCrawlerRequestErrors::ERROR_... - constants)
   *
   * @var int One of the {@link PHPCrawlerRequestErrors}::ERROR_ ... constants.
   * @section 8 Error-handling
   */
  public $error_code = null;
  
  /**
   * A representig, human readable string for the error that perhaps occured while requesting/receiving the document.
   *
   * @var string A human readable error-string.
   * @section 8 Error-handling
   */
  public $error_string = null;
  
  /**
   * Indicated whether the traffic-limit set by the user was reached after downloading this document.
   *
   * @var bool  TRUE if traffic-limit was reached.
   */
  public $traffic_limit_reached = false;
  
  /**
   * The time it took to receive the document.
   *
   * @var float The time seconds
   * @section 10 Benchmarks
   */
  public $data_transfer_time = null;
  
  /**
   * The average data-transferrate for this document.
   *
   * @var float The rate in bytes per seconds.
   * @section 10 Benchmarks
   */
  public $data_transfer_rate = null;
  
  /**
   * Some internal benchmak-results as array.
   *
   * @var array Array containing some interlnal benchmark-results for receiving and processing this document.
   *            The keys are the identifiers, the values are the benchmark-times.
   * @section 10 Benchmarks
   * @internal
   */
  public $benchmarks = array();
  
  /**
   * All meta-tag atteributes found in the source of the document.
   *
   * @var array Assoziative array conatining all found meta-attributes.
   *            The keys are the meta-names, the values the content of the attributes.
   *            (like $tags["robots"] = "nofollow")
   * @section 2 Content-related information
   *
   */
  public $meta_attributes = array();
  
  /**
   * Workaround-method, copies and converts the array $links_found_url_descriptors to $links_found.
   *
   * @internal
   */
  public function setLinksFoundArray()
  { 
    $cnt = count($this->links_found_url_descriptors);
    for ($x=0; $x<$cnt; $x++)
    {
      $UrlDescriptor = $this->links_found_url_descriptors[$x];
      
      // Convert $UrlDescriptor-object to an array
      $object_vars = get_object_vars($UrlDescriptor);
      
      $this->links_found[] = $object_vars;
    }
  }
  
  /**
   * Returns an array with all properties of this class.
   *
   * @return array
   * @internal
   */
  public function toArray()
  {
    return get_object_vars($this);
  }
}
?>