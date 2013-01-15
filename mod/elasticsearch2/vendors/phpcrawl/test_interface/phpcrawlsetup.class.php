<?php
########################################################################
#
# class phpcrawlSetup extends phpcrawler (at least v 0.7)
#
# Part of the phpcrawl testinterface / package phpcrawl
#
# Copyright (C) 2004 Uwe Hunfeld (phpcrawl@cuab.de)
#
# This class extends class phpcrawler and adds some functions just to 
# setup/configure the crawler (class phpcrawler) through setup-arrays
# instead of calling each single method.
#
# GNU General Public License
########################################################################

class PhpcrawlSetup extends PHPCrawler {

  var $setup_array_raw = array(); // Form: $array[method]=argument -> $this->method(argument)
                                  // or    $array[method][0]=argument -> $this->method(argument)
                                  // or    $array[method][1][0]=arg1
                                  //       $array[method][1][1]=arg2  -> $this->method(arg1, arg2)
  
  var $setup_array = array(); // Converted setup-array, adds some information and
                              // converts arguments if nessesary (see convertSetupArray())
                              
  var $output_array = array(); // Array specifying the output that should be done
    
  var $force_output_flushing = false; // Just a flag for the ugly "flush()"-workaround
                                      // on servers with output_buffering set to ON.
                                      // Has absolutely nothing to do with the crawling-stuff.
  
  function __construct (&$setup_array, &$output_array)
  {
    parent::__construct();
    
    $this->setup_array_raw = &$setup_array;
    $this->output_array = &$output_array;
    $this->convertSetupArray();
  }
  
  // Go through setup-array and convert arguments if nessesary, also
  // add the "argument_type" (just for the error-output)
  function convertSetupArray () {
  
    while (list($key)=@each($this->setup_array_raw)) {
        
      if ($key=="setURL") {
        $this->setup_array[$key]["arg_type"] = "string";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="setPort") {
        $this->setup_array[$key]["arg_type"] = "port-number";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="setFollowMode") {
        $this->setup_array[$key]["arg_type"] = "integer";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
    
      if ($key=="setFollowRedirects") {
        if ($this->setup_array_raw[$key]=="0") (bool)$argument=false;
        if ($this->setup_array_raw[$key]=="1") (bool)$argument=true;
        $this->setup_array[$key]["arg_type"] = "bool";
        $this->setup_array[$key]["arg_value"] = $argument;
      }
      
      if ($key=="setFollowRedirectsTillContent") {
        if ($this->setup_array_raw[$key]=="0") (bool)$argument=false;
        if ($this->setup_array_raw[$key]=="1") (bool)$argument=true;
        $this->setup_array[$key]["arg_type"] = "bool";
        $this->setup_array[$key]["arg_value"] = $argument;
      }
      
      if ($key=="setCookieHandling") {
        if ($this->setup_array_raw[$key]=="0") (bool)$argument=false;
        if ($this->setup_array_raw[$key]=="1") (bool)$argument=true;
        $this->setup_array[$key]["arg_type"] = "bool";
        $this->setup_array[$key]["arg_value"] = $argument;
      }
      
      if ($key=="setAggressiveLinkExtraction") {
        if ($this->setup_array_raw[$key]=="0") (bool)$argument=false;
        if ($this->setup_array_raw[$key]=="1") (bool)$argument=true;
        $this->setup_array[$key]["arg_type"] = "bool";
        $this->setup_array[$key]["arg_value"] = $argument;
      }
      
      if ($key=="setPageLimit") {
        $this->setup_array[$key]["arg_type"] = "integer";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="setTrafficLimit") {
        $value = $this->setup_array_raw[$key] * 1024;
        $this->setup_array[$key]["arg_type"] = "integer";
        $this->setup_array[$key]["arg_value"] = $value;
      }
      
      if ($key=="setContentSizeLimit") {
        $value = $this->setup_array_raw[$key] * 1024;
        $this->setup_array[$key]["arg_type"] = "integer";
        $this->setup_array[$key]["arg_value"] = $value;
      }
      
      if ($key=="addReceiveContentType") {
        $this->setup_array[$key]["arg_type"] = "preg-pattern";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="addReceiveContentType") {
        $this->setup_array[$key]["arg_type"] = "preg-pattern";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="addFollowMatch") {
        $this->setup_array[$key]["arg_type"] = "preg-pattern";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="addNonFollowMatch") {
        $this->setup_array[$key]["arg_type"] = "preg-pattern";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="addLinkPriority") {
        $this->setup_array[$key]["arg_type"] = "preg-pattern";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="addReceiveToTmpFileMatch") {
        $this->setup_array[$key]["arg_type"] = "preg-pattern";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="addReceiveToMemoryMatch") {
        $this->setup_array[$key]["arg_type"] = "preg-pattern";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="setWorkingDirectory") {
        $this->setup_array[$key]["arg_type"] = "writeable file";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="addLinkExtractionTags") {
        $this->setup_array[$key]["arg_type"] = "string";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="setConnectionTimeout") {
        $this->setup_array[$key]["arg_type"] = "double-value";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="setStreamTimeout") {
        $this->setup_array[$key]["arg_type"] = "double-value";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="setUserAgentString") {
        $this->setup_array[$key]["arg_type"] = "string";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="addBasicAuthentication") {
        $this->setup_array[$key]["arg_type"] = "preg-pattern";
        $this->setup_array[$key]["arg_value"] = &$this->setup_array_raw[$key];
      }
      
      if ($key=="obeyRobotsTxt") {
        if ($this->setup_array_raw[$key]=="0") (bool)$argument=false;
        if ($this->setup_array_raw[$key]=="1") (bool)$argument=true;
        $this->setup_array[$key]["arg_type"] = "bool";
        $this->setup_array[$key]["arg_value"] = $argument;
      }
    }
  
  }
  

  // Go through the setup-array and call the corresponding method of the crawler,
  // via setObjectMethod() and setObjectMethods()
  function setupCrawler () {
    
    // Check if any output was defined
    if (count($this->output_array)==0) {
      $setup_error = "No output specified, please choose some output.";
    }
    else {
      
      while(list($key)=@each($this->setup_array)) {
        
		      if (is_array($this->setup_array[$key]["arg_value"])) {
		        $setup_error = $this->setObjectMethods($key, $this->setup_array[$key]["arg_value"], $this->setup_array[$key]["arg_type"]);
		      }
		      else {
		        $setup_error = $this->setObjectMethod($key, $this->setup_array[$key]["arg_value"], $this->setup_array[$key]["arg_type"]);
		      }
		      
		      if ($setup_error) {
		        break;
		      }
        
		    }
    }
    
    return $setup_error;
  }
  
  // Function calls a $method of a $class (phpcrawl) with the arguments
  // given in $arguments.
  // $arguments has to be a numeric array, each element can be an 
  // 2-dimensional array again (method requires 2 arguments or 3).

  function setObjectMethods ($method, $arguments, $str_argument_type) {
    
    for ($x=0; $x<count($arguments); $x++) {
      
      if (is_array($arguments[$x])) {
        
        $check = false; // init
        $args = 0;
        
        // Count arguments given
        while (list($key)=@each($arguments[$x])) {
          if (isset($arguments[$x][$key]) && $arguments[$x][$key]!="") $args++;
        }
        
        // 3 arguments
        if ($args == 3) {
          $check = $this->$method($arguments[$x][0], $arguments[$x][1], $arguments[$x][2]);
          if ($check==false) $error = "Invalid arguments given for method ".$method."().";
        }
        // 2 arguments
        elseif ($args == 2) {
          $check = $this->$method($arguments[$x][0], $arguments[$x][1]);
          if ($check==false) $error = "Invalid arguments given for method ".$method."().";
        }

      }
      
      elseif ($arguments[$x]!="") {
        $check = $this->$method($arguments[$x]);
        if ($check==false) $error = "'".$arguments[$x]."' is not a valid $str_argument_type given in ".$method."().";
      }
      
    }
    
    if (isset($error)) {
      return $error;
    }
  }
  
  // Same as setObjectMethods, just $arguments is a single mixed value.
  
  function setObjectMethod ($method, $argument, $str_argument_type) {

    if ($argument!="" || is_bool($argument)) {
      $check = $this->$method($argument);
      if ($check==false) $error = "'".$argument."' is not a valid $str_argument_type given in ".$method."().";
    }
  
    if (isset($error)) {
      return $error;
    }
  }

  function handlePageData(&$page_data) {
    
    echo "<table class=intbl>";
    
    // Loop oveer the output-array and print info if wanted
    @reset($this->output_array);
    while (list($key)=@each($this->output_array)) {
    
      if ($key=="requested_url")
      {
        $str = '<a href="'.$page_data["url"].'" target=blank>'.$page_data["url"].'</a>';
        echo "<tr><td width=130><nobr>Page requested:</nobr></td><td width=470>".$str."</td></tr>";
      }
      
      if ($key=="http_status_code") {
        if ($page_data["http_status_code"]) $str = $page_data["http_status_code"];
        else $str = "-";
        echo "<tr><td>HTTP-Status:</td><td>".$str."</td></tr>";
      }
      
      if ($key=="content_type") {
        if ($page_data["content_type"]) $str = $page_data["content_type"];
        else $str = "-";
        echo "<tr><td>Content-Type:</td><td>".$str."</td></tr>";
      }
      
      if ($key=="content_size") {
        $str = PHPCrawlerUtils::getHeaderTag ("Content-Length", $page_data["header"]);
        if (trim($str)=="") $str = "??";
        echo "<tr><td>Content-Size:</td><td >".$str." bytes</td></tr>";
      }
      
      if ($key=="content_received") {
        if ($page_data["received"]==true) $str = "Yes";
        else $str = "No";
        echo "<tr><td>Content received:</td><td >".$str."</td></tr>";
      }
      
      if ($key=="content_received_completely") {
        if ($page_data["received_completely"]==true) $str = "Yes";
        else $str = "No";
        echo "<tr><td><nobr>Received completely:</nobr></td><td >".$str."</td></tr>";
      }
      
      if ($key=="bytes_received")
        echo "<tr><td>Bytes received:</td><td>".$page_data["bytes_received"]." bytes</td></tr>";
      
      if ($key=="referer_url") {
        if ($page_data["referer_url"]=="") $str = "-";
        else $str = &$page_data["referer_url"];
        echo "<tr><td><nobr>Refering URL</nobr>:</td><td >".$str."</td></tr>";
      }
      
      if ($key=="refering_linkcode") {
        if ($page_data["refering_linkcode"]=="") $str = "-";
        else {
          $str = htmlentities($page_data["refering_linkcode"]);
          $str = str_replace("\n", "<br>", $str);
        }
        echo "<tr><td valign=top><nobr>Refering linkcode:</nobr></td><td >".$str."</td></tr>";
      }
      
      if ($key=="refering_link_raw") {
        if ($page_data["refering_link_raw"]=="") $str = "-";
        else $str = &$page_data["refering_link_raw"];
        echo "<tr><td><nobr>Refering Link RAW:&nbsp;</nobr></td><td >".$str."</td></tr>";
      }
      
      if ($key=="refering_linktext") {
        if ($page_data["refering_linktext"]=="") $str = "-";
        else {
          $str = &$page_data["refering_linktext"];
          $str = htmlentities($str);
          $str = str_replace("\n", "<br>", $str);
        }
        echo "<tr><td valign=top><nobr>Refering linktext</nobr>:</td><td >".$str."</td></tr>";
      }
      
      if ($key=="header_send") {
        if ($page_data["header_send"])  $str = str_replace("\n", "<br>", trim($page_data["header_send"]));
        else $str = "-";
        echo "<tr><td valign=top>Send header:</td><td >".$str."</td></tr>";
      }
      
      if ($key=="header") {
        if ($page_data["header"])  $str = str_replace("\n", "<br>", trim($page_data["header"]));
        else $str = "-";
        echo "<tr><td valign=top>Received header:</td><td >".$str."</td></tr>";
      }
      
      if ($key=="nr_found_links") {
        $str = count($page_data["links_found"]);
        echo "<tr><td valign=top>Links found:</td><td >".$str."</td></tr>";
      }
      
      if ($key=="all_found_links") {
        echo "<tr><td valign=top>List of found links:</td>";
        echo "<td>";
        
        for ($x=0; $x<count($page_data["links_found"]); $x++) {
          echo $page_data["links_found"][$x]["url_rebuild"]."<br>";
        }
        
        if (count($page_data["links_found"])==0)
        {
          echo "-";
        }
        
        echo "</td>";
        echo "</tr>";
      }
      
      if ($key=="received_to_file") {
        if ($page_data["received_to_file"]) $str = "Yes";
        else $str = "No";
        echo "<tr><td valign=top>Received to TMP-file:</td><td >".$str."</td></tr>";
      }
      
      if ($key=="tmpfile_name_size") {
        if ($page_data["content_tmp_file"]) $str = $page_data["content_tmp_file"]." (".filesize($page_data["content_tmp_file"])." bytes)";
        else $str = "-";
        echo "<tr><td valign=top>Content TMP-file:</td><td >".$str."</td></tr>";
      }
    
      if ($key=="received_to_memory") {
        if ($page_data["received_to_memory"]) $str = "Yes";
        else $str = "No";
        echo "<tr><td valign=top>Received to memory:</td><td >".$str."</td></tr>";
      }
      
      if ($key=="memory_content_size") {
        echo "<tr><td valign=top>Memory-content-size:</td><td >".strlen($page_data["source"])." bytes</td></tr>";
      }
    }
    
    // Output error if theres one
    if ($page_data["error_string"])
      echo "<tr>
            <td class=red>Error:</td>
            <td class=red>".$page_data["error_string"]."</td>
            </tr>";
            
    echo "</table> <br>";
    
    $this->flushOutput();
    
  }
  
  function flushOutput()
  {
    if ($this->force_output_flushing == true)
    {
      echo str_pad(" ", 5000); // Ugly!
    }
    
    flush();
  }
}
?>