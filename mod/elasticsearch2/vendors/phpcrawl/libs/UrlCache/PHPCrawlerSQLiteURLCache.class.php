<?php
/**
 * Class for caching/storing URLs/links in a SQLite-database-file.
 *
 * @package phpcrawl
 * @internal
 */
class PHPCrawlerSQLiteURLCache extends PHPCrawlerURLCacheBase
{
  /**
   * PDO-object for querying SQLite-file.
   *
   * @var PDO
   */
  protected $PDO;
  
  /**
   * Prepared statement for inserting URLS into the db-file as PDOStatement-object.
   *
   * @var PDOStatement
   */
  protected $PreparedInsertStatement;
  
  protected $sqlite_db_file;
  
  protected $db_analyzed = false;
  
  /**
   * Initiates an SQLite-URL-cache.
   *
   * @param string $file            The SQLite-fiel to use.
   * @param bool   $create_tables   Defines whether all necessary tables should be created
   */
  public function __construct($file, $create_tables = false)
  {
    $this->sqlite_db_file = $file;
    $this->openConnection($create_tables);
  }
  
  public function getUrlCount()
  {
    $Result = $this->PDO->query("SELECT count(id) AS sum FROM urls WHERE processed = 0;");
    $row = $Result->fetch(PDO::FETCH_ASSOC);
    return $row["sum"];
  }
  
  /**
   * Returns the next URL from the cache that should be crawled.
   *
   * @return PhpCrawlerURLDescriptor An PhpCrawlerURLDescriptor or NULL if currently no
   *                                 URL to process.
   */
  public function getNextUrl()
  {
    PHPCrawlerBenchmark::start("fetching_next_url_from_sqlitecache"); 
    
    $ok = $this->PDO->exec("BEGIN EXCLUSIVE TRANSACTION");
    
    // Get row with max priority-level
    $Result = $this->PDO->query("SELECT max(priority_level) AS max_priority_level FROM urls WHERE in_process = 0 AND processed = 0;");
    $row = $Result->fetch(PDO::FETCH_ASSOC);
    
    if ($row["max_priority_level"] == null) 
    {
      $Result->closeCursor();
      $this->PDO->exec("COMMIT;");
      return null;
    }
    
    $Result = $this->PDO->query("SELECT * FROM urls WHERE priority_level = ".$row["max_priority_level"]." and in_process = 0 AND processed = 0;");
    $row = $Result->fetch(PDO::FETCH_ASSOC);
    $Result->closeCursor();
     
    // Update row (set in process-flag)
    $this->PDO->exec("UPDATE urls SET in_process = 1 WHERE id = ".$row["id"].";");
    
    $this->PDO->exec("COMMIT;");
    
    PHPCrawlerBenchmark::stop("fetching_next_url_from_sqlitecache");
     
    // Return URL
    return new PHPCrawlerURLDescriptor($row["url_rebuild"], $row["link_raw"], $row["linkcode"], $row["linktext"], $row["refering_url"]);
  }
  
  /**
   * Has no function in this class
   */
  public function getAllURLs()
  {
  }
  
  /**
   * Removes all URLs and all priority-rules from the URL-cache.
   */
  public function clear()
  {
    $this->PDO->exec("DELETE FROM urls;");
    $this->PDO->exec("VACUUM;");
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
    $map_key = md5($UrlDescriptor->url_rebuild);
    
    // Get priority of URL
    $priority_level = $this->getUrlPriority($UrlDescriptor->url_rebuild);
    
    $this->createPreparedInsertStatement();
                                                                    
    // Insert URL via prepared statement
    $this->PreparedInsertStatement->execute(array(":priority_level" => $priority_level,
                                                  ":distinct_hash" => $map_key,
                                                  ":link_raw" => $UrlDescriptor->link_raw,
                                                  ":linkcode" => $UrlDescriptor->linkcode,
                                                  ":linktext" => $UrlDescriptor->linktext,
                                                  ":refering_url" => $UrlDescriptor->refering_url,
                                                  ":url_rebuild" => $UrlDescriptor->url_rebuild,
                                                  ":is_redirect_url" => $UrlDescriptor->is_redirect_url));
  }
  
  /**
   * Adds an bunch of URLs to the url-cache
   *
   * @param array $urls  A numeric array containing the URLs as PHPCrawlerURLDescriptor-objects
   */
  public function addURLs($urls)
  {
    PHPCrawlerBenchmark::start("adding_urls_to_sqlitecache"); 
    
    $this->PDO->exec("BEGIN EXCLUSIVE TRANSACTION;");
    
    $cnt = count($urls);
    for ($x=0; $x<$cnt; $x++)
    {
      if ($urls[$x] != null)
      {
        $this->addURL($urls[$x]);
      }
    }
    
    $this->PDO->exec("COMMIT;");
    $this->PreparedInsertStatement->closeCursor();
        
    if ($this->db_analyzed == false)
    {
      $this->PDO->exec("ANALYZE;");
      $this->db_analyzed = true;
    }
    
    PHPCrawlerBenchmark::stop("adding_urls_to_sqlitecache"); 
  }
  
  /**
   * Marks the given URL in the cache as "followed"
   *
   * @param PHPCrawlerURLDescriptor $UrlDescriptor
   */
  public function markUrlAsFollowed(PHPCrawlerURLDescriptor $UrlDescriptor)
  {
    PHPCrawlerBenchmark::start("marking_url_as_followes");
    $hash = md5($UrlDescriptor->url_rebuild);
    $this->PDO->exec("UPDATE urls SET processed = 1, in_process = 0 WHERE distinct_hash = '".$hash."';");
    PHPCrawlerBenchmark::stop("marking_url_as_followes"); 
  }
  
  /**
   * Checks whether there are URLs left in the cache that should be processed or not.
   *
   * @return bool
   */
  public function containsURLs()
  {
    PHPCrawlerBenchmark::start("checking_for_urls_in_cache");
    
    $Result = $this->PDO->query("SELECT id FROM urls WHERE processed = 0 OR in_process = 1 LIMIT 1;");
    
    $has_columns = $Result->fetchColumn();
    
    $Result->closeCursor();
    
    PHPCrawlerBenchmark::stop("checking_for_urls_in_cache");
    
    if ($has_columns != false)
    {
      return true;
    }
    else return false;
  }
  
  /**
   * Cleans/purges the URL-cache from inconsistent entries.
   */
  public function purgeCache()
  {
    // Set "in_process" to 0 for all URLs
    $this->PDO->exec("UPDATE urls SET in_process = 0;");
  }
  
  /**
   * Creates the sqlite-db-file and opens connection to it.
   *
   * @param bool $create_tables Defines whether all necessary tables should be created
   */
  protected function openConnection($create_tables = false)
  {
    PHPCrawlerBenchmark::start("connecting_to_sqlite_db");
    
    // Open sqlite-file
    try
    {
      $this->PDO = new PDO("sqlite:".$this->sqlite_db_file);
    }
    catch (Exception $e)
    {
      throw new Exception("Error creating SQLite-cache-file, ".$e->getMessage().", try installing sqlite3-extension for PHP.");
    }
    
    $this->PDO->exec("PRAGMA journal_mode = OFF");
    
    $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    $this->PDO->setAttribute(PDO::ATTR_TIMEOUT, 100);
    
    if ($create_tables == true)
    {
      // Create url-table (if not exists)
      $this->PDO->exec("CREATE TABLE IF NOT EXISTS urls (id integer PRIMARY KEY AUTOINCREMENT,
                                                         in_process bool DEFAULT 0,
                                                         processed bool DEFAULT 0,
                                                         priority_level integer,
                                                         distinct_hash TEXT UNIQUE,
                                                         link_raw TEXT,
                                                         linkcode TEXT,
                                                         linktext TEXT,
                                                         refering_url TEXT,
                                                         url_rebuild TEXT,
                                                         is_redirect_url bool);");
      
      // Create indexes (seems that indexes make the whole thingy slower)
      $this->PDO->exec("CREATE INDEX IF NOT EXISTS priority_level ON urls (priority_level);");
      $this->PDO->exec("CREATE INDEX IF NOT EXISTS distinct_hash ON urls (distinct_hash);");
      $this->PDO->exec("CREATE INDEX IF NOT EXISTS in_process ON urls (in_process);");
      $this->PDO->exec("CREATE INDEX IF NOT EXISTS processed ON urls (processed);");
      
      $this->PDO->exec("ANALYZE;");
    }
    
    PHPCrawlerBenchmark::stop("connecting_to_sqlite_db");
  }
  
  /**
   * Creates the prepared statement for insterting URLs into database (if not done yet)
   */
  protected function createPreparedInsertStatement()
  {
    if ($this->PreparedInsertStatement == null)
    {
      // Prepared statement for URL-inserts                                      
      $this->PreparedInsertStatement = $this->PDO->prepare("INSERT OR IGNORE INTO urls (priority_level, distinct_hash, link_raw, linkcode, linktext, refering_url, url_rebuild, is_redirect_url)
                                                            VALUES(:priority_level,
                                                                   :distinct_hash,
                                                                   :link_raw,
                                                                   :linkcode,
                                                                   :linktext,
                                                                   :refering_url,
                                                                   :url_rebuild,
                                                                   :is_redirect_url);");
    }
  }
  
  /**
   * Cleans up the cache after is it not needed anymore.
   */
  public function cleanup()
  {
    unlink($this->sqlite_db_file);
  }
}
?>