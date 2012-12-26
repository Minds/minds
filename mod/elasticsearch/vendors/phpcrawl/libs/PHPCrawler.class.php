<?php
/**
 * PHPCrawl mainclass
 *
 * @package phpcrawl
 * @author Uwe Hunfeld (phpcrawl@cuab.de)
 * @version 0.81
 * @License GPL2
 */
class PHPCrawler
{
  public $class_version = "0.81";
  
  /**
   * The PHPCrawlerHTTPRequest-Object
   *
   * @var PHPCrawlerHTTPRequest
   */
  protected $PageRequest;
  
  /**
   * The PHPCrawlerLinkCache-Object
   *
   * @var PHPCrawlerURLCache
   */
  protected $LinkCache;
  
  /**
   * The PHPCrawlerCookieCache-Object
   *
   * @var  PHPCrawlerCookieCache
   */
  protected $CookieCache;
  
  /**
   * The UrlFilter-Object
   *
   * @var PHPCrawlerURLFilter
   */
  protected $UrlFilter;
  
  /**
   * The RobotsTxtParser-Object
   *
   * @var PHPCrawlerRobotsTxtParser
   */
  protected $RobotsTxtParser;
  
  /**
   * UserSendDataCahce-object.
   *
   * @var PHPCrawlerUserSendDataCache
   */
  protected $UserSendDataCache;
  
  /**
   * The URL the crawler should start with.
   *
   * The URL is full qualified and normalized.
   *
   * @var string
   */
  protected $starting_url = "";
  
  /**
   * Defines whether robots.txt-file should be obeyed
   *
   * @val bool
   */
  protected $obey_robots_txt = false;
  
  /**
   * Limit of documents to receive
   *
   * @var int
   */
  protected $document_limit = 0;
  
  /**
   * Limit of bytes to receive
   *
   * @var int The limit in bytes
   */
  protected $traffic_limit = 0;
  
  /**
   * Defines if only documents that were received will be counted.
   *
   * @var bool
   */
  protected $only_count_received_documents = true;
  
  /**
   * Flag cookie-handling enabled/diabled
   *
   * @var bool
   */
  protected $cookie_handling_enabled = true;
  
  /**
   * The reason why the process was aborted/finished.
   *
   * @var int One of the PHPCrawlerAbortReasons::ABORTREASON-constants.
   */
  protected $porcess_abort_reason = null;
  
  /**
   * Flag indicating whether this instance is running in a child-process (if crawler runs multi-processed)
   */
  protected $is_chlid_process = false;
  
  /**
   * Flag indicating whether this instance is running in the parent-process (if crawler runs multi-processed)
   */
  protected $is_parent_process = false;
  
  /**
   * URl cache-type.
   *
   * @var int One of the PHPCrawlerUrlCacheTypes::URLCACHE..-constants.
   */
  protected $url_cache_type = 1;
  
  /**
   * UID of this instance of the crawler
   *
   * @var string
   */
  protected $crawler_uniqid = null;
  
  /**
   * Base-directory for temporary directories
   *
   * @var string
   */
  protected $working_base_directory;
  
  /**
   * Complete path to the temporary directory
   *
   * @var string
   */
  protected $working_directory = null;
  
  protected $link_priority_array = array();
  
  /**
   * Number of child-process (NOT the PID!)
   *
   * @var int
   */
  protected $child_process_number = null;
  
  /**
   * ProcessCommunication-object
   *
   * @var PHPCrawlerProcessCommunication
   */
  protected $ProcessCommunication = null;
  
  /**
   * Multiprocess-mode the crawler is runnung in.
   *
   * @var int One of the PHPCrawlerMultiProcessModes-constants
   */
  protected $multiprocess_mode = 0;
  
  /**
   * DocumentInfoQueue-object
   *
   * @var PHPCrawlerDocumentInfoQueue
   */
  protected $DocumentInfoQueue = null;
  
  protected $follow_redirects_till_content = true;
  
  /**
   * Flag indicating whether resumtion is activated
   *
   * @var PHPCrawlerDocumentInfoQueue
   */
  protected $resumtion_enabled = false;
  
  /**
   * Flag indicating whether the URL-cahce was purged at the beginning of a crawling-process
   */
  protected $urlcache_purged = false;
  
  /**
   * Initiates a new crawler.
   */
  public function __construct()
  { 
    // Create uniqid for this crawlerinstance
    $this->crawler_uniqid = getmypid().time();
    
    // Include needed class-files
    $classpath = dirname(__FILE__);
    
    // Utils-class
    if (!class_exists("PHPCrawlerUtils")) include_once($classpath."/PHPCrawlerUtils.class.php");
    
    // URL-Cache-classes
    if (!class_exists("PHPCrawlerURLCacheBase")) include_once($classpath."/UrlCache/PHPCrawlerURLCacheBase.class.php");
    if (!class_exists("PHPCrawlerMemoryURLCache")) include_once($classpath."/UrlCache/PHPCrawlerMemoryURLCache.class.php");
    if (!class_exists("PHPCrawlerSQLiteURLCache")) include_once($classpath."/UrlCache/PHPCrawlerSQLiteURLCache.class.php");
    
    // PageRequest-class
    if (!class_exists("PHPCrawlerHTTPRequest")) include_once($classpath."/PHPCrawlerHTTPRequest.class.php");
    $this->PageRequest = new PHPCrawlerHTTPRequest();
    $this->PageRequest->setHeaderCheckCallbackFunction($this, "handleHeaderInfo");
      
    // Cookie-Cache-class
    if (!class_exists("PHPCrawlerCookieCacheBase")) include_once($classpath."/CookieCache/PHPCrawlerCookieCacheBase.class.php");
    if (!class_exists("PHPCrawlerMemoryCookieCache")) include_once($classpath."/CookieCache/PHPCrawlerMemoryCookieCache.class.php");
    if (!class_exists("PHPCrawlerSQLiteCookieCache")) include_once($classpath."/CookieCache/PHPCrawlerSQLiteCookieCache.class.php");
    
    // URL-filter-class
    if (!class_exists("PHPCrawlerURLFilter")) include_once($classpath."/PHPCrawlerURLFilter.class.php");
    $this->UrlFilter = new PHPCrawlerURLFilter();
    
    // RobotsTxtParser-class
    if (!class_exists("PHPCrawlerRobotsTxtParser")) include_once($classpath."/PHPCrawlerRobotsTxtParser.class.php");
    $this->RobotsTxtParser = new PHPCrawlerRobotsTxtParser();
    
    // ProcessReport-class
    if (!class_exists("PHPCrawlerProcessReport")) include_once($classpath."/PHPCrawlerProcessReport.class.php");
    
    // UserSendDataCache-class
    if (!class_exists("PHPCrawlerUserSendDataCache")) include_once($classpath."/PHPCrawlerUserSendDataCache.class.php");
    $this->UserSendDataCache = new PHPCrawlerUserSendDataCache();
    
    // URLDescriptor-class
    if (!class_exists("PHPCrawlerURLDescriptor")) include_once($classpath."/PHPCrawlerURLDescriptor.class.php");
    
    // PageInfo-class
    if (!class_exists("PHPCrawlerDocumentInfo")) include_once($classpath."/PHPCrawlerDocumentInfo.class.php");
    
    // Benchmark-class
    if (!class_exists("PHPCrawlerBenchmark")) include_once($classpath."/PHPCrawlerBenchmark.class.php");
    
    // URLDescriptor-class
    if (!class_exists("PHPCrawlerUrlPartsDescriptor")) include_once($classpath."/PHPCrawlerUrlPartsDescriptor.class.php");
    
    // CrawlerStatus-class
    if (!class_exists("PHPCrawlerStatus")) include_once($classpath."/PHPCrawlerStatus.class.php");
    
    // AbortReasons-class
    if (!class_exists("PHPCrawlerAbortReasons")) include_once($classpath."/Enums/PHPCrawlerAbortReasons.class.php");
    
    // RequestErrors-class
    if (!class_exists("PHPCrawlerRequestErrors")) include_once($classpath."/Enums/PHPCrawlerRequestErrors.class.php");
    
    // PHPCrawlerUrlCacheTypes-class
    if (!class_exists("PHPCrawlerUrlCacheTypes")) include_once($classpath."/Enums/PHPCrawlerUrlCacheTypes.class.php");
    
    // PHPCrawlerMultiProcessModes-class
    if (!class_exists("PHPCrawlerMultiProcessModes")) include_once($classpath."/Enums/PHPCrawlerMultiProcessModes.class.php");
    
    // PHPCrawlerProcessCommunication-class
    if (!class_exists("PHPCrawlerProcessCommunication")) include_once($classpath."/ProcessCommunication/PHPCrawlerProcessCommunication.class.php");
    
    // PHPCrawlerDocumentInfoQueue-class
    if (!class_exists("PHPCrawlerDocumentInfoQueue")) include_once($classpath."/ProcessCommunication/PHPCrawlerDocumentInfoQueue.class.php");
    
    // Set default temp-dir
    $this->working_base_directory = PHPCrawlerUtils::getSystemTempDir();
  }
  
  /**
   * Initiates a crawler-process
   */
  protected function initCrawlerProcess()
  {
    // Create working directory
    $this->createWorkingDirectory();
    
    // Setup url-cache
    if ($this->url_cache_type == PHPCrawlerUrlCacheTypes::URLCACHE_SQLITE) 
      $this->LinkCache = new PHPCrawlerSQLiteURLCache($this->working_directory."urlcache.db3", true);
    else
      $this->LinkCache = new PHPCrawlerMemoryURLCache();
    
    // Perge/cleanup SQLite-urlcache for resumed crawling-processes (only ONCE!)
    if ($this->url_cache_type == PHPCrawlerUrlCacheTypes::URLCACHE_SQLITE && $this->urlcache_purged == false)
    {
      $this->LinkCache->purgeCache();
      $this->urlcache_purged = true;
    }
    
    // Setup cookie-cache (use SQLite-cache if crawler runs multi-processed)
    if ($this->url_cache_type == PHPCrawlerUrlCacheTypes::URLCACHE_SQLITE)
      $this->CookieCache = new PHPCrawlerSQLiteCookieCache($this->working_directory."cookiecache.db3", true);
    else $this->CookieCache = new PHPCrawlerMemoryCookieCache();
    
    // ProcessCommunication
    $this->ProcessCommunication = new PHPCrawlerProcessCommunication($this->crawler_uniqid, $this->multiprocess_mode, $this->working_directory, $this->resumtion_enabled);
    
    // DocumentInfo-Queue
    if ($this->multiprocess_mode == PHPCrawlerMultiProcessModes::MPMODE_PARENT_EXECUTES_USERCODE)
      $this->DocumentInfoQueue = new PHPCrawlerDocumentInfoQueue($this->working_directory."doc_queue.db3", true);
    
    // Set tmp-file for PageRequest
    $this->PageRequest->setTmpFile($this->working_directory."phpcrawl_".getmypid().".tmp");
    
    // Pass url-priorities to link-cache
    $this->LinkCache->addLinkPriorities($this->link_priority_array);
                
    // Pass base-URL to the UrlFilter
    $this->UrlFilter->setBaseURL($this->starting_url);
    
    // Add the starting-URL to the url-cache
    $this->LinkCache->addUrl(new PHPCrawlerURLDescriptor($this->starting_url));
  }
  
  /**
   * Starts the crawling process in single-process-mode.
   *
   * Be sure you did override the {@link handleDocumentInfo()}- or {@link handlePageData()}-method before calling the go()-method
   * to process the documents the crawler finds.
   *
   * @section 1 Basic settings
   */
  public function go()
  {
    // Process robots.txt
    if ($this->obey_robots_txt == true)
      $this->processRobotsTxt();
    
    $this->startChildProcessLoop();
  }
  
  /**
   * Starts the cralwer by using multi processes.
   * 
   * When using this method instead of the {@link go()}-method to start the crawler, phpcrawl will use the given
   * number of processes simultaneously for spidering the target-url.
   * Using multi processes will speed up the crawling-progress dramatically in most cases.
   *
   * There are some requirements though to successfully run the cralwler in multi-process mode:
   * <ul>
   * <li> The multi-process mode only works on unix-based systems (linux)</li>
   * <li> Scripts using the crawler have to be run from the commandline (cli)</li>
   * <li> The <a href="http://php.net/manual/en/pcntl.installation.php">PCNTL-extension</a> for php (process control) has to be installed and activated.</li>
   * <li> The <a href="http://php.net/manual/en/sem.installation.php">SEMAPHORE-extension</a> for php has to be installed and activated.</li>
   * <li>The <a href="http://de.php.net/manual/en/posix.installation.php">POSIX-extension</a> for php has to be installed and activated.</li>
   * <li> The <a href="http://de2.php.net/manual/en/pdo.installation.php">PDO-extension</a> together with the SQLite-driver (PDO_SQLITE) has to be installed and activated.</li>
   * </ul>
   *
   * PHPCrawls supports two different modes of multiprocessing:
   * <ol>
   * <li><b>{@link PHPCrawlerMultiProcessModes}::MPMODE_PARENT_EXECUTES_USERCODE</b>
   *
   * The cralwer uses multi processes simultaneously for spidering the target URL, but the usercode provided to
   * the overridable function {@link handleDocumentInfo()} gets always executed on the same main-process. This
   * means that the <b>usercode never gets executed simultaneously</b> and so you dont't have to care about
   * concurrent file/database/handle-accesses or smimilar things.
   * But on the other side the usercode may slow down the crawling-procedure because every child-process has to
   * wait until the usercode got executed on the main-process. <b>This ist the recommended multiprocess-mode!</b>
   * </li>
   * <li><b>{@link PHPCrawlerMultiProcessModes}::MPMODE_CHILDS_EXECUTES_USERCODE</b>
   *
   * The cralwer uses multi processes simultaneously for spidering the target URL, and every chld-process executes
   * the usercode provided to the overridable function {@link handleDocumentInfo()} directly from it's process. This
   * means that the <b>usercode gets executed simultaneously</b> by the different child-processes and you should 
   * take care of concurrent file/data/handle-accesses proberbly (if used).
   *
   * When using this mode and you use any handles like database-connections or filestreams in your extended
   * crawler-class, you should open them within the overridden mehtod {@link initChildProcess()} instead of opening
   * them from the constructor. For more details see the documentation of the {@link initChildProcess()}-method.
   * </li>
   * </ol>
   *
   * Example for starting the crawler with 5 processes using the recommended MPMODE_PARENT_EXECUTES_USERCODE-mode:
   * <code>
   * $crawler->goMultiProcessed(5, PHPCrawlerMultiProcessModes::MPMODE_PARENT_EXECUTES_USERCODE);
   * </code>
   *
   * Please note that increasing the number of processes to high values does't automatically mean that the crawling-process
   * will go off faster! Using 3 to 5 processes should be good values to start from.
   *
   * @param int $process_count     Number of processes to use
   * @param int $multiprocess_mode The multiprocess-mode to use.
   *                               One of the {@link PHPCrawlerMultiProcessModes}-constants
   * @section 1 Basic settings
   */
  public function goMultiProcessed($process_count = 3, $multiprocess_mode = 1)
  { 
    $this->multiprocess_mode = $multiprocess_mode;
    
    // Check if fork is supported
    if (!function_exists("pcntl_fork"))
    {
      throw new Exception("PHPCrawl running with multi processes not supported in this PHP-environment (function pcntl_fork() missing).".
                          "Try running from command-line (cli) and/or installing the PHP PCNTL-extension.");
    }
    
    if (!function_exists("sem_get"))
    {
      throw new Exception("PHPCrawl running with multi processes not supported in this PHP-environment (function sem_get() missing).".
                          "Try installing the PHP SEMAPHORE-extension.");
    }
    
    if (!function_exists("posix_kill"))
    {
      throw new Exception("PHPCrawl running with multi processes not supported in this PHP-environment (function posix_kill() missing).".
                          "Try installing the PHP POSIX-extension.");
    }
    
    if (!class_exists("PDO"))
    {
      throw new Exception("PHPCrawl running with multi processes not supported in this PHP-environment (class PDO missing).".
                          "Try installing the PHP PDO-extension.");
    }
    
    PHPCrawlerBenchmark::start("crawling_process");
    
    // Set url-cache-type to sqlite.
    $this->url_cache_type = PHPCrawlerUrlCacheTypes::URLCACHE_SQLITE;
    
    // Init process
    $this->initCrawlerProcess();
    
    // Process robots.txt
    if ($this->obey_robots_txt == true)
      $this->processRobotsTxt();
    
    // Fork off child-processes
    $pids = array();
    
    for($i=1; $i<=$process_count; $i++)
    {
      $pids[$i] = pcntl_fork();

      if(!$pids[$i])
      {   
        // Childprocess goes here
        $this->is_chlid_process = true;
        $this->child_process_number = $i;
        $this->ProcessCommunication->registerChildPID(getmypid());
        $this->startChildProcessLoop();
      }
    }
        
    // Set flag "parent-process"
    $this->is_parent_process = true;
    
    // Determinate all child-PIDs
    $this->child_pids = $this->ProcessCommunication->getChildPIDs($process_count);
    
    // If crawler runs in MPMODE_PARENT_EXECUTES_USERCODE-mode -> start controller-loop
    if ($this->multiprocess_mode == PHPCrawlerMultiProcessModes::MPMODE_PARENT_EXECUTES_USERCODE)
    {
      $this->starControllerProcessLoop();
    }
     
    // Wait for childs to finish
    for ($i=1; $i<=$process_count; $i++)
    {
      pcntl_waitpid($pids[$i], $status, WUNTRACED);
    }
    
    // Get crawler-status (needed for process-report)
    $this->crawlerStatus = $this->ProcessCommunication->getCrawlerStatus();
    
    // Cleanup crawler
    $this->cleanup();
    
    PHPCrawlerBenchmark::stop("crawling_process");
  }
  
  /**
   * Starts the loop of the controller-process (main-process).
   */
  protected function starControllerProcessLoop()
  {
    // If multiprocess-mode is not MPMODE_PARENT_EXECUTES_USERCODE -> exit process
    if ($this->multiprocess_mode != PHPCrawlerMultiProcessModes::MPMODE_PARENT_EXECUTES_USERCODE) exit;
    
    $this->initCrawlerProcess();
    $this->initChildProcess();
    
    while (true)
    { 
      // Check for abort
      if ($this->checkForAbort() !== null)
      {
        $this->ProcessCommunication->killChildProcesses();
        break;
      }
      
      // Get next DocInfo-object from queue
      $DocInfo = $this->DocumentInfoQueue->getNextDocumentInfo();
      
      if ($DocInfo == null)
      { 
        
        // If there are nor more links in cache AND there are no more DocInfo-objects in queue -> passedthrough
        if ($this->LinkCache->containsURLs() == false && $this->DocumentInfoQueue->getDocumentInfoCount() == 0)
        {
          $this->ProcessCommunication->updateCrawlerStatus(null, PHPCrawlerAbortReasons::ABORTREASON_PASSEDTHROUGH);
        }
        
        sleep(0.2);
        continue;
      }
      
      // Update crawler-status
      $this->ProcessCommunication->updateCrawlerStatus($DocInfo);
      
      // Call the "abstract" method handlePageData
      $user_abort = false;
      $page_info = $DocInfo->toArray();
      $user_return_value = $this->handlePageData($page_info);
      if ($user_return_value < 0) $user_abort = true;
      
      // Call the "abstract" method handleDocumentInfo
      $user_return_value = $this->handleDocumentInfo($DocInfo);
      if ($user_return_value < 0) $user_abort = true;
        
      // Update status if user aborted process
      if ($user_abort == true) 
        $this->ProcessCommunication->updateCrawlerStatus(null, PHPCrawlerAbortReasons::ABORTREASON_USERABORT);
    }
  }
  
  /**
   * Starts the loop of a child-process.
   */
  protected function startChildProcessLoop()
  { 
    $this->initCrawlerProcess();
    
    // Call overidable method initChildProcess()
    $this->initChildProcess();
    
    // Start benchmark (if single-processed)
    if ($this->is_chlid_process == false)
    {
      PHPCrawlerBenchmark::start("crawling_process");
    }
    
    // Init vars
    $stop_crawling = false;
    
    // Main-Loop
    while ($stop_crawling == false)
    { 
      // Get next URL from cache
      $UrlDescriptor = $this->LinkCache->getNextUrl();
      
      // Process URL
      if ($UrlDescriptor != null)
      {
        $stop_crawling = $this->processUrl($UrlDescriptor);
      }
      else
      {
        sleep(1);
      }
      
      if ($this->multiprocess_mode != PHPCrawlerMultiProcessModes::MPMODE_PARENT_EXECUTES_USERCODE)
      {
        // If there's nothing more to do
        if ($this->LinkCache->containsURLs() == false)
        {
          $stop_crawling = true;
          $this->ProcessCommunication->updateCrawlerStatus(null, PHPCrawlerAbortReasons::ABORTREASON_PASSEDTHROUGH);
        }
        
        // Check for abort form other processes
        if ($this->checkForAbort() !== null) $stop_crawling = true;
      }
    }

    // Loop enden gere. If child-process -> kill it
    if ($this->is_chlid_process == true)
    {
      if ($this->multiprocess_mode == PHPCrawlerMultiProcessModes::MPMODE_PARENT_EXECUTES_USERCODE) return;
      else exit;
    }
    
    $this->crawlerStatus = $this->ProcessCommunication->getCrawlerStatus();
       
    // Cleanup crawler
    $this->cleanup();
    
    // Stop benchmark (if single-processed)
    if ($this->is_chlid_process == false)
    {
      PHPCrawlerBenchmark::stop("crawling_process");
    }
  }
  
  /**
   * Receives and processes the given URL
   *
   * @param PHPCrawlerURLDescriptor $UrlDescriptor The URL as PHPCrawlerURLDescriptor-object
   * @return bool TURE if the crawling-process should be aborted after processig the URL, otherwise FALSE.
   */
  protected function processUrl(PHPCrawlerURLDescriptor $UrlDescriptor)
  { 
    PHPCrawlerBenchmark::start("processing_url");
    
    // Setup HTTP-request
    $this->PageRequest->setUrl($UrlDescriptor);
    
    // Add cookies to request
    if ($this->cookie_handling_enabled == true)
      $this->PageRequest->addCookieDescriptors($this->CookieCache->getCookiesForUrl($UrlDescriptor->url_rebuild));
    
    // Add basic-authentications to request
    $authentication = $this->UserSendDataCache->getBasicAuthenticationForUrl($UrlDescriptor->url_rebuild);
    if ($authentication != null)
    {
      $this->PageRequest->setBasicAuthentication($authentication["username"], $authentication["password"]);
    }
    
    // Add post-data to request
    $post_data = $this->UserSendDataCache->getPostDataForUrl($UrlDescriptor->url_rebuild);
    while (list($post_key, $post_value) = @each($post_data))
    {
      $this->PageRequest->addPostData($post_key, $post_value);
    }
    
    // Do request
    $PageInfo = $this->PageRequest->sendRequest();
    
    if ($this->multiprocess_mode != PHPCrawlerMultiProcessModes::MPMODE_PARENT_EXECUTES_USERCODE)
    {
      // Check for abort
      $abort_reason = $this->checkForAbort();
      if ($abort_reason !== null) return true;
      
      $this->ProcessCommunication->updateCrawlerStatus($PageInfo);
    }
    
    // Remove post and cookie-data from request-object
    $this->PageRequest->clearCookies();
    $this->PageRequest->clearPostData();
    
    // Call user-moethods if crawler doesn't run in MPMODE_PARENT_EXECUTES_USERCODE
    if ($this->multiprocess_mode != PHPCrawlerMultiProcessModes::MPMODE_PARENT_EXECUTES_USERCODE)
    {
      // Call the "abstract" method handlePageData
      $user_abort = false;
      $page_info = $PageInfo->toArray();
      $user_return_value = $this->handlePageData($page_info);
      if ($user_return_value < 0) $user_abort = true;
      
      // Call the "abstract" method handleDocumentInfo
      $user_return_value = $this->handleDocumentInfo($PageInfo);
      if ($user_return_value < 0) $user_abort = true;
      
      // Update status if user aborted process
      if ($user_abort == true) 
      {
        $this->ProcessCommunication->updateCrawlerStatus(null, PHPCrawlerAbortReasons::ABORTREASON_USERABORT);
      }
      
      // Check for abort from other processes
      if ($this->checkForAbort() !== null) return true;
    }
    
    // Filter found URLs by defined rules
    if ($this->follow_redirects_till_content == true)
    {
      $crawler_status = $this->ProcessCommunication->getCrawlerStatus();
      
      // If content wasn't found so far and content was found NOW
      if ($crawler_status->first_content_url == null && $PageInfo->http_status_code == 200)
      {
        $this->ProcessCommunication->updateCrawlerStatus(null, null, $PageInfo->url);
        $this->UrlFilter->setBaseURL($PageInfo->url); // Set current page as base-URL
        $this->UrlFilter->filterUrls($PageInfo);
        $this->follow_redirects_till_content = false; // Content was found, so this can be set to FALSE
      }
      else if ($crawler_status->first_content_url == null)
      {
        $this->UrlFilter->keepRedirectUrls($PageInfo); // Content wasn't found so far, so just keep redirect-urls 
      }
      else if ($crawler_status->first_content_url != null)
      {
        $this->follow_redirects_till_content = false;
        $this->UrlFilter->filterUrls($PageInfo);
      }
    }
    else
    {
      $this->UrlFilter->filterUrls($PageInfo);
    }
    
    // Add Cookies to Cookie-cache
    if ($this->cookie_handling_enabled == true) $this->CookieCache->addCookies($PageInfo->cookies);

    // Add filtered links to URL-cache
    $this->LinkCache->addURLs($PageInfo->links_found_url_descriptors);
    
    PHPCrawlerBenchmark::stop("processing_url");
    
    // Complete PageInfo-Object with benchmarks
    $PageInfo->benchmarks = PHPCrawlerBenchmark::getAllBenchmarks();
    
    if ($this->multiprocess_mode == PHPCrawlerMultiProcessModes::MPMODE_PARENT_EXECUTES_USERCODE)
    {
      $this->DocumentInfoQueue->addDocumentInfo($PageInfo);
    }
    
     // Mark URL as "followed"
    $this->LinkCache->markUrlAsFollowed($UrlDescriptor);
    
    PHPCrawlerBenchmark::resetAll(array("crawling_process"));
    
    return false;
  }
  
  protected function processRobotsTxt()
  {
    PHPCrawlerBenchmark::start("processing_robots_txt");
    $robotstxt_rules = $this->RobotsTxtParser->parseRobotsTxt(new PHPCrawlerURLDescriptor($this->starting_url), $this->PageRequest->userAgentString);
    $this->UrlFilter->addURLFilterRules($robotstxt_rules);
    PHPCrawlerBenchmark::stop("processing_robots_txt");
  }
  
  /**
   * Checks if the crawling-process should be aborted.
   *
   * @return int NULL if the process shouldn't be aborted yet, otherwise one of the PHPCrawlerAbortReasons::ABORTREASON-constants.
   */
  protected function checkForAbort()
  {
    PHPCrawlerBenchmark::start("checkning_for_abort");
    
    $abort_reason = null;
     
    // Get current status
    $crawler_status = $this->ProcessCommunication->getCrawlerStatus();
    
    // if crawlerstatus already marked for ABORT
    if ($crawler_status->abort_reason !== null)
    {
      $abort_reason = $crawler_status->abort_reason;
    }
    
    // Check for reached limits
    
    // If traffic-limit is reached
    if ($this->traffic_limit > 0 && $crawler_status->bytes_received >= $this->traffic_limit)
      $abort_reason = PHPCrawlerAbortReasons::ABORTREASON_TRAFFICLIMIT_REACHED;
    
    // If document-limit is set
    if ($this->document_limit > 0)
    {
      // If document-limit regards to received documetns
      if ($this->only_count_received_documents == true && $crawler_status->documents_received >= $this->document_limit)
      {
        $abort_reason = PHPCrawlerAbortReasons::ABORTREASON_FILELIMIT_REACHED;
      }
      elseif ($this->only_count_received_documents == false && $crawler_status->links_followed >= $this->document_limit)
      {
        $abort_reason = PHPCrawlerAbortReasons::ABORTREASON_FILELIMIT_REACHED;
      }
    }
    
    $this->ProcessCommunication->updateCrawlerStatus(null, $abort_reason);
    
    PHPCrawlerBenchmark::stop("checkning_for_abort");
    
    return $abort_reason;
  }
  
  /**
   * Creates the working-directory for this instance of the cralwer.
   */
  protected function createWorkingDirectory()
  {
    $this->working_directory = $this->working_base_directory."phpcrawl_tmp_".$this->crawler_uniqid.DIRECTORY_SEPARATOR;
    
    // Check if writable
    if (!is_writeable($this->working_base_directory))
    {
      throw new Exception("Error creating working directory '".$this->working_directory."'");
    }
    
    // Create dir
    if (!file_exists($this->working_directory))
    {
      mkdir($this->working_directory);
    }
  }
  
  /**
   * Cleans up the crawler after it has finished.
   */
  protected function cleanup()
  {
    // Delete working-dir
    PHPCrawlerUtils::rmDir($this->working_directory);
    
    // Remove semaphore (if multiprocess-mode)
    if ($this->multiprocess_mode != PHPCrawlerMultiProcessModes::MPMODE_NONE)
    {
      $sem_key = sem_get($this->crawler_uniqid);
      sem_remove($sem_key);
    }
  }
  
  /**
   * Retruns summarizing report-information about the crawling-process after it has finished.
   *
   * @return PHPCrawlerProcessReport PHPCrawlerProcessReport-object containing process-summary-information
   * @section 1 Basic settings
   */
  public function getProcessReport()
  { 
    // Get current crawler-Status
    $CrawlerStatus = $this->crawlerStatus;
    
    // Create report
    $Report = new PHPCrawlerProcessReport();
    
    $Report->links_followed = $CrawlerStatus->links_followed;
    $Report->files_received = $CrawlerStatus->documents_received;
    $Report->bytes_received = $CrawlerStatus->bytes_received;
    $Report->process_runtime = PHPCrawlerBenchmark::getElapsedTime("crawling_process");
    
    if ($Report->process_runtime > 0)
      $Report->data_throughput = $Report->bytes_received / $Report->process_runtime;
    
    // Process abort-reason
    $Report->abort_reason = $CrawlerStatus->abort_reason;
    
    if ($CrawlerStatus->abort_reason == PHPCrawlerAbortReasons::ABORTREASON_TRAFFICLIMIT_REACHED)
      $Report->traffic_limit_reached = true;
    
    if ($CrawlerStatus->abort_reason == PHPCrawlerAbortReasons::ABORTREASON_FILELIMIT_REACHED)
      $Report->file_limit_reached = true;
    
    if ($CrawlerStatus->abort_reason == PHPCrawlerAbortReasons::ABORTREASON_USERABORT)
      $Report->user_abort = true;
    
    // Peak memory-usage
    if (function_exists("memory_get_peak_usage"))
      $Report->memory_peak_usage = memory_get_peak_usage(true);
    
    return $Report;
  }
  
  /**
   * Retruns an array with summarizing report-information after the crawling-process has finished
   *
   * For detailed information on the conatining array-keys see PHPCrawlerProcessReport-class.
   * 
   * @deprecated Please use getProcessReport() instead.
   * @section 11 Deprecated
   */
  public function getReport()
  {
    return $this->getProcessReport()->toArray();
  }
  
  /**
   * Overridable method that will be called after the header of a document was received and BEFORE the content
   * will be received.
   *
   * Everytime a header of a document was received, the crawler will call this method.
   * If this method returns any negative integer, the crawler will NOT reveice the content of the particular page or file.
   *
   * Example:
   * <code>
   * class MyCrawler extends PHPCrawler 
   * {
   *   function handleHeaderInfo(PHPCrawlerResponseHeader $header)
   *   {
   *     // If the content-type of the document isn't "text/html" -> don't receive it.
   *     if ($header->content_type != "text/html")
   *     {
   *       return -1;
   *     }   
   *   }
   * 
   *   function handleDocumentInfo($PageInfo)
   *   {
   *     // ...
   *   }
   * }
   * </code>
   *
   * @param PHPCrawlerResponseHeader $header The header as PHPCrawlerResponseHeader-object
   * @return int                             The document won't be received if you let this method return any negative value.
   * @section 3 Overridable methods / User data-processing
   */
  public function handleHeaderInfo(PHPCrawlerResponseHeader $header)
  {
    return 1;
  }
  
  /**
   * Overridable method that will be called by every used child-process just before it starts the crawling-procedure.
   *
   * Every child-process of the crawler will call this method just before it starts it's crawling-loop from within it's
   * process-context.
   *
   * So when using the multi-process mode "{@link PHPCrawlerMultiProcessModes::MPMODE_CHILDS_EXECUTES_USERCODE}", this method
   * should be overidden and used to open any needed database-connections, file streams or other similar handles to ensure
   * that they will get opened and accessible for every used child-process.
   *
   * Example:
   * <code>
   * class MyCrawler extends PHPCrawler 
   * {
   *   protected $mysql_link;
   *
   *   function initChildProcess()
   *   {
   *     // Open a database-connection for every used process
   *     $this->mysql_link = mysql_connect("myhost", "myusername", "mypassword");
   *     mysql_select_db ("mydatabasename", $this->mysql_link);
   *   }
   * 
   *   function handleDocumentInfo($PageInfo) 
   *   {
   *     mysql_query("INSERT INTO urls SET url = '".$PageInfo->url."';", $this->mysql_link);
   *   }
   * }
   *
   * // Start crawler with 5 processes
   * $crawler = new MyCrawler();
   * $crawler->setURL("http://www.any-url.com");
   * $crawler->goMultiProcessed(5, PHPCrawlerMultiProcessModes::MPMODE_CHILDS_EXECUTES_USERCODE);
   * </code>
   *
   * @section 3 Overridable methods / User data-processing
   */
  public function initChildProcess()
  {
  }
  
  /**
   * Override this method to get access to all information about a page or file the crawler found and received.
   *
   * Everytime the crawler found and received a document on it's way this method will be called.
   * The crawler passes all information about the currently received page or file to this method
   * by the array $page_data.
   *
   * @param array &$page_data Array containing all information about the currently received document.
   *                          For detailed information on the conatining keys see {@link PHPCrawlerDocumentInfo}-class.
   * @return int              The crawling-process will stop immedeatly if you let this method return any negative value.
   * @deprecated Please use and override the {@link handleDocumentInfo}-method to access document-information instead.
   * @section 3 Overridable methods / User data-processing
   */
  public function handlePageData(&$page_data){}
  
  /**
   * Override this method to get access to all information about a page or file the crawler found and received.
   *
   * Everytime the crawler found and received a document on it's way this method will be called.
   * The crawler passes all information about the currently received page or file to this method
   * by a PHPCrawlerDocumentInfo-object.
   *
   * Please see the {@link PHPCrawlerDocumentInfo} documentation for a list of all properties describing the
   * html-document.
   *
   * Example:
   * <code>
   * class MyCrawler extends PHPCrawler
   * {
   *   function handleDocumentInfo($PageInfo)
   *   {
   *     // Print the URL of the document
   *     echo "URL: ".$PageInfo->url."<br />";
   *
   *     // Print the http-status-code
   *     echo "HTTP-statuscode: ".$PageInfo->http_status_code."<br />";
   *
   *     // Print the number of found links in this document
   *     echo "Links found: ".count($PageInfo->links_found_url_descriptors)."<br />";
   *     
   *     // ..
   *   }
   * }
   * </code>
   *
   * @param PHPCrawlerDocumentInfo $PageInfo A PHPCrawlerDocumentInfo-object containing all information about the currently received document.
   *                                         Please see the reference of the {@link PHPCrawlerDocumentInfo}-class for detailed information.
   * @return int                             The crawling-process will stop immedeatly if you let this method return any negative value.
   *
   * @section 3 Overridable methods / User data-processing
   */
  public function handleDocumentInfo(PHPCrawlerDocumentInfo $PageInfo){}
  
  /**
   * Sets the URL of the first page the crawler should crawl (root-page).
   *
   * The given url may contain the protocol (http://www.foo.com or https://www.foo.com), the port (http://www.foo.com:4500/index.php)
   * and/or basic-authentication-data (http://loginname:passwd@www.foo.com)
   *
   * This url has to be set before calling the {@link go()}-method (of course)!
   * If this root-page doesn't contain any further links, the crawling-process will stop immediately.
   *
   * @param string $url The URL
   * @return bool
   *
   * @section 1 Basic settings
   */
  public function setURL($url)
  {
    $url = trim($url);
    
    if ($url != "" && is_string($url))
    {
      $this->starting_url = PHPCrawlerUtils::normalizeURL($url);
      return true;
    }
    else return false;
  }
  
  /**
   * Sets the port to connect to for crawling the starting-url set in setUrl().
   *
   * The default port is 80.
   *
   * Note:
   * <code>
   * $cralwer->setURL("http://www.foo.com");
   * $crawler->setPort(443);
   * </code>
   * effects the same as
   * 
   * <code>
   * $cralwer->setURL("http://www.foo.com:443");
   * </code>
   *
   * @param int $port The port
   * @return bool
   * @section 1 Basic settings
   */
  public function setPort($port)
  {
    // Check port
    if (!preg_match("#^[0-9]{1,5}$#", $port)) return false;

    // Add port to the starting-URL
    $url_parts = PHPCrawlerUtils::splitURL($this->starting_url);
    $url_parts["port"] = $port;
    $this->starting_url = PHPCrawlerUtils::buildURLFromParts($url_parts, true);
    
    return true;
  }
  
  /**
   * Adds a regular expression togehter with a priority-level to the list of rules that decide what links should be prefered.
   *
   * Links/URLs that match an expression with a high priority-level will be followed before links with a lower level.
   * All links that don't match with any of the given rules will get the level 0 (lowest level) automatically.
   *
   * The level can be any positive integer.
   *
   * <b>Example:</b>
   *
   * Telling the crawler to follow links that contain the string "forum" before links that contain ".gif" before all other found links.
   * <code>
   * $crawler->addLinkPriority("/forum/", 10);
   * $cralwer->addLinkPriority("/\.gif/", 5);
   * </code>
   *
   * @param string $regex  Regular expression definig the rule
   * @param int    $level  The priority-level
   *
   * @return bool  TRUE if a valid preg-pattern is given as argument and was succsessfully added, otherwise it returns FALSE.
   * @section 10 Other settings
   */
  function addLinkPriority($regex, $level)
  {
    $check = PHPCrawlerUtils::checkRegexPattern($regex); // Check pattern
    if ($check == true && preg_match("/^[0-9]*$/", $level))
    {
      $c = count($this->link_priority_array);
      $this->link_priority_array[$c]["match"] = trim($regex);
      $this->link_priority_array[$c]["level"] = trim($level);
    
      return true;
    }
    else return false;
  }
  
  /**
   * Defines whether the crawler should follow redirects sent with headers by a webserver or not.
   *
   * @param bool $mode  If TRUE, the crawler will follow header-redirects.
   *                    The default-value is TRUE.
   * @return bool
   * @section 10 Other settings
   */
  public function setFollowRedirects($mode)
  {
    return $this->PageRequest->setFindRedirectURLs($mode);
  }
  
  /**
   * Defines whether the crawler should follow HTTP-redirects until first content was found, regardless of defined filter-rules and follow-modes.
   *
   * Sometimes, when requesting an URL, the first thing the webserver does is sending a redirect to
   * another location, and sometimes the server of this new location is sending a redirect again
   * (and so on). 
   * So at least its possible that you find the expected content on a totally different host
   * as expected.
   *
   * If you set this option to TRUE, the crawler will follow all these redirects until it finds some content.
   * If content finally was found, the root-url of the crawling-process will be set to this url and all
   * defined options (folllow-mode, filter-rules etc.) will relate to it from now on.
   *
   * @param bool $mode If TRUE, the crawler will follow redirects until content was finally found.
   *                   Defaults to TRUE.
   * @section 10 Other settings
   */
  public function setFollowRedirectsTillContent($mode)
  {
    $this->follow_redirects_till_content = $mode;
  }
  
  /**
   * Sets the basic follow-mode of the crawler.
   *
   * The following list explains the supported follow-modes:
   *
   * <b>0 - The crawler will follow EVERY link, even if the link leads to a different host or domain.</b>
   * If you choose this mode, you really should set a limit to the crawling-process (see limit-options),
   * otherwise the crawler maybe will crawl the whole WWW!
   *
   * <b>1 - The crawler only follow links that lead to the same domain like the one in the root-url.</b>
   * E.g. if the root-url (setURL()) is "http://www.foo.com", the crawler will follow links to "http://www.foo.com/..."
   * and "http://bar.foo.com/...", but not to "http://www.another-domain.com/...".
   *
   * <b>2 - The crawler will only follow links that lead to the same host like the one in the root-url.</b>
   * E.g. if the root-url (setURL()) is "http://www.foo.com", the crawler will ONLY follow links to "http://www.foo.com/...", but not
   * to "http://bar.foo.com/..." and "http://www.another-domain.com/...". <b>This is the default mode.</b>
   *
   * <b>3 - The crawler only follows links to pages or files located in or under the same path like the one of the root-url.</b>
   * E.g. if the root-url is "http://www.foo.com/bar/index.html", the crawler will follow links to "http://www.foo.com/bar/page.html" and
   * "http://www.foo.com/bar/path/index.html", but not links to "http://www.foo.com/page.html".
   *
   * @param int $follow_mode The basic follow-mode for the crawling-process (0, 1, 2 or 3).
   * @return bool
   *
   * @section 1 Basic settings
   */
  public function setFollowMode($follow_mode)
  {
    // Check mode
    if (!preg_match("/^[0-3]{1}$/", $follow_mode)) return false;
    
    $this->UrlFilter->general_follow_mode = $follow_mode;
    return true;
  }
  
  /**
   * Adds a rule to the list of rules that decides which pages or files - regarding their content-type - should be received
   *
   * After receiving the HTTP-header of a followed URL, the crawler check's - based on the given rules - whether the content of that URL
   * should be received.
   * If no rule matches with the content-type of the document, the content won't be received.
   *
   * Example:
   * <code>
   * $crawler->addContentTypeReceiveRule("#text/html#");
   * $crawler->addContentTypeReceiveRule("#text/css#");
   * </code>
   * This rules lets the crawler receive the content/source of pages with the Content-Type "text/html" AND "text/css".
   * Other pages or files with different content-types (e.g. "image/gif") won't be received (if this is the only rule added to the list).
   *
   * <b>IMPORTANT:</b> By default, if no rule was added to the list, the crawler receives every content.
   *
   * Note: To reduce the traffic the crawler will cause, you only should add content-types of pages/files you really want to receive.
   * But at least you should add the content-type "text/html" to this list, otherwise the crawler can't find any links.
   *
   * @param string $regex The rule as a regular-expression
   * @return bool TRUE if the rule was added to the list.
   *              FALSE if the given regex is not valid.
   * @section 2 Filter-settings
   */
  public function addContentTypeReceiveRule($regex)
  {
    return $this->PageRequest->addReceiveContentType($regex);
  }
  
  /**
   * Alias for addContentTypeReceiveRule().
   *
   * @section 11 Deprecated
   * @deprecated
   * 
   */
  public function addReceiveContentType($regex)
  {
    return $this->addContentTypeReceiveRule($regex);
  }
  
  /**
   * Adds a rule to the list of rules that decide which URLs found on a page should be followd explicitly.
   *
   * If the crawler finds an URL and this URL doesn't match with any of the given regular-expressions, the crawler
   * will ignore this URL and won't follow it.
   *
   * NOTE: By default and if no rule was added to this list, the crawler will NOT filter ANY URLs, every URL the crawler finds
   * will be followed (except the ones "excluded" by other options of course).
   *
   * Example:
   * <code>
   * $crawler->addURLFollowRule("#(htm|html)$# i");
   * $crawler->addURLFollowRule("#(php|php3|php4|php5)$# i");
   * </code>
   * These rules let the crawler ONLY follow URLs/links that end with "html", "htm", "php", "php3" etc.
   *
   * @param string $regex Regular-expression defining the rule
   * @return bool TRUE if the regex is valid and the rule was added to the list, otherwise FALSE.
   *
   * @section 2 Filter-settings
   */
  public function addURLFollowRule($regex)
  {
    return $this->UrlFilter->addURLFollowRule($regex);
  }
  
  /**
   * Adds a rule to the list of rules that decide which URLs found on a page should be ignored by the crawler.
   *
   * If the crawler finds an URL and this URL matches with one of the given regular-expressions, the crawler
   * will ignore this URL and won't follow it.
   *
   * Example:
   * <code>
   * $crawler->addURLFilterRule("#(jpg|jpeg|gif|png|bmp)$# i");
   * $crawler->addURLFilterRule("#(css|js)$# i");
   * </code>
   * These rules let the crawler ignore URLs that end with "jpg", "jpeg", "gif", ..., "css"  and "js".
   *
   * @param string $regex Regular-expression defining the rule
   * @return bool TRUE if the regex is valid and the rule was added to the list, otherwise FALSE.
   *
   * @section 2 Filter-settings
   */
  public function addURLFilterRule($regex)
  {
    return $this->UrlFilter->addURLFilterRule($regex);
  }
  
  /**
   * Alias for addURLFollowRule().
   *
   * @section 11 Deprecated
   * @deprecated
   * 
   */
  public function addFollowMatch($regex)
  {
    return $this->addURLFollowRule($regex);
  }
  
  /**
   * Alias for addURLFilterRule().
   *
   * @section 11 Deprecated
   * @deprecated
   * 
   */
  public function addNonFollowMatch($regex)
  {
    return $this->addURLFilterRule($regex);
  }
  
  /**
   * Adds a rule to the list of rules that decides what types of content should be streamed diretly to a temporary file.
   *
   * If a content-type of a page or file matches with one of these rules, the content will be streamed directly into a
   * temporary file without claiming local RAM.
   *
   * It's recommendend to add all content-types of files that may be of bigger size to prevent memory-overflows.
   * By default the crawler will receive every content to memory!
   *
   * The content/source of pages and files that were streamed to file are not accessible directly within the overidden method
   * {@link handleDocumentInfo()}, instead you get information about the file the content was stored in.
   * (see properties {@link PHPCrawlerDocumentInfo::received_to_file} and {@link PHPCrawlerDocumentInfo::content_tmp_file}).
   *
   * Please note that this setting doesn't effect the link-finding results, also file-streams will be checked for links.
   *
   * A common setup may look like this example:
   * <code>
   * // Basically let the crawler receive every content (default-setting)
   * $crawler->addReceiveContentType("##");
   *
   * // Tell the crawler to stream everything but "text/html"-documents to a tmp-file
   * $crawler->addStreamToFileContentType("#^((?!text/html).)*$#");
   * </code>
   * 
   * @param string $regex The rule as a regular-expression
   * @return bool         TRUE if the rule was added to the list and the regex is valid.
   * @section 10 Other settings
   */
  public function addStreamToFileContentType($regex)
  {
    return $this->PageRequest->addStreamToFileContentType($regex);
  }
  
  /**
   * Has no function anymore.
   *
   * Please use setWorkingDirectory()
   *
   * @deprecated This method has no function anymore since v 0.8.
   * @section 11 Deprecated
   */
  public function setTmpFile($tmp_file)
  {
  }
  
  /**
   * Decides whether the crawler should parse and obey robots.txt-files. 
   *
   * If this is set to TRUE, the crawler looks for a robots.txt-file for every host that sites or files should be received
   * from during the crawling process. If a robots.txt-file for a host was found, the containig directives appliying to the
   * useragent-identification of the cralwer
   * ("PHPCrawl" or manually set by calling {@link setUserAgentString()}) will be obeyed.
   *
   * The default-value is FALSE (for compatibility reasons).
   *
   * Pleas note that the directives found in a robots.txt-file have a higher priority than other settings made by the user.
   * If e.g. {@link addFollowMatch}("#http://foo\.com/path/file\.html#") was set, but a directive in the robots.txt-file of the host
   * foo.com says "Disallow: /path/", the URL http://foo.com/path/file.html will be ignored by the crawler anyway.
   *
   * @param bool $mode Set to TRUE if you want the crawler to obey robots.txt-files.
   * @return bool
   * @section 2 Filter-settings
   */
  public function obeyRobotsTxt($mode)
  {
    if (!is_bool($mode)) return false;
    
    $this->obey_robots_txt = $mode;
    return true;
  }
  
  /**
   * Alias for addStreamToFileContentType().
   *
   * @deprecated
   * @section 11 Deprecated
   */ 
  public function addReceiveToTmpFileMatch($regex)
  {
    return $this->addStreamToFileContentType($regex);
  }
  
  /**
   * Has no function anymore!
   *
   * This method was redundant, please use addStreamToFileContentType().
   * It just still exists because of compatibility-reasons.
   *
   * @deprecated This method has no function anymore since v 0.8.
   * @section 11 Deprecated
   */ 
  public function addReceiveToMemoryMatch($regex)
  {
    return true;
  }
  
  /**
   * Sets a limit to the number of pages/files the crawler should follow.
   *
   * If the limit is reached, the crawler stops the crawling-process. The default-value is 0 (no limit).
   *
   * @param int $limit                          The limit, set to 0 for no limit (default value).
   * @param bool $only_count_received_documents OPTIONAL.
   *                                            TRUE means that only documents the crawler received will be counted.
   *                                            FALSE means that ALL followed and requested pages/files will be counted, even if the content wasn't be received.
   * @section 5 Limit-settings
   */
  public function setPageLimit($limit, $only_count_received_documents = false)
  {
    if (!preg_match("/^[0-9]*$/", $limit)) return false;
    
    $this->document_limit = $limit;
    $this->only_count_received_documents = $only_count_received_documents;
    return true;
  }
  
  /**
   * Sets the content-size-limit for content the crawler should receive from documents.
   *
   * If the crawler is receiving the content of a page or file and the contentsize-limit is reached, the crawler stops receiving content
   * from this page or file.
   *
   * Please note that the crawler can only find links in the received portion of a document.
   * 
   * The default-value is 0 (no limit).
   *
   * @param int $bytes The limit in bytes.
   * @return bool
   * @section 5 Limit-settings
   */
  public function setContentSizeLimit($bytes)
  {
    return $this->PageRequest->setContentSizeLimit($bytes);
  }
  
  /**
   * Sets a limit to the number of bytes the crawler should receive alltogether during crawling-process.
   *
   * If the limit is reached, the crawler stops the crawling-process.
   * The default-value is 0 (no limit).
   *
   * @param int $bytes Maximum number of bytes
   * @param bool $complete_requested_files This parameter has no function anymore!
   *
   * @return bool
   * @section 5 Limit-settings
   */
  public function setTrafficLimit($bytes, $complete_requested_files = true)
  {
    if (preg_match("#^[0-9]*$#", $bytes))
    {
      $this->traffic_limit = $bytes;
      return true;
    }
    else return false;
  }
  
  /**
   * Enables or disables cookie-handling.
   *
   * If cookie-handling is set to TRUE, the crawler will handle all cookies sent by webservers just like a common browser does.
   * The default-value is TRUE.
   *
   * It's strongly recommended to set or leave the cookie-handling enabled!
   *
   * @param bool $mode
   * @return bool
   * @section 10 Other settings
   */
  public function enableCookieHandling($mode)
  {
    if (!is_bool($mode)) return false;
    
    $this->cookie_handling_enabled = $mode;
    return true;
  }
  
  /**
   * Alias for enableCookieHandling()
   *
   * @section 11 Deprecated
   * @deprecated Please use enableCookieHandling()
   */
  public function setCookieHandling($mode)
  {
    return $this->enableCookieHandling($mode);
  }
  
  /**
   * Enables or disables agressive link-searching.
   *
   * If this is set to FALSE, the crawler tries to find links only inside html-tags (< and >).
   * If this is set to TRUE, the crawler tries to find links everywhere in an html-page, even outside of html-tags.
   * The default value is TRUE.
   *
   * Please note that if agressive-link-searching is enabled, it happens that the crawler will find links that are not meant as links and it also happens that it
   * finds links in script-parts of pages that can't be rebuild correctly - since there is no javascript-parser/interpreter implemented.
   * (E.g. javascript-code like document.location.href= a_var + ".html").
   *
   * Disabling agressive-link-searchingn results in a better crawling-performance.
   *
   * @param bool $mode
   * @return bool
   * @section 6 Linkfinding settings 
   */
  public function enableAggressiveLinkSearch($mode)
  {
    return $this->PageRequest->enableAggressiveLinkSearch($mode);
  }
  
  /**
   * Alias for enableAggressiveLinkSearch()
   *
   * @section 11 Deprecated
   * @deprecated Please use enableAggressiveLinkSearch()
   */
  public function setAggressiveLinkExtraction($mode)
  {
    return $this->enableAggressiveLinkSearch($mode);
  }
  
  /**
   * Sets the list of html-tags the crawler should search for links in.
   *
   * By default the crawler searches for links in the following html-tags: href, src, url, location, codebase, background, data, profile, action and open.
   * As soon as the list is set manually, this default list will be overwritten completly.
   *
   * Example:
   * <code>$crawler->setLinkExtractionTags(array("href", "src"));</code>
   * This setting lets the crawler search for links (only) in "href" and "src"-tags.
   *
   * Note: Reducing the number of tags in this list will improve the crawling-performance (a little).
   *
   * @param array $tag_array Numeric array containing the tags.
   * @section 6 Linkfinding settings
   */
  public function setLinkExtractionTags($tag_array)
  {
    return $this->PageRequest->setLinkExtractionTags($tag_array);
  }
  
  /**
   * Sets the list of html-tags from which links should be extracted from.
   *
   * This method was named wrong in previous versions of phpcrawl.
   * It does not ADD tags, it SETS the tags from which links should be extracted from.
   * 
   * Example
   * <code>$crawler->addLinkExtractionTags("href", "src");</code>
   *
   * @section 11 Deprecated
   * @deprecated Please use setLinkExtractionTags()
   */
  public function addLinkExtractionTags()
  {
    $tags = func_get_args();
    return $this->setLinkExtractionTags($tags);
  }
  
  /**
   * Adds a basic-authentication (username and password) to the list of basic authentications that will be send with requests.
   *
   * Example:
   * <code>
   * $crawler->addBasicAuthentication("#http://www\.foo\.com/protected_path/#", "myusername", "mypasswd");
   * </code>
   * This lets the crawler send the authentication "myusername/mypasswd" with every request for content placed
   * in the path "protected_path" on the host "www.foo.com".
   *
   * @param string $url_regex Regular-expression defining the URL(s) the authentication should be send to.
   * @param string $username  The username
   * @param string $password  The password
   *
   * @return bool
   *
   * @section 10 Other settings
   */
  public function addBasicAuthentication($url_regex, $username, $password)
  {
    return $this->UserSendDataCache->addBasicAuthentication($url_regex, $username, $password);
  }
  
  /**
   * Sets the "User-Agent" identification-string that will be send with HTTP-requests.
   *
   * @param string $user_agent The user-agent-string. The default-value is "PHPCrawl".
   * @section 10 Other settings
   */
  public function setUserAgentString($user_agent)
  {
    $this->PageRequest->userAgentString = $user_agent;
    return true;
  }
  
  /**
   * Has no function anymore.
   *
   * Thes method has no function anymore, just still exists because of compatibility-reasons.
   *
   * @section 11 Deprecated
   * @deprecated
   */
  public function disableExtendedLinkInfo($mode)
  {
  }
  
  /**
   * Sets the working-directory the crawler should use for storing temporary data.
   *
   * Every instance of the crawler needs and creates a temporary directory for storing some
   * internal data.
   *
   * This setting defines which base-directory the crawler will use to store the temporary
   * directories in. By default, the crawler uses the systems temp-directory as working-directory.
   * (i.e. "/tmp/" on linux-systems)
   *
   * All temporary directories created in the working-directory will be deleted automatically
   * after a crawling-process has finished.
   *
   * NOTE: To speed up the performance of a crawling-process (especially when using the
   * SQLite-urlcache), try to set a mounted shared-memory device as working-direcotry
   * (i.e. "/dev/shm/" on Debian/Ubuntu-systems).
   *
   * Example:
   * <code>
   * $crawler->setWorkingDirectory("/tmp/");
   * </code>
   *
   * @param string $directory The working-directory
   * @return bool             TRUE on success, otherwise false.
   * @section 1 Basic settings
   */
  public function setWorkingDirectory($directory)
  {
    if (is_writeable($this->working_base_directory))
    {
      $this->working_base_directory = $directory;
      return true;
    }
    else return false;
  }
  
  /**
   * Assigns a proxy-server the crawler should use for all HTTP-Requests.
   *
   * @param string $proxy_host     Hostname or IP of the proxy-server
   * @param int    $proxy_port     Port of the proxy-server
   * @param string $proxy_username Optional. The username for proxy-authentication or NULL if no authentication is required.
   * @param string $proxy_password Optional. The password for proxy-authentication or NULL if no authentication is required.
   *
   * @section 10 Other settings
   */
  public function setProxy($proxy_host, $proxy_port, $proxy_username = null, $proxy_password = null)
  {
    $this->PageRequest->setProxy($proxy_host, $proxy_port, $proxy_username, $proxy_password);
  }
  
  /**
   * Sets the timeout in seconds for connection tries to hosting webservers.
   *
   * If the the connection to a host can't be established within the given time, the
   * request will be aborted.
   *
   * @param int $timeout The timeout in seconds, the default-value is 5 seconds.
   * @return bool
   *
   * @section 10 Other settings
   */
  public function setConnectionTimeout($timeout)
  {
    if (preg_match("#[0-9]+#", $timeout))
    {
      $this->PageRequest->socketConnectTimeout = $timeout;
      return true;
    }
    else
    {
      return false;
    }
  }
  
  /**
   * Sets the timeout in seconds for waiting for data on an established server-connection.
   *
   * If the connection to a server was be etablished but the server doesnt't send data anymore without
   * closing the connection, the crawler will wait the time given in timeout and then close the connection.
   *
   * @param int $timeout The timeout in seconds, the default-value is 2 seconds.
   * @return bool
   *
   * @section 10 Other settings
   */
  public function setStreamTimeout($timeout)
  {
    if (preg_match("#[0-9]+#", $timeout))
    {
      $this->PageRequest->socketReadTimeout = $timeout;
      return true;
    }
    else
    {
      return false;
    }
  }
  
  /**
   * Adds a rule to the list of rules that decide in what kind of documents the crawler
   * should search for links in (regarding their content-type)
   *
   * By default the crawler ONLY searches for links in documents of type "text/html".
   * Use this method to add one or more other content-types the crawler should check for links.
   *
   * Example:
   * <code>
   * $crawler->addLinkSearchContentType("#text/css# i");
   * $crawler->addLinkSearchContentType("#text/xml# i");
   * </code>
   * These rules let the crawler search for links in HTML-, CSS- ans XML-documents.
   *
   * <b>Please note:</b> It is NOT recommended to let the crawler checkfor links in EVERY document-
   * type! This could slow down the crawling-process dramatically (e.g. if the crawler receives large
   * binary-files like images and tries to find links in them).
   *
   * @param string $regex Regular-expression defining the rule
   * @return bool         TRUE if the rule was successfully added
   *
   * @section 6 Linkfinding settings
   */
  public function addLinkSearchContentType($regex)
  {
    return $this->PageRequest->addLinkSearchContentType($regex);
  }
  
  /**
   * Defines what type of cache will be internally used for caching URLs.
   *
   * Currently phpcrawl is able to use a in-memory-cache or a SQlite-database-cache for
   * caching/storing found URLs internally.
   *
   * The memory-cache ({@link PHPCrawlerUrlCacheTypes}::URLCACHE_MEMORY) is recommended for spidering small to medium websites.
   * It provides better performance, but the php-memory-limit may be hit when too many URLs get added to the cache.
   * This is the default-setting.
   *
   * The SQlite-cache ({@link PHPCrawlerUrlCacheTypes}::URLCACHE_SQLite) is recommended for spidering huge websites.
   * URLs get cached in a SQLite-database-file, so the cache only is limited by available harddisk-space.
   * To increase performance of the SQLite-cache you may set it's location to a shared-memory device like "/dev/shm/"
   * by using the {@link setWorkingDirectory()}-method.
   *
   * Example:
   * <code>
   * $crawler->setUrlCacheType(PHPCrawlerUrlCacheTypes::URLCACHE_SQLITE);
   * $crawler->setWorkingDirectory("/dev/shm/");
   * </code>
   *
   * <b>NOTE:</b> When using phpcrawl in multi-process-mode ({@link goMultiProcessed()}), the cache-type is automatically set
   * to PHPCrawlerUrlCacheTypes::URLCACHE_SQLITE.
   *
   * @param int $url_cache_type 1 -> in-memory-cache (default setting)
   *                            2 -> SQlite-database-cache
   *
   *                            Or one of the {@link PHPCrawlerUrlCacheTypes}::URLCACHE..-constants.
   * @return bool
   * @section 1 Basic settings
   */
  public function setUrlCacheType($url_cache_type)
  {
    if (preg_match("#[1-2]#", $url_cache_type))
    {
      $this->url_cache_type = $url_cache_type;
      return true;
    }
    else return false;
  }
  
  /**
   * Decides whether the crawler should obey "nofollow"-tags
   *
   * If set to TRUE, the crawler will not follow links that a marked with rel="nofollow"
   * (like &lt;a href="page.html" rel="nofollow"&gt;) nor links from pages containing the meta-tag
   * <meta name="robots" content="nofollow">.
   *
   * By default, the crawler will NOT obey nofollow-tags.
   * 
   * @param bool $mode If set to TRUE, the crawler will obey "nofollow"-tags
   * @section 2 Filter-settings
   */
  public function obeyNoFollowTags($mode)
  {
    $this->UrlFilter->obey_nofollow_tags = $mode;
  }
  
  /**
   * Adds post-data together with an URL-rule to the list of post-data to send with requests.
   *
   * Example
   * <code>
   * $post_data = array("username" => "me", "password" => "my_password", "action" => "do_login");
   * $crawler->addPostData("#http://www\.foo\.com/login.php#", $post_data);
   * </code>
   * This example sends the post-values "username=me", "password=my_password" and "action=do_login" to the URL
   * http://www.foo.com/login.php
   * 
   * @param string $url_regex       Regular expression defining the URL(s) the post-data should be send to.
   * @param array  $post_data_array Post-data-array, the array-keys are the post-data-keys, the array-values the post-values.
   *                                (like array("post_key1" => "post_value1", "post_key2" => "post_value2")
   *
   * @return bool
   * @section 10 Other settings
   */
  public function addPostData($url_regex, $post_data_array)
  {
    return $this->UserSendDataCache->addPostData($url_regex, $post_data_array);
  }
  
  /**
   * Returns the unique ID of the instance of the crawler
   *
   * @return int
   * @section 9 Process resumption
   */
  public function getCrawlerId()
  {
    return $this->crawler_uniqid;
  }
  
  /**
   * Resumes the crawling-process with the given crawler-ID
   *
   * If a crawling-process was aborted (for whatever reasons), it is possible
   * to resume it by calling the resume()-method before calling the go() or goMultiProcessed() method
   * and passing the crawler-ID of the aborted process to it (as returned by {@link getCrawlerId()}).
   * 
   * In order to be able to resume a process, it is necessary that it was initially
   * started with resumption enabled (by calling the {@link enableResumption()} method).
   *
   * This method throws an exception if resuming of a crawling-process failed.
   *
   *
   * Example of a resumeable crawler-script:
   * <code>
   * // ...
   * $crawler = new MyCrawler();
   * $crawler->enableResumption();
   * $crawler->setURL("www.url123.com");
   *
   * // If process was started the first time:
   * // Get the crawler-ID and store it somewhere in order to be able to resume the process later on
   * if (!file_exists("/tmp/crawlerid_for_url123.tmp"))
   * {
   *   $crawler_id = $crawler->getCrawlerId();
   *   file_put_contents("/tmp/crawlerid_for_url123.tmp", $crawler_id);
   * }
   *
   * // If process was restarted again (after a termination):
   * // Read the crawler-id and resume the process
   * else
   * {
   *   $crawler_id = file_get_contents("/tmp/crawlerid_for_url123.tmp");
   *   $crawler->resume($crawler_id);
   * }
   *
   * // ...
   * 
   * // Start your crawling process
   * $crawler->goMultiProcessed(5);
   *
   * // After the process is finished completely: Delete the crawler-ID
   * unlink("/tmp/crawlerid_for_url123.tmp");
   * </code>
   *
   * @param int $crawler_id The crawler-ID of the crawling-process that should be resumed.
   *                        (see {@link getCrawlerId()})
   * @section 9 Process resumption
   */
  public function resume($crawler_id)
  {
    if ($this->resumtion_enabled == false)
      throw new Exception("Resumption was not enalbled, call enableResumption() before calling the resume()-method!");
    
    // Adobt crawler-id
    $this->crawler_uniqid = $crawler_id;
    
    if (!file_exists($this->working_base_directory."phpcrawl_tmp_".$this->crawler_uniqid.DIRECTORY_SEPARATOR))
    {
      throw new Exception("Couldn't find any previous aborted crawling-process with crawler-id '".$this->crawler_uniqid."'");
    }
    
    $this->createWorkingDirectory();
    
    // Unlinks pids file in working-dir (because all PIDs will change in new process)
    if (file_exists($this->working_directory."pids")) unlink($this->working_directory."pids");
  }
  
  /**
   * Prepares the crawler for process-resumption.
   *
   * In order to be able to resume an aborted/terminated crawling-process, it is necessary to
   * initially call the enableResumption() method in your script/project.
   *
   * For further details on how to resume aborted processes please see the documentation of the
   * {@link resume()} method.
   * @section 9 Process resumption
   */
  public function enableResumption()
  {
    $this->resumtion_enabled = true;
    $this->setUrlCacheType(PHPCrawlerUrlCacheTypes::URLCACHE_SQLITE);
  }
}
?>