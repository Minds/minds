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
			
<div class="videoconsultation_new">

			
	<form action="<?php echo elgg_add_action_tokens_to_url($vars['url']."action/videoconsultation/create"); ?>" method="post" name="roomForm">
			<label><?php echo elgg_echo("videoconsultation:name"); ?>:</label><br />
			
			<input type="text" id="room" maxlength="22" size="22" name='room' value='<?php if (get_input ('roomname')) echo $roomnm; else echo $generated ?>' onKeyDown="textCounter(document.roomForm.room,document.roomForm.remLen1,22); censorName()" onKeyUp="textCounter(document.roomForm.room,document.roomForm.remLen1,22); censorName()" onChange="censorName()" >
			 <div  style='float:right'><input readonly type="text" name="remLen1" size="3" maxlength="3" value="22"></div>
			 
			<?php echo $msg ?><br />
      <?php echo elgg_echo("videoconsultation:name_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:description"); ?>:</label><br />
      <textarea name="description" cols="32" rows="3"></textarea> <br />
      <?php echo elgg_echo("videoconsultation:description_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:welcome"); ?>:</label><br />
      <textarea name="welcome" cols="32" rows="3">Welcome to videoconsultation room.</textarea><br />
			<?php echo elgg_echo("videoconsultation:welcome_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:resolution"); ?>:</label><br />
      <select name="resolution"><option selected="selected" value="320x240">320x240</option><option value="160x120">160x120</option><option value="176x144">176x144</option><option value="352x288">352x288</option><option value="640x480">640x480</option></select><br />
			<?php echo elgg_echo("videoconsultation:resolution_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:camfps"); ?>:</label><br />
      <select name="camfps"><option selected="selected" value="15">15</option><option value="10">10</option><option value="12">12</option><option value="20">20</option><option value="25">25</option><option value="30">30</option></select> <br />
			<?php echo elgg_echo("videoconsultation:camfps_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:micrate"); ?>:</label><br />
      <select name="micrate"><option selected="selected" value="11">11</option><option value="22">22</option><option value="44">44</option><option value="48">48</option></select> <br />
      <?php echo elgg_echo("videoconsultation:micrate_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:camBandwidth"); ?>:</label><br />
      <input name='camBandwidth' value='40960' size="10"><br />
      <?php echo elgg_echo("videoconsultation:camBandwidth_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:background_url"); ?>:</label><br />
			<input name='background_url' value='' size="70"> <br />
			<?php echo elgg_echo("videoconsultation:background_url_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:change_background"); ?>:</label><br />
      <?php selectAccess ('change_background',2) ?>
      <?php echo elgg_echo("videoconsultation:change_background_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:room_limit"); ?>:</label><br />
			<input name='room_limit' value="30" size="10"><br />
			<?php echo elgg_echo("videoconsultation:room_limit_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:regularCams"); ?>:</label><br />
      <?php selectAccess ('regularCams',0) ?>
      <?php echo elgg_echo("videoconsultation:regularCams_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:regularWatch"); ?>:</label><br />
      <?php selectAccess ('regularWatch',0) ?>
      <?php echo elgg_echo("videoconsultation:regularWatch_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:layoutCode"); ?>:</label><br />
      <textarea name="layoutCode" cols="32" rows="3"></textarea> <br />
      <?php echo elgg_echo("videoconsultation:layoutCode_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:fillWindow"); ?>:</label><br />
      <select name="fillWindow"><option selected="selected" value="0">no</option><option value="1">yes</option></select><br />
      <?php echo elgg_echo("videoconsultation:fillWindow_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:floodProtection"); ?>:</label><br />
			<input name='floodProtection' value="<?php if (datalist_get('vcons_floodProtection')) echo datalist_get('vcons_floodProtection'); else echo '3'; ?>" size="10"><br />
			<?php echo elgg_echo("videoconsultation:floodProtection_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:filterRegex"); ?>:</label><br />
			<input name='filterRegex' value='(?i)(fuck|cunt)(?-i)' size="70"> <br />
			<?php echo elgg_echo("videoconsultation:filterRegex_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:filterReplace"); ?>:</label><br />
			<input name='filterReplace' value=' ** ' maxlength="22" size="22"> <br />
			<?php echo elgg_echo("videoconsultation:filterReplace_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:showCamSettings"); ?>:</label><br />
      <?php selectAccess ('showCamSettings',0) ?>
      <?php echo elgg_echo("videoconsultation:showCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:advancedCamSettings"); ?>:</label><br />
      <?php selectAccess ('advancedCamSettings',0) ?>
      <?php echo elgg_echo("videoconsultation:advancedCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:configureSource"); ?>:</label><br />
      <?php selectAccess ('configureSource',0) ?>
      <?php echo elgg_echo("videoconsultation:configureSource_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:disableVideo"); ?>:</label><br />
      <?php selectAccess ('disableVideo',0) ?>
      <?php echo elgg_echo("videoconsultation:disableVideo_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:disableSound"); ?>:</label><br />
      <?php selectAccess ('disableSound',0) ?>
      <?php echo elgg_echo("videoconsultation:disableSound_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:files_enabled"); ?>:</label><br />
      <?php selectAccess ('files_enabled',0) ?>
      <?php echo elgg_echo("videoconsultation:files_enabled_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:file_upload"); ?>:</label><br />
      <?php selectAccess ('file_upload',0) ?>
      <?php echo elgg_echo("videoconsultation:file_upload_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:file_delete"); ?>:</label><br />
      <?php selectAccess ('file_delete',2) ?>
      <?php echo elgg_echo("videoconsultation:file_delete_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:chat_enabled"); ?>:</label><br />
      <?php selectAccess ('chat_enabled',0) ?>
      <?php echo elgg_echo("videoconsultation:chat_enabled_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:showTimer"); ?>:</label><br />
      <?php selectAccess ('showTimer',0) ?>
      <?php echo elgg_echo("videoconsultation:showTimer_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:writeText"); ?>:</label><br />
      <?php selectAccess ('writeText',0) ?>
      <?php echo elgg_echo("videoconsultation:writeText_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:privateTextchat"); ?>:</label><br />
      <?php selectAccess ('privateTextchat',0) ?>
      <?php echo elgg_echo("videoconsultation:privateTextchat_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:externalStream"); ?>:</label><br />
      <?php selectAccess ('externalStream',2) ?>
      <?php echo elgg_echo("videoconsultation:externalStream_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:slideShow"); ?>:</label><br />
      <?php selectAccess ('slideShow',2) ?>
      <?php echo elgg_echo("videoconsultation:slideShow_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:users_enabled"); ?>:</label><br />
      <?php selectAccess ('users_enabled',0) ?>
      <?php echo elgg_echo("videoconsultation:users_enabled_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosN"); ?>:</label><br />
			<input name='publicVideosN' value="3" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosN_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:publicVideosAdd"); ?>:</label><br />
      <?php selectAccess ('publicVideosAdd',0) ?>
      <?php echo elgg_echo("videoconsultation:publicVideosAdd_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:publicVideosMax"); ?>:</label><br />
			<input name='publicVideosMax' value="8" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosMax_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosW"); ?>:</label><br />
			<input name='publicVideosW' value="165" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosW_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosH"); ?>:</label><br />
			<input name='publicVideosH' value="178" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosH_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosX"); ?>:</label><br />
			<input name='publicVideosX' value="300" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosX_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosY"); ?>:</label><br />
			<input name='publicVideosY' value="100" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosY_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosColumns"); ?>:</label><br />
			<input name='publicVideosColumns' value="2" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosColumns_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosRows"); ?>:</label><br />
			<input name='publicVideosRows' value="0" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosRows_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:autoplayServer"); ?>:</label><br />
			<input name='autoplayServer' value='' size="70"> <br />
			<?php echo elgg_echo("videoconsultation:autoplayServer_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:autoplayStream"); ?>:</label><br />
			<input name='autoplayStream' value='' size="70"> <br />
			<?php echo elgg_echo("videoconsultation:autoplayStream_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:verboseLevel"); ?>:</label><br />
      <select name="verboseLevel"><option selected="selected" value="2">Warning</option><option value="0">Nothing</option><option value="1">Failure</option><option value="3">Success</option><option value="4">Action</option></select> <br />
      <?php echo elgg_echo("videoconsultation:verboseLevel_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:userList"); ?>:</label><br />
      <textarea name="userList" cols="32" rows="3"></textarea> <br />
      <?php echo elgg_echo("videoconsultation:userList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:moderatorList"); ?>:</label><br />
      <textarea name="moderatorList" cols="32" rows="3"><?php echo $username; ?></textarea> <br />
      <?php echo elgg_echo("videoconsultation:moderatorList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:admin"); ?>:</label><br />
      <?php selectAccess ('admin',2) ?>
      <?php echo elgg_echo("videoconsultation:admin_descr"); ?><br /><br />
	  <label><?php echo elgg_echo("videoconsultation:visitor"); ?>:</label><br />
      <select name="visitor">
      <?php if (datalist_get('vcons_visitor')==1) echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
        elseif (datalist_get('vcons_visitor')==0) echo "<option selected=\"selected\" value=\"0\">no</option><option value=\"1\">yes</option></select><br />";
        else echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
      ?>
      <?php echo elgg_echo("videoconsultation:visitor_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:changeName"); ?>:</label><br />
      <?php selectAccess2 ('changeName',5) ?>
      <?php echo elgg_echo("videoconsultation:changeName_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:cleanUp"); ?>:</label><br />
      <input name='cleanUp' value='0' size="10"><br />
      <?php echo elgg_echo("videoconsultation:cleanUp_descr"); ?><br /><br />
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
