<?php

$guid = elgg_extract('guid', $vars, null);

$title = elgg_extract('title', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$descriptionx = elgg_extract('description', $vars, '');
$data_arr = explode ('^', $descriptionx);
$description = $data_arr[0];
$welcome = $data_arr[1];
$cam[0] = $data_arr [2];
$cam[1] = $data_arr [3];
$camBandwidth = $data_arr [4]; 
$background_url = $data_arr [5]; 
$layoutCode = $data_arr [6]; 
$fillWindow = $data_arr [7]; 
$filterRegex = $data_arr [8]; 
$filterReplace = $data_arr [9]; 
$camfps = $data_arr [10];
$micrate = $data_arr [11]; 
$showCamSettings = $data_arr [12]; 
$advancedCamSettings = $data_arr [13]; 
$configureSource = $data_arr [14]; 
$disableVideo = $data_arr [15]; 
$disableSound = $data_arr [16]; 
$panelFiles = $data_arr [17]; 
$file_upload = $data_arr [18]; 
$file_delete = $data_arr [19]; 
$tutorial = $data_arr [20]; 
$autoViewCams = $data_arr [21]; 
$showTimer = $data_arr [22]; 
$userList = $data_arr [23]; 
$moderatorList = $data_arr [24]; 
$cleanUp = $data_arr [25];  
$writeText = $data_arr [26]; 
$regularWatch = $data_arr [27]; 
$newWatch = $data_arr [28]; 
$privateTextchat = $data_arr [29]; 
$floodProtection = $data_arr [30]; 
$visitor = $data_arr [31]; 
$admin = $data_arr [32]; 
// $ztime = $data_arr [33]; 
$panelRooms = $data_arr [34]; 
$panelUsers = $data_arr [35]; 
$verboseLevel = $data_arr [36]; 

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

?>

<div class="videoconference_new">

	<form action="<?php echo elgg_add_action_tokens_to_url($vars['url']."action/videoconference/create"); ?>" method="post" name="roomForm">
			<label><?php echo elgg_echo("videoconference:name"); ?>:</label><br />
			<input name='room' value='<?php echo $title; ?>' maxlength="22" size="22"> <br />
      <?php echo elgg_echo("videoconference:name_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:description"); ?>:</label><br />
      <textarea name="description" cols="32" rows="3"><?php echo $description; ?></textarea><br />
      <?php echo elgg_echo("videoconference:description_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:welcome"); ?>:</label><br />
      <textarea name="welcome" cols="32" rows="3"><?php echo $welcome; ?></textarea><br />
			<?php echo elgg_echo("videoconference:welcome_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:resolution"); ?>:</label><br />
      <select name="resolution"><option selected="selected" value="<?php echo $cam[0]."x".$cam[1]; ?>"><?php echo $cam[0]."x".$cam[1]; ?></option><option value="320x240">320x240</option><option value="160x120">160x120</option><option value="176x144">176x144</option><option value="352x288">352x288</option><option value="640x480">640x480</option></select><br />
			<?php echo elgg_echo("videoconference:resolution_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:camfps"); ?>:</label><br />
      <select name="camfps"><option selected="selected" value="<?php echo $camfps; ?>"><?php echo $camfps; ?></option><option value="15">15</option><option value="10">10</option><option value="12">12</option><option value="20">20</option><option value="25">25</option><option value="30">30</option></select> <br />
			<?php echo elgg_echo("videoconference:camfps_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:micrate"); ?>:</label><br />
      <select name="micrate"><option selected="selected" value="<?php echo $micrate; ?>"><?php echo $micrate; ?></option><option value="11">11</option><option value="22">22</option><option value="44">44</option><option value="48">48</option></select> <br />
      <?php echo elgg_echo("videoconference:micrate_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:camBandwidth"); ?>:</label><br />
      <input name='camBandwidth' value='<?php echo $camBandwidth; ?>' size="10"><br />
      <?php echo elgg_echo("videoconference:camBandwidth_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:background_url"); ?>:</label><br />
			<input name='background_url' value='<?php echo $background_url; ?>' size="70"> <br />
			<?php echo elgg_echo("videoconference:background_url_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:layoutCode"); ?>:</label><br />
      <textarea name="layoutCode" cols="32" rows="3"><?php echo $layoutCode; ?></textarea> <br />
      <?php echo elgg_echo("videoconference:layoutCode_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:fillWindow"); ?>:</label><br />
      <select name="fillWindow"><option selected="selected" value="<?php echo $fillWindow; ?>"><?php if ($fillWindow == 0) echo "no"; else echo "yes"; ?></option><option value="0">no</option><option value="1">yes</option></select><br />
      <?php echo elgg_echo("videoconference:fillWindow_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:floodProtection"); ?>:</label><br />
			<input name='floodProtection' value="<?php echo $floodProtection; ?>" size="10"><br />
			<?php echo elgg_echo("videoconference:floodProtection_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:filterRegex"); ?>:</label><br />
			<input name='filterRegex' value='<?php echo $filterRegex; ?>' size="70"> <br />
			<?php echo elgg_echo("videoconference:filterRegex_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:filterReplace"); ?>:</label><br />
			<input name='filterReplace' value='<?php echo $filterReplace; ?>' maxlength="22" size="22"> <br />
			<?php echo elgg_echo("videoconference:filterReplace_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:showCamSettings"); ?>:</label><br />
      <?php selectAccess ('showCamSettings',$showCamSettings) ?>
      <?php echo elgg_echo("videoconference:showCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:advancedCamSettings"); ?>:</label><br />
      <?php selectAccess ('advancedCamSettings',$advancedCamSettings) ?>
      <?php echo elgg_echo("videoconference:advancedCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:configureSource"); ?>:</label><br />
      <?php selectAccess ('configureSource',$configureSource) ?>
      <?php echo elgg_echo("videoconference:configureSource_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:disableVideo"); ?>:</label><br />
      <?php selectAccess ('disableVideo',$disableVideo) ?>
      <?php echo elgg_echo("videoconference:disableVideo_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:disableSound"); ?>:</label><br />
      <?php selectAccess ('disableSound',$disableSound) ?>
      <?php echo elgg_echo("videoconference:disableSound_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:panelFiles"); ?>:</label><br />
      <?php selectAccess ('panelFiles',$panelFiles) ?>
      <?php echo elgg_echo("videoconference:panelFiles_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:file_upload"); ?>:</label><br />
      <?php selectAccess ('file_upload',$file_upload) ?>
      <?php echo elgg_echo("videoconference:file_upload_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:file_delete"); ?>:</label><br />
      <?php selectAccess ('file_delete',$file_delete) ?>
      <?php echo elgg_echo("videoconference:file_delete_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconference:tutorial"); ?>:</label><br />
      <?php selectAccess ('tutorial',$tutorial) ?>
			<?php echo elgg_echo("videoconference:tutorial_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:autoViewCams"); ?>:</label><br />
      <?php selectAccess ('autoViewCams',$autoViewCams) ?>
			<?php echo elgg_echo("videoconference:autoViewCams_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:showTimer"); ?>:</label><br />
      <?php selectAccess ('showTimer',$showTimer) ?>
      <?php echo elgg_echo("videoconference:showTimer_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:writeText"); ?>:</label><br />
      <?php selectAccess ('writeText',$writeText) ?>
      <?php echo elgg_echo("videoconference:writeText_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:regularWatch"); ?>:</label><br />
      <?php selectAccess ('regularWatch',$regularWatch) ?>
      <?php echo elgg_echo("videoconference:regularWatch_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:newWatch"); ?>:</label><br />
      <?php selectAccess ('newWatch',$newWatch) ?>
      <?php echo elgg_echo("videoconference:newWatch_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:privateTextchat"); ?>:</label><br />
      <?php selectAccess ('privateTextchat',$privateTextchat) ?>
      <?php echo elgg_echo("videoconference:privateTextchat_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:panelRooms"); ?>:</label><br />
      <?php selectAccess ('panelRooms',$panelRooms) ?>
      <?php echo elgg_echo("videoconference:panelRooms_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:panelUsers"); ?>:</label><br />
      <?php selectAccess ('panelUsers',$panelUsers) ?>
      <?php echo elgg_echo("videoconference:panelUsers_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:verboseLevel"); ?>:</label><br />
      <select name="verboseLevel"><option selected="selected" value="<?php echo $verboseLevel; ?>"><?php if ($verboseLevel == 0) echo "Nothing"; elseif ($verboseLevel == 1) echo "Failure"; elseif ($verboseLevel == 2) echo "Warning"; elseif ($verboseLevel == 3) echo "Success"; else echo "Action"; ?></option><option value="0">Nothing</option><option value="1">Failure</option><option value="2">Warning</option><option value="3">Success</option><option value="4">Action</option></select> <br />
      <?php echo elgg_echo("videoconference:verboseLevel_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:userList"); ?>:</label><br />
      <textarea name="userList" cols="32" rows="3"><?php echo $userList; ?></textarea> <br />
      <?php echo elgg_echo("videoconference:userList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:moderatorList"); ?>:</label><br />
      <textarea name="moderatorList" cols="32" rows="3"><?php echo $moderatorList; ?></textarea> <br />
      <?php echo elgg_echo("videoconference:moderatorList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:admin"); ?>:</label><br />
      <?php selectAccess ('admin',$admin) ?>
      <?php echo elgg_echo("videoconference:admin_descr"); ?><br /><br />
	  <label><?php echo elgg_echo("videoconference:visitor"); ?>:</label><br />
      <select name="visitor">
      <?php if ($visitor==1) echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
        elseif ($visitor==0) echo "<option selected=\"selected\" value=\"0\">no</option><option value=\"1\">yes</option></select><br />";
        else echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
      ?>
      <?php echo elgg_echo("videoconference:visitor_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:cleanUp"); ?>:</label><br />
      <input name='cleanUp' value='<?php echo $cleanUp; ?>' size="10"><br />
      <?php echo elgg_echo("videoconference:cleanUp_descr"); ?><br /><br />
      <input type="hidden" name="method" value="site" />
<?php

if ($guid) {
	echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
}

echo elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'name' => 'save',
));
?>
    	<p><br />
			<label>
				<?php echo elgg_echo('access'); ?><br />
          <?php echo elgg_view('input/access', array('name' => 'access_id','value' => $access_id)); ?>
			</label>
  		</p>
	</form>
</div>
