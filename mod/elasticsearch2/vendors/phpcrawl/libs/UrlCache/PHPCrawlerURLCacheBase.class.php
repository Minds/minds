<?php
/**
 * Abstract baseclass for implemented URL-caching classes.
 *
 * @package phpcrawl
 * @internal
 */
abstract class PHPCrawlerURLCacheBase
{
  protected $url_priorities = array();
  
  /**
   * Defines which property of an URL is used to ensure that each URL is only cached once.
   *
   * @var int One of the URLHASH_.. constants
   */
  public $url_distinct_property = self::URLHASH_URL;
    
  const URLHASH_URL = 1;
  const URLHASH_RAWLINK= 2; 
  const URLHASH_NONE = 3;
  
  /**
   * Returns the next URL from the cache that should be crawled.
   *
   * @return PhpCrawlerURLDescriptor
   */
  abstract public function getNextUrl();
  
  /**
   * Returns all URLs currently cached in the URL-cache.
   *
   * @return array Numeric array containing all URLs as PHPCrawlerURLDescriptor-objects
   */
  abstract public function getAllURLs();
  
  /**
   * Removes all URLs and all priority-rules from the URL-cache.
   */
  abstract public function clear();
  
  /**
   * Adds an URL to the url-cache
   *
   * @param PHPCrawlerURLDescriptor $UrlDescriptor      
   */
  abstract public function addURL(PHPCrawlerURLDescriptor $UrlDescriptor);
  
  /**
   * Adds an bunch of URLs to the url-cache
   *
   * @param array $urls  A numeric array containing the URLs as PHPCrawlerURLDescriptor-objects
   */
  abstract public function addURLs($urls);
  
  /**
   * Checks whether there are URLs left in the cache or not.
   *
   * @return bool
   */
  abstract public function containsURLs();
  
  /**
   * Marks the given URL in the cache as "followed"
   *
   * @param PHPCrawlerURLDescriptor $UrlDescriptor
   */
  abstract public function markUrlAsFollowed(PHPCrawlerURLDescriptor $UrlDescriptor);
  
  /**
   * Do cleanups after the cache is not needed anymore
   */
  abstract public function cleanup();
  
  /**
   * Cleans/purges the URL-cache from inconsistent entries.
   */
  abstract public function purgeCache();
  
  /**
   * Returns the distinct-hash for the given URL that ensures that no URLs a cached more than one time.
   *
   * @return string The hash or NULL if no distinct-hash should be used.
   */
  protected function getDistinctURLHash(PHPCrawlerURLDescriptor $UrlDescriptor)
  {
    if ($this->url_distinct_property == self::URLHASH_URL)
      return md5($UrlDescriptor->url_rebuild);
    elseif ($this->url_distinct_property == self::URLHASH_RAWLINK)
      return md5($UrlDescriptor->link_raw);
    else
      return null;
  }
  
  /**
   * Gets the priority-level of the given URL
   */
  protected function getUrlPriority($url)
  {
    $cnt = count($this->url_priorities);
    for ($x=0; $x<$cnt; $x++)
    {
      if (preg_match($this->url_priorities[$x]["match"], $url))
      {
        return $this->url_priorities[$x]["level"];
      }
    }
    
    return 0;
  }
  
  /**
   * Adds a Link-Priority-Level
   *
   * @param string $regex
   * @param int    $level
   */
  public function addLinkPriority($regex, $level)
  {
    $c = count($this->url_priorities);
    $this->url_priorities[$c]["match"] = trim($regex);
    $this->url_priorities[$c]["level"] = trim($level);
    
    // Sort url-priortie-array so that high priority-levels come firts.
    PHPCrawlerUtils::sort2dArray($this->url_priorities, "level", SORT_DESC);
  }
  
  /**
   * Adds a bunch of link-priorities
   *
   * @param array $priority_array Numeric array containing the subkeys "match" and "level"
   */
  public function addLinkPriorities($priority_array)
  {
    for ($x=0; $x<count($priority_array); $x++)
    {
      $this->addLinkPriority($priority_array[$x]["match"], $priority_array[$x]["level"]);
    }
  }
}
?>