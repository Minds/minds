<?php
/**
 * Contains all possible abortreasons for a crawling-process.
 *
 * @package phpcrawl.enums
 */
class PHPCrawlerAbortReasons
{
  /**
   * Crawling-process aborted because everything is done/passedthrough.
   *
   * @var int
   */
  const ABORTREASON_PASSEDTHROUGH = 1;
  
  /**
   * Crawling-process aborted because the traffic-limit set by user was reached.
   *
   * @var int
   */
  const ABORTREASON_TRAFFICLIMIT_REACHED = 2;
  
  /**
   * Crawling-process aborted because the filelimit set by user was reached.
   *
   * @var int
   */
  const ABORTREASON_FILELIMIT_REACHED = 3;
  
  /**
   * Crawling-process aborted because the handleDocumentInfo-method returned a negative value
   *
   * @var int
   */
  const ABORTREASON_USERABORT = 4;
}
?>