<?php
/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */

// once elgg_view stops throwing all sorts of junk into $vars, we can use 
/*$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}*/

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) ."/kaltura/api_client/includes.php");
		
		$kmodel = KalturaModel::getInstance();
 		$userId = KalturaHelpers::getLoggedUserId();
		$ks = $kmodel->getClientSideSession();
        $partnerId = elgg_get_plugin_setting('partner_id', 'kaltura_video');
        $secret = elgg_get_plugin_setting('admin_secret', 'kaltura_video');
		
		$kcw_uid = elgg_get_plugin_setting('custom_kcw',"kaltura_video");
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
		<title>KRecord - Kaltura Chromeless Recorder - JS Example</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.1/swfobject.js"></script>
    <script type="text/javascript">
	    if (swfobject.hasFlashPlayerVersion("9.0.0")) {
	      var fn = function() {
	        var att = { name:"krecorder",
						data:"Krecord.swf", 
						width:"400", 
						height:"300",
						wmode:"opaque"
					};
	        var par = { flashvars:"host=www.minds.tv" +
							"&kshowId=-1" +
							"&autoPreview=1" +
							"&pid=<?php echo $partnerId;?>" +
							"&subpid=<?php echo $partnerId*100;?>" +
							"&uid=<?php echo $userId;?>" +
							"&ks=<?php echo $ks;?>" +
							"&rtmphost=rtmp://www.minds.tv/oflaDemo",
						allowScriptAccess:"always",
						allowfullscreen:"true",
						bgcolor:"#ccc",
						wmode:"opaque"
					};
	        var id = "krecorder";
	        var myObject = swfobject.createSWF(att, par, id);
	      };
	      swfobject.addDomLoadEvent(fn);
	    }
    </script>
  </head>
  <body>
	<div id="debuginfocontainer" style="display:none;float:right;margin-right:-175px;">
		<h2>Create Session Rsult:</h2>
		<pre>
			array(2) {
  ["result"]=>
  array(5) {
    ["ks"]=>
    string(132) "NjJlMTUxZWJjYWMzOWQwMTlmYTNkZTRjZjYxODgwZjJhNjg5ZGQ1Ynw1MTA5ODE7NTEwOTgxOzEzNDUyOTc3ODA7MDsxMzQ1MjExMzgwLjk0MTY7NTEwOTgxO2VkaXQ6Kjs7"
    ["partner_id"]=>
    string(6) "510981"
    ["subp_id"]=>
    string(8) "51098100"
    ["uid"]=>
    string(6) "510981"
    ["serverTime"]=>
    int(1345211380)
  }
  ["debug"]=>
  array(5) {
    ["sigtype"]=>
    int(1)
    ["signature"]=>
    string(32) "2c2e5afa7a031abf9951661af7dc536b"
    ["execute_impl_time"]=>
    float(0.000275135040283)
    ["execute_time"]=>
    float(0.025486946106)
    ["total_time"]=>
    string(17) "0.025652885437012"
  }
}
		</pre>
	</div>
    <div id="krecorder">
		This is where the KRecord will be embedded.
	</div>
	<div id="kdpcontainer" style="float:right;display:none;">
		KDP:
		 <object name="kdp" id="kdp" 
				type="application/x-shockwave-flash" 
				allowScriptAccess="always" allowNetworking="all" 
				allowFullScreen="true" height="332" width="400" 
				data="http://www.kaltura.com/index.php/kwidget/wid/_0/uiconf_id/1000106">
				  <param name="allowScriptAccess" value="always"/>
				  <param name="allowNetworking" value="all"/>
				  <param name="allowFullScreen" value="true"/>
				  <param name="bgcolor" value="#000000"/>
				  <param name="movie" value="http://www.kaltura.com/index.php/kwidget/wid/_0/uiconf_id/1000106"/>
				  <param name="flashVars" value="readyF=onKdpReady"/>
				  <param name="wmode" value="opaque"/>
		 </object>
	</div>
	<div>
		<form>
			<fieldset style="display:block;width:300px;margin:10px;padding: 1em;font:80%/1 sans-serif;">
				 <legend>Camera Quality</legend>
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="quality" title="An integer that specifies the required level of picture quality,
as determined by the amount of compression being applied to each video frame. Acceptable values range from 1
(lowest quality, maximum compression) to 100 (highest quality, no compression)." />
					Quality: </label>
				<INPUT type="text" id="quality" value="85" title="An integer that specifies the required level of picture quality,
as determined by the amount of compression being applied to each video frame. Acceptable values range from 1
(lowest quality, maximum compression) to 100 (highest quality, no compression)."/>
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="bandwidth" title="Specifies the maximum amount of bandwidth that the current outgoing video feed can use, in bytes per second. To specify that Flash Player video can use as much bandwidth as needed to
maintain the value of quality, pass 0 for bandwidth. The default value is 16384." />
					BandWidth: </label>
				<INPUT type="text" id="bandwidth" value="16384" title="Specifies the maximum amount of bandwidth that the current outgoing video feed can use, in bytes per second. To specify that Flash Player video can use as much bandwidth as needed to
maintain the value of quality, pass 0 for bandwidth. The default value is 16384." />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="videoWidth" />
					Video Width: 
				</label>
				<INPUT type="text" id="videoWidth" value="320" />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="videoHeight" />
					Video Height: 
				</label>
				<INPUT type="text" id="videoHeight" value="240" />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="videoFPS" />
					Video FPS: 
				</label>
				<INPUT type="text" id="videoFPS" value="25" />
				<INPUT type="button" value="Set Camera Quality" onclick="setCameraQuality();return false;" />
			</fieldset>
		</form>
	</div>
	<a id="addentryparamslink" href="#" onclick="showEntryInfo ();return false;">Add entry parameters</a>
	<div id="entryInfoParameters" style="display:none;">
		<form>
			<fieldset style="display:block;width:300px;margin:10px;padding: 1em;font:80%/1 sans-serif;">
				 <legend onclick="showEntryInfo ();return false;">Add entry parameters</legend>
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="entry_name" />
					entry_name: </label>
				<INPUT type="text" id="entry_name" value="my added entry,,,whoo!" />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="entry_tags" />
					entry_tags: </label>
				<INPUT type="text" id="entry_tags" value="this is, my entry, test, webcam" />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="entry_description" />
					entry_description: 
				</label>
				<INPUT type="text" id="entry_description" value="this is my testing entry for webcam" />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="credits_screen_name" />
					credits_screen_name: 
				</label>
				<INPUT type="text" id="credits_screen_name" value="my entry contributor name" />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="credits_site_url" />	
					credits_site_url: </label>
				<INPUT type="text" id="credits_site_url" value="http://justatry.com" />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="thumb_offset" />
					thumb_offset: 
				</label>
				<INPUT type="text" id="thumb_offset" value="1" />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="admin_tags" />
					admin_tags: 
				</label>
				<INPUT type="text" id="admin_tags" value="an admin, tag admin" />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="license_type" />
					license_type: 
				</label>
				<INPUT type="text" id="license_type" value="CC-0.1" />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="credit" />
					credit: 
				</label>
				<INPUT type="text" id="credit" value="mycredit" />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="group_id" />
					group_id: 
				</label>
				<INPUT type="text" id="group_id" value="" />
				<label style="float:left;width:45%;margin-right:0.5em;padding-top:0.2em;text-align:left;font-weight:bold;" for="partner_data" />
					partner_data: 
				</label>
				<INPUT type="text" id="partner_data" value="<pd>my_pd</pd>" />
			</fieldset>
		</form>
	</div>
	<div id="status">
		<h2>Current Status</h2>
		<h4 id="currentStatus" style="margin-left:10px;"></h4>
		<h2>Status Log</h2>
		<div id="statusLog" style="margin-left:10px;border:1px solid;width:600px;"></div>
	</div>
	<script type="text/javascript">
		var entryId2Play = 0;
		var apptime = 0;
		var d = new Date();
		apptime = d.getTime();
		function writeToLog (msg, error) 
		{
			$('#currentStatus').html(msg);
			if (error == true)
				$('#currentStatus').css("color","red");
			else
				$('#currentStatus').css("color","blue");
			var d = new Date();
			var t = d.getTime() - apptime;
			$('#statusLog').append(t + ' :: ' + msg + '<br />');
		}
		function setCameraQuality ()
		{
			var vidQ = $('#quality').val();
			var vidBW = $('#bandwidth').val();
			var vidW = $('#videoWidth').val();
			var vidH = $('#videoHeight').val();
			var vidFPS = $('#videoFPS').val();
			$('#krecorder').get(0).setQuality (vidQ, vidBW, vidW, vidH, vidFPS);
		}
		function addEntry ()
		{
			var entryName = $('#entry_name').val();
			var entryTags = $('#entry_tags').val();
			var entryDescription = $('#entry_description').val();
			var screenName = $('#credits_screen_name').val();
			var siteUrl = $('#credits_site_url').val();
			var thumbOffset = $('#thumb_offset').val();
			if (thumbOffset == '')
				thumbOffset = -1;
			var adminTags = $('#admin_tags').val();
			var licenseType = $('#license_type').val();
			var userCredit = $('#credit').val();
			var groupId = $('#group_id').val();
			var partnerData = $('#partner_data').val();
			$('#krecorder').get(0).addEntry(entryName, entryTags, entryDescription, screenName, siteUrl, 
											thumbOffset, adminTags, licenseType, userCredit, groupId, partnerData);
		}
		function addEntryComplete (addedEntry)
		{
		var thumbnailUrl = 'http://cdn.kaltura.com/p/510981/sp/51098100/thumbnail/entry_id/' + addedEntry.entryId + '/width/120/height/90';
			writeToLog ('your newly created entryId is: ' + addedEntry.entryId + 
					'<br />You can access the new entry at:' + 
					'<input type="text" style="width:300px;" value="' +
					'http://www.kaltura.com/api/getentry?entry_id=' + addedEntry.entryId + 
					'&ks=NjJlMTUxZWJjYWMzOWQwMTlmYTNkZTRjZjYxODgwZjJhNjg5ZGQ1Ynw1MTA5ODE7NTEwOTgxOzEzNDUyOTc3ODA7MDsxMzQ1MjExMzgwLjk0MTY7NTEwOTgxO2VkaXQ6Kjs7&partner_id=510981&subp_id=51098100&uid=510981" />' + 
					'<br />new entry thumbnail: <img src="' + thumbnailUrl + '" />' + 
					'<br /><input type="button" value="Open in Player" onclick="playEntryInPlayer (\'' + addedEntry.entryId + '\');return false;" />' + 
					' || <input type="button" value="Close Player" onclick="closeKdp();return false;" />', false);
		}
		function playEntryInPlayer (entryId)
		{
			entryId2Play = entryId;
			if ($('#kdpcontainer').css("display") == "none") {
				$('#kdpcontainer').css('display', 'block');
			} else {
				kdpDoLoadVideo();
			}
		}
		function closeKdp ()
		{
			$('#kdp').get(0).dispatchKdpEvent('doPause');
			$('#kdpcontainer').css('display', 'none');
		}
		function onKdpReady ()
		{
			setTimeout ( kdpDoLoadVideo(), 1000 );
		}
		function kdpDoLoadVideo() {
			$('#kdp').get(0).insertMedia("-1",entryId2Play,'true');
		}
		function addEntryFail (err)
		{
			writeToLog ('failed to add entry:\n' + err, true);
		}
		function previewEnd ()
		{
			writeToLog ('preview finished', false);
		}
		function flushComplete ()
		{
			writeToLog ('finished sending recorded stream to server.', false);
			writeToLog ('recorded time: ' + $('#krecorder').get(0).getRecordedTime());
		}
		function recordStart ()
		{
			writeToLog ('started sending recorded camera stream to server.', false);
		}
		function connected ()
		{
			writeToLog ('connected to server.', false);
		}
		function deviceDetected ()
		{
			writeToLog ('found recording devices on user computer.', false);
		}
		function resizeRecorder ()
		{
			$('#krecorder').effect("size", { to: {width: 600,height: 500} }, 2000);
		}
		function showDebugInfo () 
		{
			$('#debuginfocontainer').toggle ();
		}
		function showEntryInfo ()
		{
			$('#entryInfoParameters').toggle ();
			$('#addentryparamslink').toggle ();
		}
	</script>
	<INPUT type="button" value="resize recorder" onclick="resizeRecorder();return false;" />
	Show debug info: <input id="showdebugCheck" type="checkbox" name="showdebugCheck" onchange="showDebugInfo();return false;" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js"></script>
	<!--
		list of available properties on the created entry:
			plays
			width
			height
			views
			entryId
			entryName
			partnerId
			subpId
			tags
			status
			entryType
			mediaType
			kshowId
			kuserId
			puserId
			createdAtAsInt
			createdAt
			duration
			description
			thumbnailUrl
			entryVersion
			displayCredit
			userScreenName
			dataUrl
			downloadUrl
			adminTags
			sourceLink
			userLandingPage
	-->
  </body>
</html>

<!-- Hosting24 Analytics Code -->

<script type="text/javascript" src="http://stats.hosting24.com/count.php"></script>

<!-- End Of Analytics Code -->


  
<div>
	<label><?php echo elgg_echo('video'); ?></label><br />
	<?php echo elgg_view('input/file', array('name' => 'upload')); ?>
</div>
<div>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
</div>
<div>
	<label><?php echo elgg_echo('description'); ?></label>
	<?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc)); ?>
</div>
<div>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>
<div>
	<label><?php echo elgg_echo('kalturavideo:license:label'); ?></label>
    <?php echo elgg_view('input/dropdown', array(	'name' => 'license',
																'id' => 'license',
																'options_values' => array(
																	'cca' => elgg_echo('kalturavideo:license:cca'),
																	'ccs' => elgg_echo('kalturavideo:license:ccs'),
																	'gpl' => elgg_echo('kalturavideo:license:gpl')
																	),
																
																));?>
</div>                                                             															
<?php

$categories = elgg_view('input/categories', $vars);
if ($categories) {
	echo $categories;
}

?>
<div>
	<label><?php echo elgg_echo('access'); ?></label><br />
	<?php echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id)); ?>
</div>
<div class="elgg-foot">
<?php

echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'file_guid', 'value' => $guid));
}

echo elgg_view('input/submit', array('value' => $submit_label));

?>
</div>
