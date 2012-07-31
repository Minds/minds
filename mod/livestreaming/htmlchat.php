<HEAD>
<SCRIPT>
function getContent(url, target) {
  document.getElementById(target).innerHTML = ' Fetching data...';
  if (window.XMLHttpRequest) {
    req = new XMLHttpRequest();
  } else if (window.ActiveXObject) {
    req = new ActiveXObject("Microsoft.XMLHTTP");
  }
  if (req != undefined) {
    req.onreadystatechange = function() {getContentDone(url, target);};
    req.open("GET", url, true);
    req.send("");
  }
}  

function getContentDone(url, target) {
  if (req.readyState == 4) { // only if req is "loaded"
	if (req.status == 200) { // only if "OK"
      document.getElementById(target).innerHTML = req.responseText;
	  document.getElementById(target).scrollTop = document.getElementById(target).scrollHeight;
	  
    } else {
      document.getElementById(target).innerHTML=" Chat History:\n"+ req.status + "\n" +req.statusText;
    }
  }
}

function load(name, div) {
	getContent(name,div);
	return false;
}
</SCRIPT>
<title><?=$n?> Text Chat</title>
</HEAD>
<BODY>
<?php
$room = $_GET['n'];
if (!$room) $room = $_POST['n'];

//do not allow access to other folders
if ( strstr($room,"/") || strstr($room,"..") ) 
{
	echo "Access denied.";
	exit;
}

$name = $_POST['name'];
$message = $_POST['message'];

$day=date("y-M-j",time());
$chatfile = "uploads/$room/Log$day.html";
?>
<form action="htmlchat.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
  Name:
  <input name="name" type="text" id="name" size="16" maxlength="32" value="<?php echo $name ?>" />
  
  Message:
<input name="message" type="text" id="message" size="32" />
  <input type="hidden" name="n" id="n" value="<?php echo $room ?>" />
  <input type="submit" name="button" id="button" value="Send" />
</form>
<?php
if ($name && $message && $room!="null")
{

$dir="uploads";
if (!file_exists($dir)) mkdir($dir);
@chmod($dir, 0755);
$dir.="/$room";
if (!file_exists($dir)) mkdir($dir);
@chmod($dir, 0755);

$show = "<font color=\"#337733\"><B>$name</B>: $message</font>";

$dfile = fopen($chatfile,"a");
fputs($dfile, $show . "<BR>");
fclose($dfile);

$dir.="/external";
if (!file_exists($dir)) mkdir($dir);
@chmod($dir, 0755);

$dfile = fopen("uploads/$room/external/$day.html","a");
fputs($dfile, "\"" . time(). "\",\"" . $show . "\";;\r\n");
fclose($dfile);
}

//if (file_exists($chatfile)) echo implode('', file($chatfile));

?>
<style type="text/css">
<!--
#chathistory {
	overflow: auto; 
	height:150px;
	width:100%;
	background-color: #E3E3E3;
 	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}
input {
	border: 1px solid #BBB;
	color: #666;
	font-weight: normal;
 	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}
body {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 15px;
	color: #666;
	background-color: #eee;
}
-->
</style>

<div id="chathistory" name="chathistory">
Loading chat history...
</div>
<script>
load('<?php echo $chatfile?>', 'chathistory');
setInterval( "load('<?php echo $chatfile?>', 'chathistory')", 6000);
</script>
</BODY>