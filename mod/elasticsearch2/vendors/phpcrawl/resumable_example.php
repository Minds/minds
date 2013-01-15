<?php
/**
 * The following code is a complete example of a resumable crawling-process
 *
 * You may test it by starting it from the commandline (CLI, type "php resumable_example.php"),
 * abort it (Ctrl^C) and start it again). 
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
    flush();
  } 
}

$crawler = new MyCrawler();
$crawler->setURL("www.php.net");
$crawler->addContentTypeReceiveRule("#text/html#");
$crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i");
$crawler->setPageLimit(50); // Set the page-limit to 50 for testing

// Important for resumable scripts/processes!
$crawler->enableResumption();

// At the firts start of the script retreive the crawler-ID and store it
// (in a temporary file in this example)
if (!file_exists("/tmp/mycrawlerid_for_php.net.tmp"))
{
  $crawler_ID = $crawler->getCrawlerId();
  file_put_contents("/tmp/mycrawlerid_for_php.net.tmp", $crawler_ID);
}
// If the script was restarted again (after it was aborted), read the crawler-ID
// and pass it to the resume() method.
else
{
  $crawler_ID = file_get_contents("/tmp/mycrawlerid_for_php.net.tmp");
  $crawler->resume($crawler_ID);
}

// Start crawling
$crawler->goMultiProcessed(5);

// Delete the stored crawler-ID after the process is finished completely and successfully.
unlink("/tmp/mycrawlerid_for_php.net.tmp");

$report = $crawler->getProcessReport();

if (PHP_SAPI == "cli") $lb = "\n";
else $lb = "<br />";
    
echo "Summary:".$lb;
echo "Links followed: ".$report->links_followed.$lb;
echo "Documents received: ".$report->files_received.$lb;
echo "Bytes received: ".$report->bytes_received." bytes".$lb;
echo "Process runtime: ".$report->process_runtime." sec".$lb; 
?>