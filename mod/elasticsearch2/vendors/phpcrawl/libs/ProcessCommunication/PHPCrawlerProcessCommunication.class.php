<?php
/**
 * Class containing methods for process handling and communication
 *
 * @package phpcrawl
 * @internal
 */
class PHPCrawlerProcessCommunication
{
  protected $crawler_uniqid;
  
  protected $multiprocess_mode;
  
  protected $working_directory;
  
  protected $crawlerStatus;
  
  /**
   * Flag indicating whether resumtion is activated
   *
   * @var PHPCrawlerDocumentInfoQueue
   */
  protected $resumtion_enabled = false;
  
  /**
   * Initiates a new PHPCrawlerProcessCommunication-object.
   *
   * @param string $crawler_uniqid     UID of the crawler
   * @param int    $multiprocess_mode  Multprocess-mode the crawler is running (one of the PHPCrawlerMultiProcessModes-constants)
   * @param string $working_directory  Working-dir of the crawler
   * @param bool   $enable_resumtion   TRUE if resumption of crawling-processes should be possible
   */ 
  public function __construct($crawler_uniqid, $multiprocess_mode, $working_directory, $enable_resumtion)
  {
    $this->crawler_uniqid = $crawler_uniqid;
    $this->multiprocess_mode = $multiprocess_mode;
    $this->working_directory = $working_directory;
    $this->resumtion_enabled = $enable_resumtion;
    
    $this->crawlerStatus = new PHPCrawlerStatus();
  }
  
  /**
   * Sets/writes the current crawler-status
   *
   * @param PHPCrawlerStatus $crawler_status The status to set
   */
  public function setCrawlerStatus(PHPCrawlerStatus $crawler_status)
  {
    $this->crawlerStatus = $crawler_status;
    
    // Write crawler-status back to file if crawler is multiprocessed
    if ($this->multiprocess_mode == PHPCrawlerMultiProcessModes::MPMODE_CHILDS_EXECUTES_USERCODE || $this->resumtion_enabled == true)
    {
      PHPCrawlerUtils::serializeToFile($this->working_directory."crawlerstatus.tmp", $crawler_status);
    }
  }
  
  /**
   * Returns/reads the current crawler-status
   *
   * @return PHPCrawlerStatus The current crawlerstatus as a PHPCrawlerStatus-object
   */
  public function getCrawlerStatus()
  {
    // Get crawler-status from file if crawler is multiprocessed
    if ($this->multiprocess_mode == PHPCrawlerMultiProcessModes::MPMODE_CHILDS_EXECUTES_USERCODE || $this->resumtion_enabled == true)
    {
      $this->crawlerStatus = PHPCrawlerUtils::deserializeFromFile($this->working_directory."crawlerstatus.tmp");
      if ($this->crawlerStatus == null) $this->crawlerStatus = new PHPCrawlerStatus();
    }
    
    return $this->crawlerStatus;
  }
  
  /**
   * Updates the status of the crawler
   *
   * @param PHPCrawlerDocumentInfo $PageInfo          The PHPCrawlerDocumentInfo-object of the last received document
   *                                                  or NULL if no document was received.
   * @param int                    $abort_reason      One of the PHPCrawlerAbortReasons::ABORTREASON-constants if the crawling-process
   *                                                  should get aborted, otherwise NULL
   * @param string                 $first_content_url The first URL some content was found in (or NULL if no content was found so far).
   */
  public function updateCrawlerStatus($PageInfo, $abort_reason = null, $first_content_url = null)
  {
    PHPCrawlerBenchmark::start("updating_crawler_status");
    
    // Set semaphore if crawler is multiprocessed
    if ($this->multiprocess_mode == PHPCrawlerMultiProcessModes::MPMODE_CHILDS_EXECUTES_USERCODE || $this->resumtion_enabled == true)
    {
      $sem_key = sem_get($this->crawler_uniqid);
      sem_acquire($sem_key);
    }
    
    // Get current Status
    $crawler_status = $this->getCrawlerStatus();
    
    // Update status
    if ($PageInfo != null)
    {
      // Increase number of followed links
      $crawler_status->links_followed++;
      
      // Increase documents_received-counter
      if ($PageInfo->received == true) $crawler_status->documents_received++;
        
      // Increase bytes-counter
      $crawler_status->bytes_received += $PageInfo->bytes_received;
    }
    
    // Set abortreason
    if ($abort_reason !== null) $crawler_status->abort_reason = $abort_reason;
    
    // Set first_content_url
    if ($first_content_url !== null) $crawler_status->first_content_url = $first_content_url;
    
    // Write crawler-status back
    $this->setCrawlerStatus($crawler_status);
    
    // Remove semaphore if crawler is multiprocessed
    if ($this->multiprocess_mode == PHPCrawlerMultiProcessModes::MPMODE_CHILDS_EXECUTES_USERCODE || $this->resumtion_enabled == true)
    {
      sem_release($sem_key);
    }
    
    PHPCrawlerBenchmark::stop("updating_crawler_status");
  }
  
  /**
   * Registers the PID of a child-process
   *
   * @param int The IPD
   */
  public function registerChildPID($pid)
  {
    $sem_key = sem_get($this->crawler_uniqid);
    sem_acquire($sem_key);
    
    file_put_contents($this->working_directory."pids", $pid."\n", FILE_APPEND);
    
    sem_release($sem_key);
  }
  
  /**
   * Returns alls PIDs of all running child-processes
   *
   * @param int $process_count If set, this function tries to get the child-PIDs until the gievn number of PIDs
   *                           was determinated.
   * @return array Numeric array conatining the PIDs
   */
  public function getChildPIDs($process_count = null)
  { 
    $child_pids = array();
    $try = true;
    
    while ($try == true)
    {
      if (file_exists($this->working_directory."pids"))
      {
        $ct = file_get_contents($this->working_directory."pids");
        $child_pids = preg_split("#\n#", $ct, -1, PREG_SPLIT_NO_EMPTY);
        
        if ($process_count == null) $try = false;
        if (count($child_pids) == $process_count) $try = false;
      }
      
      sleep(0.2);
    }
    
    return $child_pids;
    
  }
  
  /**
   * Kills all running child-processes
   */
  public function killChildProcesses()
  {
    $child_pids = $this->getChildPIDs();
    for ($x=0; $x<count($child_pids); $x++)
    {
      posix_kill($child_pids[$x], SIGKILL);
    }
  }
  
  /**
   * Checks wehther any child-processes a (still) running.
   *
   * @return bool
   */
  public function childProcessAlive()
  {
    $pids = $this->getChildPIDs();
    $cnt = count($pids);
    
    for ($x=0; $x<$cnt; $x++)
    {
      if (posix_getsid($pids[$x]) != false)
      {
        return true;
      }
    }
    
    return false;
  }
}
?>