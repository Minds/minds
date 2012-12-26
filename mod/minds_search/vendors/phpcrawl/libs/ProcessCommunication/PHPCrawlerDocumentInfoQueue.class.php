<?php
/**
 * Queue for PHPCrawlerDocumentInfo-objects
 *
 * @package phpcrawl
 * @internal
 */
class PHPCrawlerDocumentInfoQueue
{
  protected $PDO;
  
  protected $sqlite_db_file;
  
  protected $prepared_statements_created = false;
  
  protected $working_directory = null;
  
  protected $queue_max_size = 20;
  
  /**
   * Initiates a PHPCrawlerDocumentInfoQueue
   *
   * @param string $file            The SQLite-fiel to use.
   * @param  bool  $create_tables   Defines whether all necessary tables should be created
   */
  public function __construct($file, $create_tables = false)
  {
    $this->sqlite_db_file = $file;
    $this->working_directory = dirname($file)."/";
    $this->openConnection($create_tables);
  }
  
  /**
   * Returns the current number of PHPCrawlerDocumentInfo-objects in the queue
   */
  public function getDocumentInfoCount()
  {
    $Result = $this->PDO->query("SELECT count(id) as sum FROM document_infos;");
    $row = $Result->fetch(PDO::FETCH_ASSOC);
    $Result->closeCursor();
    
    return $row["sum"];
  }
  
  /**
   * Adds a PHPCrawlerDocumentInfo-object to the queue
   */
  public function addDocumentInfo(PHPCrawlerDocumentInfo $DocInfo)
  {
    // If queue is full -> wait a little
    while ($this->getDocumentInfoCount() >= $this->queue_max_size)
    {
      sleep(0.5);
    }
    
    $this->createPreparedStatements();
    
    $ser = serialize($DocInfo);
    
    $this->PDO->exec("BEGIN EXCLUSIVE TRANSACTION");
    $this->prepared_insert_statement->bindParam(1, $ser, PDO::PARAM_LOB);
    $this->prepared_insert_statement->execute();
    $this->prepared_select_statement->closeCursor();
    $this->PDO->exec("COMMIT");
  }
  
   /**
   * Returns a PHPCrawlerDocumentInfo-object from the queue
   */
  public function getNextDocumentInfo()
  { 
    $this->createPreparedStatements();
    
    $this->prepared_select_statement->execute();
    $this->prepared_select_statement->bindColumn("document_info", $doc_info, PDO::PARAM_LOB);
    $this->prepared_select_statement->bindColumn("id", $id);
    $row = $this->prepared_select_statement->fetch(PDO::FETCH_BOUND);
    $this->prepared_select_statement->closeCursor();
    
    if ($id == null) 
    {
      return null; 
    }
    
    $this->PDO->exec("DELETE FROM document_infos WHERE id = ".$id.";");
    
    $DocInfo = unserialize($doc_info);

    return $DocInfo;
  }
  
  protected function createPreparedStatements()
  {
    if ($this->prepared_statements_created == false)
    {
      $this->prepared_insert_statement = $this->PDO->prepare("INSERT INTO document_infos (document_info) VALUES (?);");
      $this->prepared_select_statement = $this->PDO->prepare("SELECT * FROM document_infos limit 1;");
      
      $this->prepared_statements_created = true;
    }
  }
  
  /**
   * Creates the sqlite-db-file and opens connection to it.
   *
   * @param bool $create_tables Defines whether all necessary tables should be created
   */
  protected function openConnection($create_tables = false)
  {
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
      $this->PDO->exec("CREATE TABLE IF NOT EXISTS document_infos (id integer PRIMARY KEY AUTOINCREMENT,
                                                                   document_info blob);");
      $this->PDO->exec("ANALYZE;");
    }
  }
}
?>