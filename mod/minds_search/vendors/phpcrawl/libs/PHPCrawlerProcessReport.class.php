<?php
/**
 * Contains summarizing information about a crawling-process after the process is finished.
 *
 * @package phpcrawl
 */
class PHPCrawlerProcessReport
{
  /**
   * The total number of links/URLs the crawler found and followed.
   *
   * @var int
   */
  public $links_followed = 0;
  
  /**
   * The total number of documents the crawler received.
   *
   * @var int
   */
  public $files_received = 0;
  
  /**
   * The total number of bytes the crawler received alltogether.
   *
   * @var int
   */
  public $bytes_received = 0;
  
  /**
   * The total time the crawling-process was running in seconds.
   *
   * @var float Proess-runtime in seconds.
   */
  public $process_runtime = 0;
  
  /**
   * The average data-throughput in bytes per second.
   *
   * @var float
   */
  public $data_throughput = 0;
  
  /**
   * Will be TRUE if the crawling-process stopped becaus the traffic-limit was reached.
   *
   * @var bool
   */
  public $traffic_limit_reached = false;
  
  /**
   * Will be TRUE if the page/file-limit was reached.
   *
   * @var bool
   */
  public $file_limit_reached = false;
  
  /**
   * Will be TRUE if the crawling-process stopped because the overridable function handleDocumentInfo() returned a negative value.
   *
   * @var bool
   */
  public $user_abort = false;
  
  /**
   * The peak memory-usage the crawling-process caused.
   *
   * @var int Memory-usage in bytes. May be NULL if PHP-version is lower than 5.2.0. 
   */
  public $memory_peak_usage;
  
  /**
   * Reason for the abortion of the crawling-process
   *
   * @var int One of the {@link PHPCrawlerAbortReasons}-constants
   */
  public $abort_reason;
  
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