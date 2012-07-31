<?php

$guid = elgg_extract('guid', $vars, null);

$title = elgg_extract('title', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$descriptionx = elgg_extract('description', $vars, '');
$data_arr = explode ('^', $descriptionx);
$description = $data_arr[0];
$welcome = $data_arr[1];
$visitor = $data_arr[2];
$background_url = $data_arr[3];
$change_background = $data_arr[4];
$room_limit = $data_arr[5];
$showTimer = $data_arr[6];
$regularCams = $data_arr[7];
$regularWatch = $data_arr[8];
$cam[0] = $data_arr[9];
$cam[1] = $data_arr[10];
$camfps = $data_arr[11];
$micrate = $data_arr[12];
$camBandwidth = $data_arr[13];
$showCamSettings = $data_arr[14];
$advancedCamSettings = $data_arr[15];
$configureSource = $data_arr[16];
$disableVideo = $data_arr[17];
$disableSound = $data_arr[18];
$files_enabled = $data_arr[19];
$file_upload = $data_arr[20];
$file_delete = $data_arr[21];
$chat_enabled = $data_arr[22];
$floodProtection = $data_arr[23];
$writeText = $data_arr[24];
$privateTextchat = $data_arr[25]; 
$externalStream = $data_arr[26];
$slideShow = $data_arr[27];
$users_enabled = $data_arr[28];
$publicVideosN = $data_arr[29];
$publicVideosAdd = $data_arr[30];
$publicVideosMax = $data_arr[31];
$publicVideosW = $data_arr[32];
$publicVideosH = $data_arr[33];
$publicVideosX = $data_arr[34]; 
$publicVideosY = $data_arr[35];
$publicVideosColumns = $data_arr[36];
$publicVideosRows = $data_arr[37];
$autoplayServer = $data_arr[38];
$autoplayStream = $data_arr[39];
$layoutCode = $data_arr[40];
$fillWindow = $data_arr[41];
$filterRegex = $data_arr[42];
$filterReplace = $data_arr[43];
$userList = $data_arr[44];
$moderatorList = $data_arr[45];
$cleanUp = $data_arr[46];
$changeName = $data_arr[47];
$admin = $data_arr[48];
// $ztime = $data_arr[49];
$verboseLevel = $data_arr[50];

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

?>

<div class="videoconsultation_new">

	<form action="<?php echo elgg_add_action_tokens_to_url($vars['url']."action/videoconsultation/create"); ?>" method="post" name="roomForm">
			<label><?php echo elgg_echo("videoconsultation:name"); ?>:</label><br />
			<input name='room' value=' <?php echo $title; ?> ' maxlength="22" size="22"> <br />
      <?php echo elgg_echo("videoconsultation:name_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:description"); ?>:</label><br />
      <textarea name="description" cols="32" rows="3"><?php echo $description; ?></textarea> <br />
      <?php echo elgg_echo("videoconsultation:description_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:welcome"); ?>:</label><br />
      <textarea name="welcome" cols="32" rows="3"><?php echo $welcome; ?></textarea><br />
			<?php echo elgg_echo("videoconsultation:welcome_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:resolution"); ?>:</label><br />
      <select name="resolution"><option selected="selected" value="<?php echo $cam[0]."x".$cam[1]; ?>"><?php echo $cam[0]."x".$cam[1]; ?></option><option value="320x240">320x240</option><option value="160x120">160x120</option><option value="176x144">176x144</option><option value="352x288">352x288</option><option value="640x480">640x480</option></select><br />
			<?php echo elgg_echo("videoconsultation:resolution_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:camfps"); ?>:</label><br />
      <select name="camfps"><option selected="selected" value="<?php echo $camfps; ?>"><?php echo $camfps; ?></option><option value="15">15</option><option value="10">10</option><option value="12">12</option><option value="20">20</option><option value="25">25</option><option value="30">30</option></select> <br />
			<?php echo elgg_echo("videoconsultation:camfps_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:micrate"); ?>:</label><br />
      <select name="micrate"><option selected="selected" value="<?php echo $micrate; ?>"><?php echo $micrate; ?></option><option value="11">11</option><option value="22">22</option><option value="44">44</option><option value="48">48</option></select> <br />
      <?php echo elgg_echo("videoconsultation:micrate_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:camBandwidth"); ?>:</label><br />
      <input name='camBandwidth' value='<?php echo $camBandwidth; ?>' size="10"><br />
      <?php echo elgg_echo("videoconsultation:camBandwidth_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:background_url"); ?>:</label><br />
			<input name='background_url' value='<?php echo $background_url; ?>' size="70"> <br />
			<?php echo elgg_echo("videoconsultation:background_url_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:change_background"); ?>:</label><br />
      <?php selectAccess ('change_background',$change_background) ?>
      <?php echo elgg_echo("videoconsultation:change_background_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:room_limit"); ?>:</label><br />
			<input name='room_limit' value="<?php echo $room_limit; ?>" size="10"><br />
			<?php echo elgg_echo("videoconsultation:room_limit_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:regularCams"); ?>:</label><br />
      <?php selectAccess ('regularCams',$regularCams) ?>
      <?php echo elgg_echo("videoconsultation:regularCams_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:regularWatch"); ?>:</label><br />
      <?php selectAccess ('regularWatch',$regularWatch) ?>
      <?php echo elgg_echo("videoconsultation:regularWatch_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:layoutCode"); ?>:</label><br />
      <textarea name="layoutCode" cols="32" rows="3"><?php echo $layoutCode; ?></textarea> <br />
      <?php echo elgg_echo("videoconsultation:layoutCode_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:fillWindow"); ?>:</label><br />
      <select name="fillWindow"><option selected="selected" value="<?php echo $fillWindow; ?>"><?php if ($fillWindow == 0) echo "no"; else echo "yes"; ?></option><option value="0">no</option><option value="1">yes</option></select><br />
      <?php echo elgg_echo("videoconsultation:fillWindow_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:floodProtection"); ?>:</label><br />
			<input name='floodProtection' value="<?php echo $floodProtection; ?>" size="10"><br />
			<?php echo elgg_echo("videoconsultation:floodProtection_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:filterRegex"); ?>:</label><br />
			<input name='filterRegex' value='<?php echo $filterRegex; ?>' size="70"> <br />
			<?php echo elgg_echo("videoconsultation:filterRegex_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:filterReplace"); ?>:</label><br />
			<input name='filterReplace' value='<?php echo $filterReplace; ?>' maxlength="22" size="22"> <br />
			<?php echo elgg_echo("videoconsultation:filterReplace_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:showCamSettings"); ?>:</label><br />
      <?php selectAccess ('showCamSettings',$showCamSettings) ?>
      <?php echo elgg_echo("videoconsultation:showCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:advancedCamSettings"); ?>:</label><br />
      <?php selectAccess ('advancedCamSettings',$advancedCamSettings) ?>
      <?php echo elgg_echo("videoconsultation:advancedCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:configureSource"); ?>:</label><br />
      <?php selectAccess ('configureSource',$configureSource) ?>
      <?php echo elgg_echo("videoconsultation:configureSource_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:disableVideo"); ?>:</label><br />
      <?php selectAccess ('disableVideo',$disableVideo) ?>
      <?php echo elgg_echo("videoconsultation:disableVideo_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:disableSound"); ?>:</label><br />
      <?php selectAccess ('disableSound',$disableSound) ?>
      <?php echo elgg_echo("videoconsultation:disableSound_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:files_enabled"); ?>:</label><br />
      <?php selectAccess ('files_enabled',$files_enabled) ?>
      <?php echo elgg_echo("videoconsultation:files_enabled_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:file_upload"); ?>:</label><br />
      <?php selectAccess ('file_upload',$file_upload) ?>
      <?php echo elgg_echo("videoconsultation:file_upload_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:file_delete"); ?>:</label><br />
      <?php selectAccess ('file_delete',$file_delete) ?>
      <?php echo elgg_echo("videoconsultation:file_delete_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:chat_enabled"); ?>:</label><br />
      <?php selectAccess ('chat_enabled',$chat_enabled) ?>
      <?php echo elgg_echo("videoconsultation:chat_enabled_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:showTimer"); ?>:</label><br />
      <?php selectAccess ('showTimer',$showTimer) ?>
      <?php echo elgg_echo("videoconsultation:showTimer_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:writeText"); ?>:</label><br />
      <?php selectAccess ('writeText',$writeText) ?>
      <?php echo elgg_echo("videoconsultation:writeText_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:privateTextchat"); ?>:</label><br />
      <?php selectAccess ('privateTextchat',$privateTextchat) ?>
      <?php echo elgg_echo("videoconsultation:privateTextchat_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:externalStream"); ?>:</label><br />
      <?php selectAccess ('externalStream',$externalStream) ?>
      <?php echo elgg_echo("videoconsultation:externalStream_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:slideShow"); ?>:</label><br />
      <?php selectAccess ('slideShow',$slideShow) ?>
      <?php echo elgg_echo("videoconsultation:slideShow_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:users_enabled"); ?>:</label><br />
      <?php selectAccess ('users_enabled',$users_enabled) ?>
      <?php echo elgg_echo("videoconsultation:users_enabled_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosN"); ?>:</label><br />
			<input name='publicVideosN' value="<?php echo $publicVideosN; ?>" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosN_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:publicVideosAdd"); ?>:</label><br />
      <?php selectAccess ('publicVideosAdd',$publicVideosAdd) ?>
      <?php echo elgg_echo("videoconsultation:publicVideosAdd_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:publicVideosMax"); ?>:</label><br />
			<input name='publicVideosMax' value="<?php echo $publicVideosMax; ?>" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosMax_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosW"); ?>:</label><br />
			<input name='publicVideosW' value="<?php echo $publicVideosW; ?>" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosW_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosH"); ?>:</label><br />
			<input name='publicVideosH' value="<?php echo $publicVideosH; ?>" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosH_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosX"); ?>:</label><br />
			<input name='publicVideosX' value="<?php echo $publicVideosX; ?>" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosX_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosY"); ?>:</label><br />
			<input name='publicVideosY' value="<?php echo $publicVideosY; ?>" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosY_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosColumns"); ?>:</label><br />
			<input name='publicVideosColumns' value="<?php echo $publicVideosColumns; ?>" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosColumns_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:publicVideosRows"); ?>:</label><br />
			<input name='publicVideosRows' value="<?php echo $publicVideosRows; ?>" size="10"><br />
			<?php echo elgg_echo("videoconsultation:publicVideosRows_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:autoplayServer"); ?>:</label><br />
			<input name='autoplayServer' value='<?php echo $autoplayServer; ?>' size="70"> <br />
			<?php echo elgg_echo("videoconsultation:autoplayServer_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videoconsultation:autoplayStream"); ?>:</label><br />
			<input name='autoplayStream' value='<?php echo $autoplayStream; ?>' size="70"> <br />
			<?php echo elgg_echo("videoconsultation:autoplayStream_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:verboseLevel"); ?>:</label><br />
      <select name="verboseLevel"><option selected="selected" value="<?php echo $verboseLevel; ?>"><?php if ($verboseLevel == 0) echo "Nothing"; elseif ($verboseLevel == 1) echo "Failure"; elseif ($verboseLevel == 2) echo "Warning"; elseif ($verboseLevel == 3) echo "Success"; else echo "Action"; ?></option><option value="0">Nothing</option><option value="1">Failure</option><option value="2">Warning</option><option value="3">Success</option><option value="4">Action</option></select> <br />
      <?php echo elgg_echo("videoconsultation:verboseLevel_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:userList"); ?>:</label><br />
      <textarea name="userList" cols="32" rows="3"><?php echo $userList; ?></textarea> <br />
      <?php echo elgg_echo("videoconsultation:userList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:moderatorList"); ?>:</label><br />
      <textarea name="moderatorList" cols="32" rows="3"><?php echo $moderatorList; ?></textarea> <br />
      <?php echo elgg_echo("videoconsultation:moderatorList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:admin"); ?>:</label><br />
      <?php selectAccess ('admin',$admin) ?>
      <?php echo elgg_echo("videoconsultation:admin_descr"); ?><br /><br />
	  <label><?php echo elgg_echo("videoconsultation:visitor"); ?>:</label><br />
      <select name="visitor">
      <?php if ($visitor==1) echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
        elseif ($visitor==0) echo "<option selected=\"selected\" value=\"0\">no</option><option value=\"1\">yes</option></select><br />";
        else echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
      ?>
      <?php echo elgg_echo("videoconsultation:visitor_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:changeName"); ?>:</label><br />
      <?php selectAccess2 ('changeName',$changeName) ?>
      <?php echo elgg_echo("videoconsultation:changeName_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:cleanUp"); ?>:</label><br />
      <input name='cleanUp' value='<?php echo $cleanUp; ?>' size="10"><br />
      <?php echo elgg_echo("videoconsultation:cleanUp_descr"); ?><br /><br />
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
