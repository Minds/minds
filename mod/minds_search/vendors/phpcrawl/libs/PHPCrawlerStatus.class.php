<?php
/**
 * Describes the current status of an crawler-instance.
 *
 * @package phpcrawl
 * @internal
 */
class PHPCrawlerStatus
{
  /**
   * Number of bytes the crawler-instance received so far
   */
  public $bytes_received = 0;
  
  /**
   * Number of links the crawler-instance followed so far
   */
  public $links_followed = 0;
  
  /**
   * Number of documents the crawler-instance received so far
   */
  public $documents_received = 0;
  
  /**
   * Abort reason for aborting the crawling-process.
   *
   * @var int One of the PHPCrawlerAbortReasons-contants or NULL if the process shouldn't
   *          get aborted yet.
   */
  public $abort_reason = null;
  
  public $first_content_url = null;
}
?>