<?php
/**
 * The following code is a complete example of using phpcrawl with multi processes.
 *
 * The listed script "spiders" the documentation of the php-mysql-extension on php.net (http://php.net/manual/en/book.mysql.php)
 * including all it's subsections and links. By defining some rules is it assured that all other links leading to other sites 
 * and sections on php.net get ignored.
 *
 * This script has to be run from the commandline (php CLI, run "php multiprocessing_example.php"). 
 */

// Inculde the phpcrawl-mainclass
include("libs/PHPCrawler.class.php");

// Extend the class and override the handleDocumentInfo()-method
class MyCrawler extends PHPCrawler 
{
  function handleDocumentInfo($DocInfo) 
  {
    // Just detect linebreak for output ("\n" in CLI-mode, otherwise "<br>").
    if (PHP_SAPI == "cli") $lb = "\n";
    else $lb = "<br />";

    // Print the URL and the HTTP-status-Code
    echo "Page requested: ".$DocInfo->url." (".$DocInfo->http_status_code.")".$lb;
    
    // Print the refering URL
    echo "Referer-page: ".$DocInfo->referer_url.$lb;
    
    // Print if the content of the document was be recieved or not
    if ($DocInfo->received == true)
      echo "Content received: ".$DocInfo->bytes_received." bytes".$lb;
    else
      echo "Content not received".$lb; 
    
    // Now you should do something with the content of the actual
    // received page or file ($DocInfo->source), we skip it in this example 
    
    echo $lb;
    
    flush();
  }
}

// Now, create a instance of your class, define the behaviour
// of the crawler (see class-reference for more options and details)
// and start the crawling-process.

$crawler = new MyCrawler();

// URL to crawl (the entry-page of the mysql-documentation on php.net)
$crawler->setURL("http://www.php.net/manual/en/book.mysql.php");

// Only receive content of documents with content-type "text/html"
$crawler->addReceiveContentType("#text/html#");

// Ignore links to pictures, css-documents etc (prefilter)
$crawler->addURLFilterRule("#\.(jpg|gif|png|pdf|jpeg|css|js)$# i");

// Every URL within the mysql-documentation looks like 
// "http://www.php.net/manual/en/function.mysql-affected-rows.php"
// or "http://www.php.net/manual/en/mysql.setup.php", they all contain
// "http://www.php.net/manual/en/" followed by  "mysql" somewhere.
// So we add a corresponding follow-rule to the crawler.
$crawler->addURLFollowRule("#^http://www.php.net/manual/en/.*mysql[^a-z]# i");

// That's it, start crawling using 5 processes
$crawler->goMultiProcessed(5);

// At the end, after the process is finished, we print a short
// report (see method getReport() for more information)
$report = $crawler->getProcessReport();

if (PHP_SAPI == "cli") $lb = "\n";
else $lb = "<br />";
    
echo "Summary:".$lb;
echo "Links followed: ".$report->links_followed.$lb;
echo "Documents received: ".$report->files_received.$lb;
echo "Bytes received: ".$report->bytes_received." bytes".$lb;
echo "Process runtime: ".$report->process_runtime." sec".$lb;
?>