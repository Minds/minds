<?php
/**
 * Describes a URL within the PHPCrawl-system.
 *
 * @package phpcrawl
 */
class PHPCrawlerURLDescriptor
{
  /**
   * The complete, full qualified and normalized URL
   *
   * @var string
   */
  public $url_rebuild = null;
  
  /**
   * The raw link to this URL as it was found in the HTML-source, i.e. "../dunno/index.php"
   */
  public $link_raw = null;
  
  /**
   * The html-codepart that contained the link to this URL, i.e. "<a href="../foo.html">LINKTEXT</a>"
   */
  public $linkcode = null;
  
  /**
   * The linktext or html-code the link to this URL was layed over.
   */
  public $linktext = null;
  
  /**
   * The URL of the page that contained the link to the URL described here.
   *
   * @var string
   */
  public $refering_url;
  
  /**
   * Flag indicating whether this URL was target of an HTTP-redirect.
   *
   * @var string
   */
  public $is_redirect_url = false;
  
  /**
   * Initiates an URL-descriptor
   *
   * @internal
   */
  public function __construct($url_rebuild, $link_raw = null, $linkcode = null, $linktext = null, $refering_url = null)
  {
    $this->url_rebuild = $url_rebuild;
    
    if (!empty($link_raw)) $this->link_raw = $link_raw;
    if (!empty($linkcode)) $this->linkcode = $linkcode;
    if (!empty($linktext)) $this->linktext = $linktext;
    if (!empty($refering_url)) $this->refering_url = $refering_url;
  }  
}
?>