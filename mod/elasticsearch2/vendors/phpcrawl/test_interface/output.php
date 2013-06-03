<?php
// This file shouldn't be opened/run directly 
if (!preg_match("#index\.php$#i", $_SERVER["SCRIPT_FILENAME"]))
{
  header("Location: index.php");
  exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
 <title>PHPCrawl Testinterface Output</title>
 <link rel="stylesheet" type="text/css" href="style.css">
  
 <style type="text/css">
 #cont {
   height:100%;
 }
 
 #output {
    width:691px; 
    height:500px; 
    background-color:#e1e1e1; 
    border-width:2px; 
    border-style:solid; 
    overflow:auto;
    font-family: verdana;
    font-size: 10px;
    padding-left: 5px;
  }
 
 #head {
    padding-left: 5px;
    padding-top: 3px;
    width:691px; 
    height:30px; 
    background-color:#ffffff; 
    border-top: 2px black solid;
    border-left: 2px black solid; 
    border-right: 2px black solid; 
    font-family: verdana;
    font-size: 14px;
    font-weight: bold;
  }
  
  #foot {
    width:691px; 
    height:40px; 
    background-color:#ffffff; 
    border-bottom: 2px black solid;
    border-left: 2px black solid; 
    border-right: 2px black solid; 
    font-family: verdana;
    font-size: 12px;
    padding-left: 5px;
    padding-top: 2px;
    vertical-align: bottom;
  }
  
  table.intbl
  {
    border-color:#000000;
    border-collapse: collapse;
    border-width:1px;
    border-style:solid;
  }
 
  table.intbl td 
  {
    background-color: #efefef;
    padding-left:3px;
    border: 1px black solid;
  }
  
  table.summary
  {
    background-color: #ffffff;
    font-family: verdana;
    font-size: 12px;
  }
  
  table.summary td
  {
    padding: 2px;
  }
  
  table.summary td#summary_head
  {
    font-weight: bold;
  }
 
 </style>
 
 <script language="javascript">
 self.focus();
 </script>
</head>

<body>

<?php
// Setup the crawler
$crawler = new phpcrawlSetup ($val, $output); // $val is the posted setup-array,
                                               // $output the posted output-array
                                                 
if (isset($_POST["misc"]["force_flush"]) && $_POST["misc"]["force_flush"] == "1")
{
  $crawler->force_output_flushing = true;
}

$setup_error = $crawler->setupCrawler();
?>

<div id="container">
  
  <div id="head">
    PHPCrawl Testinterface Output
    (using version: <?php echo $crawler->class_version; ?>)
  </div>
    
  <div id="output">
  
  <?php  
  flush();
                                                
  // Start crawling if no error occured during setup
  if ($setup_error==false)
  {
    echo "<br>";
    
    $crawler->go();
    
    // Get the report after its finished.
    $summary = $crawler->getReport();
  }
  else
  {
    echo "<br><font class=warning>Setup error: ".$setup_error."</font>";
  }
  ?>
    
  </div>
    
  <div id="foot">
    
    <?php
    // Print the summary if no error occured
    if ($setup_error == false)
    {
      echo '<table class="summary">
              <tr>
                <td id="summary_head">Process finished!&nbsp;</td>
                <td>Links followed:</td>
                <td>'.$summary["links_followed"].'&nbsp;&nbsp;</td>
                <td>Kb received:</td>
                <td>'.round($summary["bytes_received"] / 1000).'&nbsp;&nbsp;</td>
                <td>Data throughput kb/s:</td>
                <td>'.round($summary["data_throughput"] / 1000).'&nbsp;&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>Files received:</td>
                <td>'.$summary["files_received"].'&nbsp;&nbsp;</td>
                <td>Time in sec:</td>
                <td>'.number_format($summary["process_runtime"], 2, ".", "").'&nbsp;&nbsp;</td>
                <td>Max memory-usage in KB:</td>
                <td>'.number_format($summary["memory_peak_usage"] / 1024, 2, ".", "").'</td>
              </tr>
             </table>';
    }
    ?>
       
  </div>
    
</div>

</body>
</html>
