<?php

########################################################################
#
# phpcrawl testinterface
# requires class phpcrawler (at least version 0.7)
#
# Part of the package phpcrawl
#
# Copyright (C) 2004 Uwe Hunfeld (phpcrawl@cuab.de)
#
# A visual HTML-interface for setting up an testing the phpcrawler-class.
#
# GNU General Public License
########################################################################

include("phpcrawl_testinterface.func.php");
include("phpcrawl_testinterface.conf.php");

// Login (http-auth)
auth_login($cfg_authUsername, $cfg_authPassword);

set_time_limit(0);

// Workaround, convert array $_POST["val"] to "plain" array $val
// Same with output-array ans misc-array
$val = &$_POST["val"];
$output = &$_POST["output"];
$misc = &$_POST["misc"];

// Save current setup (array $val)
if (isset($_POST["action"]) && $_POST["action"] == "save_setup")
{
  // Convert the setup-comment
  //$misc["comment"] = htmlentities($misc["comment"], ENT_QUOTES);
  $misc["comment"] = str_replace('\"', '"', $misc["comment"]);
  $misc["comment"] = str_replace("\'", "'", $misc["comment"]);
  
  $setuparray_combined["setup"] = &$val;
  $setuparray_combined["output"] = &$output;
  $setuparray_combined["misc"] = &$misc;
  
  $rw_error_message = save_setup($cfg_setupSaveDir, $_POST["save_setup_filename"], $setuparray_combined);
  if (!$rw_error_message) $rw_message = "Setup was saved.";
}

// Load a setup
if (isset($_POST["action"]) && $_POST["action"]=="load_setup")
{
  $setuparray_combined = &load_setup($cfg_setupSaveDir, $_POST["selected_setup_filename"], $rw_error_message);
  if (!$rw_error_message)
  {
    $val = &$setuparray_combined["setup"];
    $output = &$setuparray_combined["output"];
    $misc = &$setuparray_combined["misc"];
    $rw_message = "Setup was loaded.";
    
    $_POST["save_setup_filename"] = $_POST["selected_setup_filename"];
  }
}

// Delete a setup
if (isset($_POST["action"]) && $_POST["action"]=="delete_setup")
{
  $rw_error_message = delete_setup($cfg_setupSaveDir, $_POST["selected_setup_filename"]);
  if (!$rw_error_message) $rw_message = "Setup was deleted.";
}

// Get all setup files into an array
$setup_files = get_setup_files($cfg_setupSaveDir);

// Go crawling with given setup
if (isset($_POST["action"]) && $_POST["action"]=="start_crawling")
{
  set_time_limit(0);
  
  include($cfg_phpcrawlClassDir."phpcrawler.class.php"); 
  include("phpcrawlsetup.class.php");
  
  include("output.php");
  exit;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
 <title>PHPCrawl Testinterface</title>
 <link rel=stylesheet type="text/css" href="style.css">

<script language="javascript">
var classref_page = "<?php echo $cfg_phpcrawlClassrefPage; ?>";
</script>
<script language="javascript" src="js.js">
</script>
</head>

<body>

<form name="options" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>" target="blank">
<table width="700" cellspacing="2" cellpadding="2" border="0" class="bordered">
<tr>
  <td colspan="3" class="head">Load / Save Setup</td>
</tr>
  
<?php
if (isset($rw_error_message)) {
  echo '<tr>
          <td colspan="3" class="red">ERROR: '.$rw_error_message.'</td>
        </tr>';
}
elseif (isset($rw_message)) {
  echo '<tr>
          <td colspan="3" class="red">'.$rw_message.'</td>
        </tr>';
}
?>

<tr>
  <td>Save this setup as</td>
  <td><input type="text" name="save_setup_filename" id="save_setup_filename" value="<?php if (isset($_POST["save_setup_filename"])) echo $_POST["save_setup_filename"]; ?>" style="width:200px"></td>
  <td><input type="button" name="save_setup" value="Save setup" onClick="save_setupfile()"></td>
</tr>
<tr>
  <td>Load setup</td>
  <td>
    <select name="selected_setup_filename" id="selected_setup_filename" style="width:200px">
    <?php
    for ($x=0; $x<count($setup_files); $x++) {
      echo '<option value="'.$setup_files[$x].'">'.$setup_files[$x].'</option>';
    }
    ?>
    </select>
  </td>
  <td>
    <input type="button" name="load_setup" value="Load selected setup" onClick="setAction('load_setup', '_self')">
    <input type="button" value="Delete selected setup" onClick="delete_selected_setup();">
    <input type="hidden" name="delete_setup" value="">
  </td>
</tr>
</table>

<br>

<table width="700" cellspacing="2" cellpadding="2" border="0" class="bordered">
<tr>
  <td colspan="3" class="head">
    Class Setup &nbsp;&nbsp;&nbsp;
    <a href="javascript: void(0)" onClick="showCommentDiv('comment_div', true)">View/Edit comment</a>
    &nbsp;&nbsp;
    
    <div id="comment_div">
      <p style="margin: 0px;">Comment for this setup</p>
      <textarea name="misc[comment]" style="width:370px; height:310px;"><?php if (isset($misc["comment"])) echo $misc["comment"]; ?></textarea>
      <p style="margin:2px 0px 2px 0px; text-align:center"><input type="button" value="Close comment" onClick="showCommentDiv('comment_div', false)"></p>
    </div>
      
  </td>
</tr>
<?php
if (isset($setup_error_message)) {
  echo '<tr>
          <td colspan="3" class="red">ERROR: '.$setup_error_message.'</td>
        </tr>';
}
?>
<tr>
  <td colspan="3">
  
    <table>
      <tr>
        <td width="500">
          URL to crawl <a href="javascript: show_documentation('setURL');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;<br>
          <input type="text" name="val[setURL]" value="<?php if (isset($val["setURL"])) echo $val["setURL"]; ?>" size="80" style="width:550px">
          <br><br>
        </td>
        <td>&nbsp;
          Port <a href="javascript: show_documentation('setPort');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;<br>&nbsp;
          <input type="text" size="5" name="val[setPort]" value="<?php if (isset($val["setPort"])) echo $val["setPort"]; ?>">
          <br><br>
        </td>
      </tr>
    </table>
    
  </td>
</tr>

<tr>
  <td width="233" valign="top">
    Follow mode
    <a href="javascript: show_documentation('setFollowMode');"><img src="info.gif" align="absbottom" border="0"></a>
    <br>
    <select name="val[setFollowMode]">
    <?php
    // default value
    if (!$val) $val["setFollowMode"]=2;
    ?>
    <option value="0" <?php if ($val["setFollowMode"]==0) echo "selected"; ?>>0 - Follow every link</option>
    <option value="1" <?php if ($val["setFollowMode"]==1) echo "selected"; ?>>1 - Stay in domain</option>
    <option value="2" <?php if ($val["setFollowMode"]==2) echo "selected"; ?>>2 - Stay in host</option>
    <option value="3" <?php if ($val["setFollowMode"]==3) echo "selected"; ?>>3 - Stay in path</option>
    </select>
    <br>
    
  </td>
  
  <td width="233" valign="top">
  
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          Follow redirects
          <a href="javascript: show_documentation('setFollowRedirects');"><img src="info.gif" align="absbottom" border="0"></a>
        </td>
        <td>
          <select name="val[setFollowRedirects]" id="setFollowRedirects">
          <?php
          // default value
          if (!isset($val["setFollowRedirects"])) $val["setFollowRedirects"]=1;
          ?>
          <option value="0" <?php if ($val["setFollowRedirects"]==0) echo "selected"; ?>>No</option>
          <option value="1" <?php if ($val["setFollowRedirects"]==1) echo "selected"; ?>>Yes</option>
          </select>
        </td>
      </tr>
      <tr>  
        <td>
          Enable cookie-handling
          <a href="javascript: show_documentation('enableCookieHandling');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;
        </td>
        <td>
          <select name="val[setCookieHandling]" id="setCookieHandling">
          <?php
          // default value
          if (!isset($val["setCookieHandling"])) $val["setCookieHandling"]=1;
          ?>
          <option value="0" <?php if ($val["setCookieHandling"]==0) echo "selected"; ?>>No</option>
          <option value="1" <?php if ($val["setCookieHandling"]==1) echo "selected"; ?>>Yes</option>
          </select>
        </td>
      </tr>
      <tr>  
        <td>
          Aggressive linkextraction
          <a href="javascript: show_documentation('enableAggressiveLinkSearch');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;
        </td>
        <td>
          <select name="val[setAggressiveLinkExtraction]" id="setAggressiveLinkExtraction">
          <?php
          // default value
          if (!isset($val["setAggressiveLinkExtraction"])) $val["setAggressiveLinkExtraction"]=1;
          ?>
          <option value="0" <?php if ($val["setAggressiveLinkExtraction"]==0) echo "selected"; ?>>No</option>
          <option value="1" <?php if ($val["setAggressiveLinkExtraction"]==1) echo "selected"; ?>>Yes</option>
          </select>
        </td>
      </tr>
      <tr>  
        <td>
          Obey robots.txt
          <a href="javascript: show_documentation('obeyRobotsTxt');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;
        </td>
        <td>
          <select name="val[obeyRobotsTxt]" id="obeyRobotsTxt">
          <?php
          // default value
          if (!isset($val["obeyRobotsTxt"])) $val["obeyRobotsTxt"] = 0;
          ?>
          <option value="0" <?php if ($val["obeyRobotsTxt"]==0) echo "selected"; ?>>No</option>
          <option value="1" <?php if ($val["obeyRobotsTxt"]==1) echo "selected"; ?>>Yes</option>
          </select>
        </td>
      </tr>
    </table>
    
  </td>
  
  <td width="233" valign="top">
  
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          Page/File limit
          <a href="javascript: show_documentation('setPageLimit');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;
        </td>
        <td>
          <input type="text" name="val[setPageLimit]" value="<?php if (isset($val["setPageLimit"])) echo $val["setPageLimit"]; ?>" size="6">
        </td>
      </tr>
      <tr>  
        <td>
          Traffic limit in kb
          <a href="javascript: show_documentation('setTrafficLimit');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;
        </td>
        <td>
          <input type="text" name="val[setTrafficLimit]" value="<?php if (isset($val["setTrafficLimit"])) echo $val["setTrafficLimit"]; ?>" size="6">
        </td>
      </tr>
      <tr>  
        <td>
          Contentsize limit in kb
          <a href="javascript: show_documentation('setContentSizeLimit');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;
        </td>
        <td>
          <input type="text" name="val[setContentSizeLimit]" value="<?php if (isset($val["setContentSizeLimit"])) echo $val["setContentSizeLimit"]; ?>" size="6">
        </td>
      </tr>
      <tr>
        <td>
          Connection timeout
          <a href="javascript: show_documentation('setConnectionTimeout');"><img src="info.gif" align="absbottom" border="0"></a>
          &nbsp;
        </td>
        <td>
          <input type="text" name="val[setConnectionTimeout]" value="<?php if (isset($val["setConnectionTimeout"])) echo $val["setConnectionTimeout"]; ?>" size="6">
        </td>
      </tr>
      
      <tr>
        <td>
          Stream timeout
          <a href="javascript: show_documentation('setStreamTimeout');"><img src="info.gif" align="absbottom" border="0"></a>
          &nbsp;
        </td>
        <td>
          <input type="text" name="val[setStreamTimeout]" value="<?php if (isset($val["setStreamTimeout"])) echo $val["setStreamTimeout"]; ?>" size="6">
        </td>
      </tr>
      
    </table>
    
  </td>
</tr>

<tr>
  <td colspan="3" style="font-size: 4px">&nbsp;</td>
</tr>
  
<tr>
  <td>
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td>
        Content-types to receive <a href="javascript: show_documentation('addContentTypeReceiveRule');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;
        </td>
      </tr>
      <tr>
        <td><input type="text" name="val[addReceiveContentType][0]" value="<?php if (isset($val["addReceiveContentType"][0])) echo $val["addReceiveContentType"][0]; ?>" size="30"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addReceiveContentType][1]" value="<?php if (isset($val["addReceiveContentType"][1])) echo $val["addReceiveContentType"][1]; ?>" size="30"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addReceiveContentType][2]" value="<?php if (isset($val["addReceiveContentType"][2])) echo $val["addReceiveContentType"][2]; ?>" size="30"></td>
      </tr>
    </table>
  </td>
  <td>
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td>Follow matches <a href="javascript: show_documentation('addURLFollowRule');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;</td>
      </tr>
      <tr>
        <td><input type="text" name="val[addFollowMatch][0]" value="<?php if (isset($val["addFollowMatch"][0])) echo $val["addFollowMatch"][0]; ?>" size="30"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addFollowMatch][1]" value="<?php if (isset($val["addFollowMatch"][1])) echo $val["addFollowMatch"][1]; ?>" size="30"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addFollowMatch][2]" value="<?php if (isset($val["addFollowMatch"][2])) echo $val["addFollowMatch"][2]; ?>" size="30"></td>
      </tr>
    </table>
  </td>
  <td>
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td>
          Non follow matches <a href="javascript: show_documentation('addURLFilterRule');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;
        </td>
      </tr>
      <tr>
        <td><input type="text" name="val[addNonFollowMatch][0]" value="<?php if (isset($val["addNonFollowMatch"][0])) echo $val["addNonFollowMatch"][0]; ?>" size="30"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addNonFollowMatch][1]" value="<?php if (isset($val["addNonFollowMatch"][1])) echo $val["addNonFollowMatch"][1]; ?>" size="30"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addNonFollowMatch][2]" value="<?php if (isset($val["addNonFollowMatch"][2])) echo $val["addNonFollowMatch"][2]; ?>" size="30"></td>
      </tr>
    </table>
  </td>
</tr>

<tr>
  <td>
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td>
        Link priorities <a href="javascript: show_documentation('addLinkPriority');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;
        </td>
        <td>Level</td>
      </tr>
      <tr>
        <td><input type="text" name="val[addLinkPriority][0][0]" value="<?php if (isset($val["addLinkPriority"][0][0])) echo $val["addLinkPriority"][0][0]; ?>" size="30"></td>
        <td><input type="text" name="val[addLinkPriority][0][1]" value="<?php if (isset($val["addLinkPriority"][0][1])) echo $val["addLinkPriority"][0][1]; ?>" size="3"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addLinkPriority][1][0]" value="<?php if (isset($val["addLinkPriority"][1][0])) echo $val["addLinkPriority"][1][0]; ?>" size="30"></td>
        <td><input type="text" name="val[addLinkPriority][1][1]" value="<?php if (isset($val["addLinkPriority"][1][1])) echo $val["addLinkPriority"][1][1]; ?>" size="3"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addLinkPriority][2][0]" value="<?php if (isset($val["addLinkPriority"][2][0])) echo $val["addLinkPriority"][2][0]; ?>" size="30"></td>
        <td><input type="text" name="val[addLinkPriority][2][1]" value="<?php if (isset($val["addLinkPriority"][2][1])) echo $val["addLinkPriority"][2][1]; ?>" size="3"></td>
      </tr>
    </table>
  </td>
  
  <td>
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td>
        Receive to tmp-file <a href="javascript: show_documentation('addStreamToFileContentType');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;
        </td>
      </tr>
      <tr>
        <td><input type="text" name="val[addReceiveToTmpFileMatch][0]" value="<?php if (isset($val["addReceiveToTmpFileMatch"][0])) echo $val["addReceiveToTmpFileMatch"][0]; ?>" size="30"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addReceiveToTmpFileMatch][1]" value="<?php if (isset($val["addReceiveToTmpFileMatch"][1])) echo $val["addReceiveToTmpFileMatch"][1]; ?>" size="30"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addReceiveToTmpFileMatch][2]" value="<?php if (isset($val["addReceiveToTmpFileMatch"][2])) echo $val["addReceiveToTmpFileMatch"][2]; ?>" size="30"></td>
      </tr>
    </table>
  </td>
</tr>

<tr>
  
  <td>
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="3">
        Tags to extract links from <a href="javascript: show_documentation('setLinkExtractionTags');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;
        </td>
      </tr>
      <tr>
        <td><input type="text" name="val[addLinkExtractionTags][0]" value="<?php if (isset($val["addLinkExtractionTags"][0])) echo $val["addLinkExtractionTags"][0]; ?>" size="10"></td>
        <td><input type="text" name="val[addLinkExtractionTags][1]" value="<?php if (isset($val["addLinkExtractionTags"][1])) echo $val["addLinkExtractionTags"][1]; ?>" size="10"></td>
        <td><input type="text" name="val[addLinkExtractionTags][2]" value="<?php if (isset($val["addLinkExtractionTags"][2])) echo $val["addLinkExtractionTags"][2]; ?>" size="10"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addLinkExtractionTags][3]" value="<?php if (isset($val["addLinkExtractionTags"][3])) echo $val["addLinkExtractionTags"][3]; ?>" size="10"></td>
        <td><input type="text" name="val[addLinkExtractionTags][4]" value="<?php if (isset($val["addLinkExtractionTags"][4])) echo $val["addLinkExtractionTags"][4]; ?>" size="10"></td>
        <td><input type="text" name="val[addLinkExtractionTags][5]" value="<?php if (isset($val["addLinkExtractionTags"][5])) echo $val["addLinkExtractionTags"][5]; ?>" size="10"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addLinkExtractionTags][6]" value="<?php if (isset($val["addLinkExtractionTags"][6])) echo $val["addLinkExtractionTags"][6]; ?>" size="10"></td>
        <td><input type="text" name="val[addLinkExtractionTags][7]" value="<?php if (isset($val["addLinkExtractionTags"][7])) echo $val["addLinkExtractionTags"][7]; ?>" size="10"></td>
        <td><input type="text" name="val[addLinkExtractionTags][8]" value="<?php if (isset($val["addLinkExtractionTags"][8])) echo $val["addLinkExtractionTags"][8]; ?>" size="10"></td>
      </tr>
    </table>
  </td>
        
  <td colspan="2">
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td>Authentication for URLs <a href="javascript: show_documentation('addBasicAuthentication');"><img src="info.gif" align="absbottom" border="0"></a></td>
        <td>Username</td>
        <td>Password</td>
      </tr>
      <tr>
        <td><input type="text" name="val[addBasicAuthentication][0][0]" value="<?php if (isset($val["addBasicAuthentication"][0][0])) echo $val["addBasicAuthentication"][0][0]; ?>" size="35"></td>
        <td><input type="text" name="val[addBasicAuthentication][0][1]" value="<?php if (isset($val["addBasicAuthentication"][0][1])) echo $val["addBasicAuthentication"][0][1]; ?>" size="15"></td>
        <td><input type="text" name="val[addBasicAuthentication][0][2]" value="<?php if (isset($val["addBasicAuthentication"][0][2])) echo $val["addBasicAuthentication"][0][2]; ?>" size="15"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addBasicAuthentication][1][0]" value="<?php if (isset($val["addBasicAuthentication"][1][0])) echo $val["addBasicAuthentication"][1][0]; ?>" size="35"></td>
        <td><input type="text" name="val[addBasicAuthentication][1][1]" value="<?php if (isset($val["addBasicAuthentication"][1][1])) echo $val["addBasicAuthentication"][1][1]; ?>" size="15"></td>
        <td><input type="text" name="val[addBasicAuthentication][1][2]" value="<?php if (isset($val["addBasicAuthentication"][1][2])) echo $val["addBasicAuthentication"][1][2]; ?>" size="15"></td>
      </tr>
      <tr>
        <td><input type="text" name="val[addBasicAuthentication][2][0]" value="<?php if (isset($val["addBasicAuthentication"][2][0])) echo $val["addBasicAuthentication"][2][0]; ?>" size="35"></td>
        <td><input type="text" name="val[addBasicAuthentication][2][1]" value="<?php if (isset($val["addBasicAuthentication"][2][1])) echo $val["addBasicAuthentication"][2][1]; ?>" size="15"></td>
        <td><input type="text" name="val[addBasicAuthentication][2][2]" value="<?php if (isset($val["addBasicAuthentication"][2][2])) echo $val["addBasicAuthentication"][2][2]; ?>" size="15"></td>
      </tr>
    </table>
  </td>
    
</tr>
        
<tr>
  <td colspan="3">
    Temporary working-directory to use <a href="javascript: show_documentation('setWorkingDirectory');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;<br>
    <input type="text" name="val[setWorkingDirectory]" value="<?php if (isset($val["setWorkingDirectory"])) echo $val["setWorkingDirectory"]; ?>" size="50" style="width:400px">
    <br><br>
    User-Agent String <a href="javascript: show_documentation('setUserAgentString');"><img src="info.gif" align="absbottom" border="0"></a>&nbsp;<br>
    <input type="text" name="val[setUserAgentString]" value="<?php if (isset($val["setUserAgentString"])) echo $val["setUserAgentString"]; ?>" size="50" style="width:400px">
    <br><br>
  </td>
</tr>
  
</table>

<br>

<table width="700" cellspacing="2" cellpadding="2" border="0" class="bordered">
<tr>
  <td colspan="3" class="head">Output</td>
</tr>

<tr>
  <td>
  
    <table>
      <tr>
        <td><input type="checkbox" name="output[requested_url]" value="1" <?php if (isset($output["requested_url"])) echo "checked"; ?>></td>
        <td>Requested URL</td>
      </tr>
  
      <tr>
        <td><input type="checkbox" name="output[http_status_code]" value="1" <?php if (isset($output["http_status_code"])) echo "checked"; ?>></td>
        <td>HTTP Status-code</td>
      </tr>
  
      <tr>
        <td><input type="checkbox" name="output[content_type]" value="1" <?php if (isset($output["content_type"])) echo "checked"; ?>></td>
        <td>Content-Type</td>
      </tr>
      
      <tr>
        <td><input type="checkbox" name="output[content_size]" value="1" <?php if (isset($output["content_size"])) echo "checked"; ?>></td>
        <td>Content-Size</td>
      </tr>
      
      <tr>
        <td><input type="checkbox" name="output[content_received]" value="1" <?php if (isset($output["content_received"])) echo "checked"; ?>></td>
        <td>Flag content received (y/n)</td>
      </tr>
      
      <tr>
        <td><input type="checkbox" name="output[content_received_completely]" value="1" <?php if (isset($output["content_received_completely"])) echo "checked"; ?>></td>
        <td>Flag content received completely (y/n)</td>
      </tr>
      
      <tr>
        <td><input type="checkbox" name="output[bytes_received]" value="1" <?php if (isset($output["bytes_received"])) echo "checked"; ?>></td>
        <td>Bytes received</td>
      </tr>
      
    </table>
    
  </td>
  
  <td valign="top">
  
    <table>
      <tr>
        <td><input type="checkbox" name="output[header_send]" value="1" <?php if (isset($output["header_send"])) echo "checked"; ?>></td>
        <td>Header send (complete)</td>
      </tr>
  
      <tr>
        <td><input type="checkbox" name="output[header]" value="1" <?php if (isset($output["header"])) echo "checked"; ?>></td>
        <td>Header received (complete)</td>
      </tr>
  
      <tr>
        <td><input type="checkbox" name="output[referer_url]" value="1" <?php if (isset($output["referer_url"])) echo "checked"; ?>></td>
        <td>Refering URL</td>
      </tr>
      
      <tr>
        <td><input type="checkbox" name="output[refering_linkcode]" value="1" <?php if (isset($output["refering_linkcode"])) echo "checked"; ?>></td>
        <td>Refering linkcode (html/js)</td>
      </tr>
      
      <tr>
        <td><input type="checkbox" name="output[refering_link_raw]" value="1" <?php if (isset($output["refering_link_raw"])) echo "checked"; ?>></td>
        <td>Refering link raw</td>
      </tr>
      
      <tr>
        <td><input type="checkbox" name="output[refering_linktext]" value="1" <?php if (isset($output["refering_linktext"])) echo "checked"; ?>></td>
        <td>Refering linktext</td>
      </tr>
    </table>
    
  </td>
  
  <td valign="top">
  
    <table>
      <tr>
        <td><input type="checkbox" name="output[nr_found_links]" value="1" <?php if (isset($output["nr_found_links"])) echo "checked"; ?>></td>
        <td>No. of found links in content</td>
      </tr>
  
      <tr>
        <td valign="top"><input type="checkbox" name="output[all_found_links]" value="1" <?php if (isset($output["all_found_links"])) echo "checked"; ?>></td>
        <td>Display all found links<br>Note: May be a lot !!</td>
      </tr>
  
      <tr>
        <td><input type="checkbox" name="output[received_to_file]" value="1" <?php if (isset($output["received_to_file"])) echo "checked"; ?>></td>
        <td>Flag content received to tmpfile (y/n)</td>
      </tr>
      
      <tr>
        <td><input type="checkbox" name="output[tmpfile_name_size]" value="1" <?php if (isset($output["tmpfile_name_size"])) echo "checked"; ?>></td>
        <td>Name of tmpfile and its size</td>
      </tr>
      
      <tr>
        <td><input type="checkbox" name="output[received_to_memory]" value="1" <?php if (isset($output["received_to_memory"])) echo "checked"; ?>></td>
        <td>Flag content received to memory (y/n)</td>
      </tr>
      
      <tr>
        <td><input type="checkbox" name="output[memory_content_size]" value="1" <?php if (isset($output["memory_content_size"])) echo "checked"; ?>></td>
        <td>Size of content in memory</td>
      </tr>
    </table>
    
  </td>
</tr>
</table>

<br>

<table width="700" cellspacing="0" cellpadding="3" border="0">
<tr>
  <td class="white" width="300" align="right">
    <input type="checkbox" name="misc[force_flush]" value="1" <?php if (isset($misc["force_flush"])) echo "checked"; ?>>
  </td>
  <td class="white">Force flushing of output</td>
  <td class="white" align="right">
    <input type="button" name="start_crawling" value="&gt;&gt; Start crawling with this settings" onClick="setAction('start_crawling', '_blank');" style="width: 230px">
  </td>
</tr>
</table>
  
<input type="hidden" name="action" value="">
</form>

</body>
</html>
