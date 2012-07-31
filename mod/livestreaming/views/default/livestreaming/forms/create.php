<?php

$generated =  base_convert((time()-1224000000).rand(0,10),10,36);
$roomnm = get_input ('roomname');

// get username
$ver=explode('.', get_version(true));			
if ($ver[1]>7) $ElggUser=elgg_get_logged_in_user_entity();
else $ElggUser=get_loggedin_user();
$username=$ElggUser->get("username");

// function view select_access_create
function selectAccess ($data,$default) {
  $access_name = array ("All","Groups","Moderators","Owner","None");
  echo "<select name=$data>";
  for ($no=0; $no<=4; $no++) {
    if ($no==$default) echo "<option selected='selected' value=$no>$access_name[$no]</option>";
    else echo "<option value=$no>$access_name[$no]</option>";
  }
  echo "</select> <br />"; 
}
// change name
function selectAccess2 ($data,$default) {
  $access_name = array ("All","Groups","Moderators","Owner","None", "Visitors");
  echo "<select name=$data>";
  for ($no=0; $no<=5; $no++) {
    if ($no==$default) echo "<option selected='selected' value=$no>$access_name[$no]</option>";
    else echo "<option value=$no>$access_name[$no]</option>";
  }
  echo "</select> <br />"; 
}

if (defined('ACCESS_DEFAULT')) $access_id = ACCESS_DEFAULT;
else $access_id=2;

?>
			<script language="JavaScript">
			function censorName()
			{
				document.roomForm.room.value = document.roomForm.room.value.replace(/^[\s]+|[\s]+$/g, '');
				document.roomForm.room.value = document.roomForm.room.value.replace(/[^0-9a-zA-Z_\-]+/g, '-');
				document.roomForm.room.value = document.roomForm.room.value.replace(/\-+/g, '-');
				document.roomForm.room.value = document.roomForm.room.value.replace(/^\-+|\-+$/g, '');
				if (document.roomForm.room.value.length>2) return true;
				else return false;
			}

			function textCounter(field,cntfield,maxlimit) {
				// if too long...trim it!
				if (field.value.length > maxlimit) {
					field.value = field.value.substring(0, maxlimit);
				} else {
					// otherwise, update 'characters left' counter
					cntfield.value = maxlimit - field.value.length;
				}
			}

			</script>

<div class="livestreaming_new">

	<form action="<?php echo elgg_add_action_tokens_to_url($vars['url']."action/livestreaming/create"); ?>" method="post" name="roomForm">
			<label><?php echo elgg_echo("livestreaming:name"); ?>:</label><br />
			
			<input type="text" id="room" maxlength="22" size="22" name='room' value='<?php if (get_input ('roomname')) echo $roomnm; else echo $generated ?>' onKeyDown="textCounter(document.roomForm.room,document.roomForm.remLen1,22); censorName()" onKeyUp="textCounter(document.roomForm.room,document.roomForm.remLen1,22); censorName()" onChange="censorName()" >
			 <div  style='float:right'><input readonly type="text" name="remLen1" size="3" maxlength="3" value="22"></div>
			 
			 <?php echo $msg ?><br />
      <?php echo elgg_echo("livestreaming:name_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:description"); ?>:</label><br />
      <textarea name="description" cols="32" rows="3"></textarea> <br />
      <?php echo elgg_echo("livestreaming:description_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:room_limit"); ?>:</label><br />
			<input name='room_limit' value="0" size="10"><br />
			<?php echo elgg_echo("livestreaming:room_limit_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:visitor"); ?>:</label><br />
      <select name="visitor">
      <?php if (datalist_get('lstr_visitor')==1) echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
        elseif (datalist_get('lstr_visitor')==0) echo "<option selected=\"selected\" value=\"0\">no</option><option value=\"1\">yes</option></select><br />";
        else echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
      ?>
      <?php echo elgg_echo("livestreaming:visitor_descr"); ?><br /><br />
	  <label><?php echo elgg_echo("livestreaming:showCamSettings"); ?>:</label><br />
      <?php selectAccess ('showCamSettings',0) ?>
      <?php echo elgg_echo("livestreaming:showCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:advancedCamSettings"); ?>:</label><br />
      <?php selectAccess ('advancedCamSettings',0) ?>
      <?php echo elgg_echo("livestreaming:advancedCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:configureSource"); ?>:</label><br />
      <?php selectAccess ('configureSource',0) ?>
      <?php echo elgg_echo("livestreaming:configureSource_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:overLogo"); ?>:</label><br />
			<input name='overLogo' value="logo.png" size="10"><br />
			<?php echo elgg_echo("livestreaming:overLogo_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:overLink"); ?>:</label><br />
      <textarea name="overLink" cols="32" rows="3">http://www.videowhisper.com</textarea> <br />
      <?php echo elgg_echo("livestreaming:overLink_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:filterRegex"); ?>:</label><br />
			<input name='filterRegex' value='(?i)(fuck|cunt)(?-i)' size="70"> <br />
			<?php echo elgg_echo("livestreaming:filterRegex_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:filterReplace"); ?>:</label><br />
			<input name='filterReplace' value=' ** ' maxlength="22" size="22"> <br />
			<?php echo elgg_echo("livestreaming:filterReplace_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:userList"); ?>:</label><br />
      <textarea name="userList" cols="32" rows="3"></textarea> <br />
      <?php echo elgg_echo("livestreaming:userList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:moderatorList"); ?>:</label><br />
      <textarea name="moderatorList" cols="32" rows="3"><?php echo $username; ?></textarea> <br />
      <?php echo elgg_echo("livestreaming:moderatorList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:cleanUp"); ?>:</label><br />
      <input name='cleanUp' value='0' size="10"><br />
      <?php echo elgg_echo("livestreaming:cleanUp_descr"); ?><br /><br />

<h3 class="settings"><?php echo elgg_echo('livestreaming:broadcasting'); ?></h3>
      <label><?php echo elgg_echo("livestreaming:welcome"); ?>:</label><br />
      <textarea name="welcome" cols="32" rows="3">Welcome to livestreaming room.</textarea><br />
			<?php echo elgg_echo("livestreaming:welcome_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:onlyVideo"); ?>:</label><br />
      <?php selectAccess ('onlyVideo',4) ?>
      <?php echo elgg_echo("livestreaming:onlyVideo_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:noVideo"); ?>:</label><br />
      <?php selectAccess ('noVideo',4) ?>
      <?php echo elgg_echo("livestreaming:noVideo_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:noEmbeds"); ?>:</label><br />
      <?php selectAccess ('noEmbeds',4) ?>
      <?php echo elgg_echo("livestreaming:noEmbeds_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:showTimer"); ?>:</label><br />
      <?php selectAccess ('showTimer',0) ?>
      <?php echo elgg_echo("livestreaming:showTimer_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:writeText"); ?>:</label><br />
      <?php selectAccess ('writeText',0) ?>
      <?php echo elgg_echo("livestreaming:writeText_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:resolution"); ?>:</label><br />
      <select name="resolution"><option selected="selected" value="320x240">320x240</option><option value="160x120">160x120</option><option value="176x144">176x144</option><option value="352x288">352x288</option><option value="640x480">640x480</option></select><br />
			<?php echo elgg_echo("livestreaming:resolution_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:camfps"); ?>:</label><br />
      <select name="camfps"><option selected="selected" value="15">15</option><option value="10">10</option><option value="12">12</option><option value="20">20</option><option value="25">25</option><option value="30">30</option></select> <br />
			<?php echo elgg_echo("livestreaming:camfps_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:micrate"); ?>:</label><br />
      <select name="micrate"><option selected="selected" value="11">11</option><option value="22">22</option><option value="44">44</option><option value="48">48</option></select> <br />
      <?php echo elgg_echo("livestreaming:micrate_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:camBandwidth"); ?>:</label><br />
      <input name='camBandwidth' value='40960' size="10"><br />
      <?php echo elgg_echo("livestreaming:camBandwidth_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:floodProtection"); ?>:</label><br />
			<input name='floodProtection' value="<?php if (datalist_get('lstr_floodProtection')) echo datalist_get('lstr_floodProtection'); else echo '1'; ?>" size="10"><br />
			<?php echo elgg_echo("livestreaming:floodProtection_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:labelColor"); ?>:</label><br />
      <input name='labelColor' value='FFFFFF' size="10"><br />
      <?php echo elgg_echo("livestreaming:labelColor_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:privateTextchat"); ?>:</label><br />
      <?php selectAccess ('privateTextchat',0) ?>
      <?php echo elgg_echo("livestreaming:privateTextchat_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:soundQuality"); ?>:</label><br />
      <input name='soundQuality' value='9' size="10"><br />
      <?php echo elgg_echo("livestreaming:soundQuality_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:verboseLevel"); ?>:</label><br />
      <select name="verboseLevel"><option selected="selected" value="2">Warning</option><option value="0">Nothing</option><option value="1">Failure</option><option value="3">Success</option><option value="4">Action</option></select> <br />
      <?php echo elgg_echo("livestreaming:verboseLevel_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:layoutCode"); ?>:</label><br />
      <textarea name="layoutCode" cols="32" rows="3">id=0&label=Webcam&x=10&y=40&width=242&height=235&resize=true&move=true; id=1&label=Chat&x=260&y=40&width=340&height=235&resize=true&move=true; id=2&label=Users&x=610&y=40&width=180&height=235&resize=true&move=true</textarea> <br />
      <?php echo elgg_echo("livestreaming:layoutCode_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:fillWindow"); ?>:</label><br />
      <select name="fillWindow"><option selected="selected" value="0">no</option><option value="1">yes</option></select><br />
      <?php echo elgg_echo("livestreaming:fillWindow_descr"); ?><br /><br />

 
<h3 class="settings"><?php echo elgg_echo('livestreaming:watchvideo'); ?></h3>
      <label><?php echo elgg_echo("livestreaming:welcome2"); ?>:</label><br />
      <textarea name="welcome2" cols="32" rows="3">Welcome to livestreaming room.</textarea><br />
			<?php echo elgg_echo("livestreaming:welcome2_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:changeName"); ?>:</label><br />
      <?php selectAccess2 ('changeName',5) ?>
      <?php echo elgg_echo("livestreaming:changeName_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:offlineMessage"); ?>:</label><br />
      <input name='offlineMessage' value='Channel+Offline' size="70"><br />
      <?php echo elgg_echo("livestreaming:Channel+Offline_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:floodProtection2"); ?>:</label><br />
			<input name='floodProtection2' value="<?php if (datalist_get('lstr_floodProtection2')) echo datalist_get('lstr_floodProtection2'); else echo '3'; ?>" size="10"><br />
			<?php echo elgg_echo("livestreaming:floodProtection2_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:writeText2"); ?>:</label><br />
      <?php selectAccess ('writeText2',0) ?>
      <?php echo elgg_echo("livestreaming:writeText2_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:disableVideo"); ?>:</label><br />
      <?php selectAccess ('disableVideo',0) ?>
      <?php echo elgg_echo("livestreaming:disableVideo_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:disableChat"); ?>:</label><br />
      <?php selectAccess ('disableChat',0) ?>
      <?php echo elgg_echo("livestreaming:disableChat_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:disableUsers"); ?>:</label><br />
      <?php selectAccess ('disableUsers',0) ?>
      <?php echo elgg_echo("livestreaming:disableUsers_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:privateTextchat2"); ?>:</label><br />
      <?php selectAccess ('privateTextchat2',0) ?>
      <?php echo elgg_echo("livestreaming:privateTextchat2_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:verboseLevel2"); ?>:</label><br />
      <select name="verboseLevel2"><option selected="selected" value="2">Warning</option><option value="0">Nothing</option><option value="1">Failure</option><option value="3">Success</option><option value="4">Action</option></select> <br />
      <?php echo elgg_echo("livestreaming:verboseLevel2_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:layoutCode2"); ?>:</label><br />
      <textarea name="layoutCode2" cols="32" rows="3"></textarea> <br />
      <?php echo elgg_echo("livestreaming:layoutCode2_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:fillWindow2"); ?>:</label><br />
      <select name="fillWindow2"><option selected="selected" value="0">no</option><option value="1">yes</option></select><br />
      <?php echo elgg_echo("livestreaming:fillWindow2_descr"); ?><br /><br />


      <input type="hidden" name="method" value="site" />
<?php
echo elgg_view('input/submit', array(
	'value' => elgg_echo('create'),
	'name' => 'save',
));
?>
    	<p><br />
			<label>
				<?php echo elgg_echo('access'); ?><br />
          <?php if ($ver[1]>7) echo elgg_view('input/access', array('name' => 'access_id','value' => $access_id));  
          else echo elgg_view('input/access', array('internalname' => 'access_id','value' => $access_id)); ?>
			</label>
  		</p>
	</form>
</div>
