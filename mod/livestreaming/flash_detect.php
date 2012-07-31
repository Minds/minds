  <p>
	This application requires Flash browser plugin. <script type="text/javascript" src="flash_detect_min.js"> </script>
	<script type="text/javascript">
	
	var updateWarning = false;

	if(FlashDetect.installed)
	{
	document.write("Flash version detected: " + FlashDetect.major + "."+ FlashDetect.minor + " "); 
	
	
	if(!FlashDetect.versionAtLeast(11, 2))
	{
		alert("Flash was detected but is too old to run this application. Upgrade your Flash plugin to proceed!"); 
		updateWarning = true;
	}
	
	}
	else
	{
		alert("Flash was not detected in your browser: Flash plugin is required to use this application!"); 
		updateWarning = true;
	}
	
	if (updateWarning)	document.write("<B class=warning>Update to latest flash player: <a href=\"http://get.adobe.com/flashplayer/\" target=\"_blank\">http://get.adobe.com/flashplayer/</a> !</B>");
	</script>
  </p>