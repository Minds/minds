<?php

$guid = elgg_extract('guid', $vars, null);

$title = elgg_extract('title', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$descriptionx = elgg_extract('description', $vars, '');
$data_arr = explode ('^', $descriptionx);
$description = $data_arr[0];
$welcome = $data_arr[1]; 
$cam[0] = $data_arr[2];
$cam[1] = $data_arr[3];
$room_limit = $data_arr[4]; 
$showTimer = $data_arr[5]; 
$camfps = $data_arr[6];
$micrate = $data_arr[7]; 
$camBandwidth = $data_arr[8]; 
$showCamSettings = $data_arr[9]; 
$advancedCamSettings = $data_arr[10]; 
$configureSource = $data_arr[11]; 
$onlyVideo = $data_arr[12]; 
$noVideo = $data_arr[13]; 
$noEmbeds = $data_arr[14]; 
$labelColor = $data_arr[15]; 
$writeText = $data_arr[16]; 
$floodProtection = $data_arr[17]; 
$welcome2 = $data_arr[18];
$offlineMessage = $data_arr[19];
$floodProtection2 = $data_arr[20];
$filterRegex = $data_arr[21];
$filterReplace = $data_arr[22];
$layoutCode = $data_arr[23];
$fillWindow = $data_arr[24];
$writeText2 = $data_arr[25];
$disableVideo = $data_arr[26];
$disableChat = $data_arr[27];
$disableUsers = $data_arr[28];
$visitor = $data_arr[29];
$userList = $data_arr[30]; 
$moderatorList = $data_arr[31]; 
$cleanUp = $data_arr[32];  
$changeName = $data_arr[33];
$ztime = $data_arr[34];
$layoutCode2 = $data_arr[35];
$fillWindow2 = $data_arr[36];
$verboseLevel = $data_arr[37];
$verboseLevel2 = $data_arr[38];
$privateTextchat = $data_arr[39];
$privateTextchat2 = $data_arr[40];
$soundQuality = $data_arr[41];
$overLogo = $data_arr[42];
$overLink = $data_arr[43];

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

<div class="livestreaming_new">

	<form action="<?php echo elgg_add_action_tokens_to_url($vars['url']."action/livestreaming/create"); ?>" method="post" name="roomForm">
			<label><?php echo elgg_echo("livestreaming:name"); ?>:</label><br />
			<input name='room' value='<?php echo $title; ?>' maxlength="22" size="22"> <br />
      <?php echo elgg_echo("livestreaming:name_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:description"); ?>:</label><br />
      <textarea name="description" cols="32" rows="3"><?php echo $description; ?></textarea><br />
      <?php echo elgg_echo("livestreaming:description_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:room_limit"); ?>:</label><br />
			<input name='room_limit' value="<?php echo $room_limit; ?>" size="10"><br />
			<?php echo elgg_echo("livestreaming:room_limit_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:visitor"); ?>:</label><br />
      <select name="visitor">
      <?php if ($visitor==1) echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
        elseif ($visitor==0) echo "<option selected=\"selected\" value=\"0\">no</option><option value=\"1\">yes</option></select><br />";
        else echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
      ?>
      <?php echo elgg_echo("livestreaming:visitor_descr"); ?><br /><br />
	  <label><?php echo elgg_echo("livestreaming:showCamSettings"); ?>:</label><br />
      <?php selectAccess ('showCamSettings', $showCamSettings ) ?>
      <?php echo elgg_echo("livestreaming:showCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:advancedCamSettings"); ?>:</label><br />
      <?php selectAccess ('advancedCamSettings', $advancedCamSettings ) ?>
      <?php echo elgg_echo("livestreaming:advancedCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:configureSource"); ?>:</label><br />
      <?php selectAccess ('configureSource', $configureSource ) ?>
      <?php echo elgg_echo("livestreaming:configureSource_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:overLogo"); ?>:</label><br />
			<input name='overLogo' value="<?php echo $overLogo; ?>" size="10"><br />
			<?php echo elgg_echo("livestreaming:overLogo_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:overLink"); ?>:</label><br />
      <textarea name="overLink" cols="32" rows="3"><?php echo $overLink; ?></textarea> <br />
      <?php echo elgg_echo("livestreaming:overLink_descr"); ?><br /><br />
			
      <label><?php echo elgg_echo("livestreaming:userList"); ?>:</label><br />
      <textarea name="userList" cols="32" rows="3"><?php echo $userList; ?></textarea> <br />
      <?php echo elgg_echo("livestreaming:userList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:moderatorList"); ?>:</label><br />
      <textarea name="moderatorList" cols="32" rows="3"><?php echo $moderatorList; ?></textarea> <br />
      <?php echo elgg_echo("livestreaming:moderatorList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:cleanUp"); ?>:</label><br />
      <input name='cleanUp' value='<?php echo $cleanUp; ?>' size="10"><br />
      <?php echo elgg_echo("livestreaming:cleanUp_descr"); ?><br /><br />

<h3 class="settings"><?php echo elgg_echo('livestreaming:broadcasting'); ?></h3>
      <label><?php echo elgg_echo("livestreaming:welcome"); ?>:</label><br />
      <textarea name="welcome" cols="32" rows="3"><?php echo $welcome; ?></textarea><br />
			<?php echo elgg_echo("livestreaming:welcome_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:onlyVideo"); ?>:</label><br />
      <?php selectAccess ('onlyVideo', $onlyVideo ) ?>
      <?php echo elgg_echo("livestreaming:onlyVideo_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:noVideo"); ?>:</label><br />
      <?php selectAccess ('noVideo', $noVideo ) ?>
      <?php echo elgg_echo("livestreaming:noVideo_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:noEmbeds"); ?>:</label><br />
      <?php selectAccess ('noEmbeds', $noEmbeds ) ?>
      <?php echo elgg_echo("livestreaming:noEmbeds_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:showTimer"); ?>:</label><br />
      <?php selectAccess ('showTimer', $showTimer ) ?>
      <?php echo elgg_echo("livestreaming:showTimer_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:writeText"); ?>:</label><br />
      <?php selectAccess ('writeText', $writeText ) ?>
      <?php echo elgg_echo("livestreaming:writeText_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:resolution"); ?>:</label><br />
      <select name="resolution"><option selected="selected" value="<?php echo $cam[0]."x".$cam[1]; ?>"><?php echo $cam[0]."x".$cam[1]; ?></option><option value="320x240">320x240</option><option value="160x120">160x120</option><option value="176x144">176x144</option><option value="352x288">352x288</option><option value="640x480">640x480</option></select><br />
			<?php echo elgg_echo("livestreaming:resolution_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:camfps"); ?>:</label><br />
      <select name="camfps"><option selected="selected" value="<?php echo $camfps; ?>"><?php echo $camfps; ?></option><option value="15">15</option><option value="10">10</option><option value="12">12</option><option value="20">20</option><option value="25">25</option><option value="30">30</option></select> <br />
			<?php echo elgg_echo("livestreaming:camfps_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:micrate"); ?>:</label><br />
      <select name="micrate"><option selected="selected" value="<?php echo $micrate; ?>"><?php echo $micrate; ?></option><option value="11">11</option><option value="22">22</option><option value="44">44</option><option value="48">48</option></select> <br />
      <?php echo elgg_echo("livestreaming:micrate_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:camBandwidth"); ?>:</label><br />
      <input name='camBandwidth' value='<?php echo $camBandwidth; ?>' size="10"><br />
      <?php echo elgg_echo("livestreaming:camBandwidth_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:floodProtection"); ?>:</label><br />
			<input name='floodProtection' value="<?php echo $floodProtection; ?>" size="10"><br />
			<?php echo elgg_echo("livestreaming:floodProtection_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:labelColor"); ?>:</label><br />
      <input name='labelColor' value='<?php echo $labelColor; ?>' size="10"><br />
      <?php echo elgg_echo("livestreaming:labelColor_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:privateTextchat"); ?>:</label><br />
      <?php selectAccess ('privateTextchat', $privateTextchat ) ?>
      <?php echo elgg_echo("livestreaming:privateTextchat_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:soundQuality"); ?>:</label><br />
      <input name='soundQuality' value='<?php echo $soundQuality; ?>' size="10"><br />
      <?php echo elgg_echo("livestreaming:soundQuality_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:verboseLevel"); ?>:</label><br />
      <select name="verboseLevel"><option selected="selected" value="<?php echo $verboseLevel; ?>"><?php if ($verboseLevel == 0) echo "Nothing"; elseif ($verboseLevel == 1) echo "Failure"; elseif ($verboseLevel == 2) echo "Warning"; elseif ($verboseLevel == 3) echo "Success"; else echo "Action"; ?></option><option value="0">Nothing</option><option value="1">Failure</option><option value="2">Warning</option><option value="3">Success</option><option value="4">Action</option></select> <br />
      <?php echo elgg_echo("livestreaming:verboseLevel_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:layoutCode"); ?>:</label><br />
      <textarea name="layoutCode" cols="32" rows="3"><?php echo $layoutCode; ?></textarea> <br />
      <?php echo elgg_echo("livestreaming:layoutCode_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:fillWindow"); ?>:</label><br />
      <select name="fillWindow"><option selected="selected" value="<?php echo $fillWindow; ?>"><?php if ($fillWindow == 0) echo "no"; else echo "yes"; ?></option><option value="0">no</option><option value="1">yes</option></select><br />
      <?php echo elgg_echo("livestreaming:fillWindow_descr"); ?><br /><br />

 
<h3 class="settings"><?php echo elgg_echo('livestreaming:watchvideo'); ?></h3>
      <label><?php echo elgg_echo("livestreaming:welcome2"); ?>:</label><br />
      <textarea name="welcome2" cols="32" rows="3"><?php echo $welcome2; ?></textarea><br />
			<?php echo elgg_echo("livestreaming:welcome2_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:changeName"); ?>:</label><br />
      <?php selectAccess2 ('changeName', $changeName ) ?>
      <?php echo elgg_echo("livestreaming:changeName_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:offlineMessage"); ?>:</label><br />
      <input name='offlineMessage' value='<?php echo $offlineMessage; ?>' size="70"><br />
      <?php echo elgg_echo("livestreaming:Channel+Offline_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:floodProtection2"); ?>:</label><br />
			<input name='floodProtection2' value="<?php echo $floodProtection2; ?>" size="10"><br />
			<?php echo elgg_echo("livestreaming:floodProtection2_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:writeText2"); ?>:</label><br />
      <?php selectAccess ('writeText2', $writeText2 ) ?>
      <?php echo elgg_echo("livestreaming:writeText2_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:disableVideo"); ?>:</label><br />
      <?php selectAccess ('disableVideo', $disableVideo ) ?>
      <?php echo elgg_echo("livestreaming:disableVideo_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:disableChat"); ?>:</label><br />
      <?php selectAccess ('disableChat', $disableChat ) ?>
      <?php echo elgg_echo("livestreaming:disableChat_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:disableUsers"); ?>:</label><br />
      <?php selectAccess ('disableUsers', $disableUsers ) ?>
      <?php echo elgg_echo("livestreaming:disableUsers_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:privateTextchat2"); ?>:</label><br />
      <?php selectAccess ('privateTextchat2', $privateTextchat2 ) ?>
      <?php echo elgg_echo("livestreaming:privateTextchat2_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:verboseLevel2"); ?>:</label><br />
      <select name="verboseLevel2"><option selected="selected" value="<?php echo $verboseLevel2; ?>"><?php if ($verboseLevel2 == 0) echo "Nothing"; elseif ($verboseLevel2 == 1) echo "Failure"; elseif ($verboseLevel2 == 2) echo "Warning"; elseif ($verboseLevel2 == 3) echo "Success"; else echo "Action"; ?></option><option value="0">Nothing</option><option value="1">Failure</option><option value="2">Warning</option><option value="3">Success</option><option value="4">Action</option></select> <br />
      <?php echo elgg_echo("livestreaming:verboseLevel2_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:layoutCode2"); ?>:</label><br />
      <textarea name="layoutCode2" cols="32" rows="3"><?php echo $layoutCode2; ?></textarea> <br />
      <?php echo elgg_echo("livestreaming:layoutCode2_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:fillWindow2"); ?>:</label><br />
      <select name="fillWindow2"><option selected="selected" value="<?php echo $fillWindow2; ?>"><?php if ($fillWindow2 == 0) echo "no"; else echo "yes"; ?></option><option value="0">no</option><option value="1">yes</option></select><br />
      <?php echo elgg_echo("livestreaming:fillWindow2_descr"); ?><br /><br />


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
