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
<BODY><?php

$n=$_GET["n"];

$swfurl="live_video.swf?n=".urlencode($n);
$scale="noborder";

$room = $_GET['n'];
if (!$room) $room = $_POST['n'];

//do not allow access to other folders
if ( strstr($room,"/") || strstr($room,"..") ) 
{
	echo "Access denied.";
	exit;
}



$day=date("y-M-j",time());
$chatfile = "uploads/$room/Log$day.html";


?><div id="videowhisper_video">
<object width="100%" height="100%">
<param name="movie" value="<?=$swfurl?>"></param><param name="scale" value="<?=$scale?>" /><param name="salign" value="lt"></param><param name="wmode" value="transparent" /><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed width="100%" height="100%" scale="<?=$scale?>" salign="lt" src="<?=$swfurl?>" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="transparent"></embed>
</object></div><style type="text/css">
<!--
#videowhisper_video
{
	position:absolute;
	height:100%;
	width:100%;
	left:0px;
	top:0px;
	z-index:1;
}

#chathistory {
	overflow: auto; 
	height:150px;
	position:absolute;
	bottom:20px;
	left:20px;
	z-index:2;
	font-size: 20px;
	text-shadow: 1px 1px 1px #fff;
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
</style><div id="chathistory" name="chathistory">
Loading chat history...
</div><script>
load('<?php echo $chatfile?>', 'chathistory');
setInterval( "load('<?php echo $chatfile?>', 'chathistory')", 6000);
</script>
</BODY>