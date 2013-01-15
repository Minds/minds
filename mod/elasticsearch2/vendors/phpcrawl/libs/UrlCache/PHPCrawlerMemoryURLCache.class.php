<?php
/**
 * Class for caching/storing URLs/links in memory.
 *
 * @package phpcrawl
 * @internal
 */
class PHPCrawlerMemoryURLCache extends PHPCrawlerURLCacheBase
{
  protected $urls = array();
  protected $url_map = array();
  
  /**
   * Returns the next URL from the cache that should be crawled.
   *
   * @return PhpCrawlerURLDescriptor
   */
  public function getNextUrl()
  {
    //PHPCrawlerBenchmark::start("getting_cached_url");
    
    $max_pri_lvl = $this->getMaxPriorityLevel();
    
    @reset($this->urls[$max_pri_lvl]);
    while (list($key) = @each($this->urls[$max_pri_lvl]))
    {
      $UrlDescriptor_next = $this->urls[$max_pri_lvl][$key];
      unset($this->urls[$max_pri_lvl][$key]);
      break;
    }
    
    // If there's no URL in the priority-level-array left -> unset
    if (count($this->urls[$max_pri_lvl]) == 0) unset($this->urls[$max_pri_lvl]);
    
    //PHPCrawlerBenchmark::stop("getting_cached_url");
     
    return $UrlDescriptor_next;
  }
  
  /**
   * Returns all URLs currently cached in the URL-cache.
   *
   * @return array Numeric array containing all URLs as PHPCrawlerURLDescriptor-objects
   */
  public function getAllURLs()
  {
    $URLs = array();
    
    @reset($this->urls);
    while (list($pri_lvl) = @each($this->urls))
    {
      $cnt = count($this->urls[$pri_lvl]);
      for ($x=0; $x<$cnt; $x++)
      {
        $URLs[] = &$this->urls[$pri_lvl][$x];
      }
    }
    
    return $URLs;
  }
  
  /**
   * Removes all URLs and all priority-rules from the URL-cache.
   */
  public function clear()
  {
    $this->urls = array();
    $this->url_map = array();
    $this->url_priorities = array();
  }
  
  /**
   * Adds an URL to the url-cache
   *
   * @param PHPCrawlerURLDescriptor $UrlDescriptor      
   */
  public function addURL(PHPCrawlerURLDescriptor $UrlDescriptor)
  { 
    if ($UrlDescriptor == null) return;
    
    // Hash of the URL
    $map_key = $this->getDistinctURLHash($UrlDescriptor);
    
    // If URL already in cache -> abort
    if($map_key != null && isset($this->url_map[$map_key])) return;
    
    // Retrieve priority-level
    $priority_level = $this->getUrlPriority($UrlDescriptor->url_rebuild);
    
    // Add URL to URL-Array
    $this->urls[$priority_level][] = $UrlDescriptor;
    
    // Add URL to URL-Map
    if ($this->url_distinct_property != self::URLHASH_NONE)
      $this->url_map[$map_key] = true;
  }
  
  /**
   * Adds an bunch of URLs to the url-cache
   *
   * @param array $urls  A numeric array containing the URLs as PHPCrawlerURLDescriptor-objects
   */
  public function addURLs($urls)
  {
    //PHPCrawlerBenchmark::start("caching_urls");
    
    $cnt = count($urls);
    for ($x=0; $x<$cnt; $x++)
    {
      if ($urls[$x] != null)
      {
        $this->addURL($urls[$x]);
      }
    }
    
    //PHPCrawlerBenchmark::stop("caching_urls");
  }
  
  /**
   * Checks whether there are URLs left in the cache or not.
   *
   * @return bool
   */
  public function containsURLs()
  {
    if (count($this->urls) == 0) return false;
    else return true;
  }
  
  /**
   * Has no function in this class.
   */
  public function cleanup()
  {
  }
  
  /**
   * Has no function in this class.
   */
  public function purgeCache()
  {
  }
  
  /**
   * Has no function in this memory-cache.
   */
  public function markUrlAsFollowed(PHPCrawlerURLDescriptor $UrlDescriptor)
  {
  }
  
  /**
   * Returns the highest priority-level an URL exists in cache for.
   *
   * @return int
   */
  protected function getMaxPriorityLevel()
  {
    $defined_priority_levels = array_keys($this->urls);
    rsort($defined_priority_levels);
    return $defined_priority_levels[0];
  }
}
?>