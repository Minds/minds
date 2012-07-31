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
			
<div class="videoconference_new">

	<form action="<?php echo elgg_add_action_tokens_to_url($vars['url']."action/videoconference/create"); ?>" method="post" name="roomForm">
			<label><?php echo elgg_echo("videoconference:name"); ?>:</label><br />

			<input type="text" id="room" maxlength="22" size="22" name='room' value='<?php if (get_input ('roomname')) echo $roomnm; else echo $generated ?>' onKeyDown="textCounter(document.roomForm.room,document.roomForm.remLen1,22); censorName()" onKeyUp="textCounter(document.roomForm.room,document.roomForm.remLen1,22); censorName()" onChange="censorName()" >
			 <div  style='float:right'><input readonly type="text" name="remLen1" size="3" maxlength="3" value="22"></div>
			
			<?php echo $msg ?><br />
      <?php echo elgg_echo("videoconference:name_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:description"); ?>:</label><br />
      <textarea name="description" cols="32" rows="3"></textarea> <br />
      <?php echo elgg_echo("videoconference:description_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:welcome"); ?>:</label><br />
      <textarea name="welcome" cols="32" rows="3">Welcome to videoconference room.</textarea><br />
			<?php echo elgg_echo("videoconference:welcome_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:resolution"); ?>:</label><br />
      <select name="resolution"><option selected="selected" value="320x240">320x240</option><option value="160x120">160x120</option><option value="176x144">176x144</option><option value="352x288">352x288</option><option value="640x480">640x480</option></select><br />
			<?php echo elgg_echo("videoconference:resolution_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:camfps"); ?>:</label><br />
      <select name="camfps"><option selected="selected" value="15">15</option><option value="10">10</option><option value="12">12</option><option value="20">20</option><option value="25">25</option><option value="30">30</option></select> <br />
			<?php echo elgg_echo("videoconference:camfps_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:micrate"); ?>:</label><br />
      <select name="micrate"><option selected="selected" value="11">11</option><option value="22">22</option><option value="44">44</option><option value="48">48</option></select> <br />
      <?php echo elgg_echo("videoconference:micrate_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:camBandwidth"); ?>:</label><br />
      <input name='camBandwidth' value='40960' size="10"><br />
      <?php echo elgg_echo("videoconference:camBandwidth_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:background_url"); ?>:</label><br />
			<input name='background_url' value='' size="70"> <br />
			<?php echo elgg_echo("videoconference:background_url_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:layoutCode"); ?>:</label><br />
      <textarea name="layoutCode" cols="32" rows="3"></textarea> <br />
      <?php echo elgg_echo("videoconference:layoutCode_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:fillWindow"); ?>:</label><br />
      <select name="fillWindow"><option selected="selected" value="0">no</option><option value="1">yes</option></select><br />
      <?php echo elgg_echo("videoconference:fillWindow_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:floodProtection"); ?>:</label><br />
			<input name='floodProtection' value="<?php if (datalist_get('vconf_floodProtection')) echo datalist_get('vconf_floodProtection'); else echo '3'; ?>" size="10"><br />
			<?php echo elgg_echo("videoconference:floodProtection_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:filterRegex"); ?>:</label><br />
			<input name='filterRegex' value='(?i)(fuck|cunt)(?-i)' size="70"> <br />
			<?php echo elgg_echo("videoconference:filterRegex_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:filterReplace"); ?>:</label><br />
			<input name='filterReplace' value=' ** ' maxlength="22" size="22"> <br />
			<?php echo elgg_echo("videoconference:filterReplace_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:showCamSettings"); ?>:</label><br />
      <?php selectAccess ('showCamSettings',0) ?>
      <?php echo elgg_echo("videoconference:showCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:advancedCamSettings"); ?>:</label><br />
      <?php selectAccess ('advancedCamSettings',0) ?>
      <?php echo elgg_echo("videoconference:advancedCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:configureSource"); ?>:</label><br />
      <?php selectAccess ('configureSource',0) ?>
      <?php echo elgg_echo("videoconference:configureSource_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:disableVideo"); ?>:</label><br />
      <?php selectAccess ('disableVideo',0) ?>
      <?php echo elgg_echo("videoconference:disableVideo_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:disableSound"); ?>:</label><br />
      <?php selectAccess ('disableSound',0) ?>
      <?php echo elgg_echo("videoconference:disableSound_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:panelFiles"); ?>:</label><br />
      <?php selectAccess ('panelFiles',0) ?>
      <?php echo elgg_echo("videoconference:panelFiles_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:file_upload"); ?>:</label><br />
      <?php selectAccess ('file_upload',0) ?>
      <?php echo elgg_echo("videoconference:file_upload_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:file_delete"); ?>:</label><br />
      <?php selectAccess ('file_delete',2) ?>
      <?php echo elgg_echo("videoconference:file_delete_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:tutorial"); ?>:</label><br />
      <?php selectAccess ('tutorial',0) ?>
			<?php echo elgg_echo("videoconference:tutorial_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:autoViewCams"); ?>:</label><br />
      <?php selectAccess ('autoViewCams',0) ?>
			<?php echo elgg_echo("videoconference:autoViewCams_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:showTimer"); ?>:</label><br />
      <?php selectAccess ('showTimer',0) ?>
      <?php echo elgg_echo("videoconference:showTimer_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:writeText"); ?>:</label><br />
      <?php selectAccess ('writeText',0) ?>
      <?php echo elgg_echo("videoconference:writeText_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:regularWatch"); ?>:</label><br />
      <?php selectAccess ('regularWatch',0) ?>
      <?php echo elgg_echo("videoconference:regularWatch_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:newWatch"); ?>:</label><br />
      <?php selectAccess ('newWatch',0) ?>
      <?php echo elgg_echo("videoconference:newWatch_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:privateTextchat"); ?>:</label><br />
      <?php selectAccess ('privateTextchat',0) ?>
      <?php echo elgg_echo("videoconference:privateTextchat_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:panelRooms"); ?>:</label><br />
      <?php selectAccess ('panelRooms',0) ?>
      <?php echo elgg_echo("videoconference:panelRooms_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:panelUsers"); ?>:</label><br />
      <?php selectAccess ('panelUsers',0) ?>
      <?php echo elgg_echo("videoconference:panelUsers_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:verboseLevel"); ?>:</label><br />
      <select name="verboseLevel"><option selected="selected" value="2">Warning</option><option value="0">Nothing</option><option value="1">Failure</option><option value="3">Success</option><option value="4">Action</option></select> <br />
      <?php echo elgg_echo("videoconference:verboseLevel_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:userList"); ?>:</label><br />
      <textarea name="userList" cols="32" rows="3"></textarea> <br />
      <?php echo elgg_echo("videoconference:userList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:moderatorList"); ?>:</label><br />
      <textarea name="moderatorList" cols="32" rows="3"><?php echo $username; ?></textarea> <br />
      <?php echo elgg_echo("videoconference:moderatorList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:admin"); ?>:</label><br />
      <?php selectAccess ('admin',2) ?>
      <?php echo elgg_echo("videoconference:admin_descr"); ?><br /><br />
	  <label><?php echo elgg_echo("videoconference:visitor"); ?>:</label><br />
      <select name="visitor">
      <?php if (datalist_get('vconf_visitor')==1) echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
        elseif (datalist_get('vconf_visitor')==0) echo "<option selected=\"selected\" value=\"0\">no</option><option value=\"1\">yes</option></select><br />";
        else echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
      ?>
      <?php echo elgg_echo("videoconference:visitor_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:cleanUp"); ?>:</label><br />
      <input name='cleanUp' value='0' size="10"><br />
      <?php echo elgg_echo("videoconference:cleanUp_descr"); ?><br /><br />
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
