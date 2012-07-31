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
$camBandwidth = $data_arr[4];
$filterRegex = $data_arr[5]; 
$filterReplace = $data_arr[6]; 
$camfps = $data_arr[7];
$micrate = $data_arr[8]; 
$showCamSettings = $data_arr[9];
$configureSource = $data_arr[10]; 
$disableVideo = $data_arr[11]; 
$disableSound = $data_arr[12]; 
$showTimer = $data_arr[13]; 
$userList = $data_arr[14]; 
$moderatorList = $data_arr[15]; 
$cleanUp = $data_arr[16];  
$disableEmoticons = $data_arr[17]; 
$showTextChat = $data_arr[18]; 
$sendTextChat = $data_arr[19];
// $enableP2P = $data_arr[20]; 
// $enableServer = $data_arr[21];
$configureConnection = $data_arr[22]; 
$enableNext = $data_arr[23]; 
$enableBuzz = $data_arr[24]; 
$enableSoundFx = $data_arr[25]; 
$requestSnapshot = $data_arr[26]; 
$autoSnapshots = $data_arr[27]; 
$visitor = $data_arr[28]; 
$verboseLevel = $data_arr[29]; 
$ztime = $data_arr[30];
$camPicture = $data_arr[31];
$enableButtonLabels = $data_arr[32];
$enableFullscreen = $data_arr[33];
$enableSwap = $data_arr[34];
$enableLogout = $data_arr[35];
$enableLogo = $data_arr[36];
$enableHeaders = $data_arr[37];
$enableTitles = $data_arr[38];
$videoW = $data_arr[39];
$videoH = $data_arr[40];
$video2W = $data_arr[41];
$video2H = $data_arr[42];
$layoutCode = $data_arr[43];
$chatTextColor = $data_arr[44];

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

<div class="videochat_new">

	<form action="<?php echo elgg_add_action_tokens_to_url($vars['url']."action/videochat/create"); ?>" method="post" name="roomForm">
			<label><?php echo elgg_echo("videochat:name"); ?>:</label><br />
			<input name='room' value='<?php echo $title; ?>' maxlength="22" size="22"> <br />
      <?php echo elgg_echo("videochat:name_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videochat:description"); ?>:</label><br />
      <textarea name="description" cols="32" rows="3"><?php echo $description; ?></textarea> <br />
      <?php echo elgg_echo("videochat:description_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:welcome"); ?>:</label><br />
      <textarea name="welcome" cols="32" rows="3"><?php echo $welcome; ?></textarea><br />
			<?php echo elgg_echo("videochat:welcome_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:resolution"); ?>:</label><br />
      <select name="resolution"><option selected="selected" value="<?php echo $cam[0]."x".$cam[1]; ?>"><?php echo $cam[0]."x".$cam[1]; ?></option><option value="320x240">320x240</option><option value="160x120">160x120</option><option value="176x144">176x144</option><option value="352x288">352x288</option><option value="640x480">640x480</option></select><br />
			<?php echo elgg_echo("videochat:resolution_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:camfps"); ?>:</label><br />
      <select name="camfps"><option selected="selected" value="<?php echo $camfps; ?>"><?php echo $camfps; ?></option><option value="15">15</option><option value="10">10</option><option value="12">12</option><option value="20">20</option><option value="25">25</option><option value="30">30</option></select> <br />
			<?php echo elgg_echo("videochat:camfps_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:micrate"); ?>:</label><br />
      <select name="micrate"><option selected="selected" value="<?php echo $micrate; ?>"><?php echo $micrate; ?></option><option value="11">11</option><option value="22">22</option><option value="44">44</option><option value="48">48</option></select> <br />
      <?php echo elgg_echo("videochat:micrate_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:camBandwidth"); ?>:</label><br />
      <input name='camBandwidth' value='<?php echo $camBandwidth; ?>' size="10"><br />
      <?php echo elgg_echo("videochat:camBandwidth_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:filterRegex"); ?>:</label><br />
			<input name='filterRegex' value='<?php echo $filterRegex; ?>' size="70"> <br />
			<?php echo elgg_echo("videochat:filterRegex_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:filterReplace"); ?>:</label><br />
			<input name='filterReplace' value='<?php echo $filterReplace; ?>' maxlength="22" size="22"> <br />
			<?php echo elgg_echo("videochat:filterReplace_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:showCamSettings"); ?>:</label><br />
      <?php selectAccess ('showCamSettings',$showCamSettings) ?>
      <?php echo elgg_echo("videochat:showCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:configureSource"); ?>:</label><br />
      <?php selectAccess ('configureSource',$configureSource) ?>
      <?php echo elgg_echo("videochat:configureSource_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:videoW"); ?>:</label><br />
      <input name='videoW' value='<?php echo $videoW; ?>' size="10"><br />
      <?php echo elgg_echo("videochat:videoW_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:videoH"); ?>:</label><br />
      <input name='videoH' value='<?php echo $videoH; ?>' size="10"><br />
      <?php echo elgg_echo("videochat:videoH_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:video2W"); ?>:</label><br />
      <input name='video2W' value='<?php echo $video2W; ?>' size="10"><br />
      <?php echo elgg_echo("videochat:video2W_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:video2H"); ?>:</label><br />
      <input name='video2H' value='<?php echo $video2H; ?>' size="10"><br />
      <?php echo elgg_echo("videochat:video2H_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:layoutCode"); ?>:</label><br />
      <textarea name="layoutCode" cols="32" rows="3"><?php echo $layoutCode; ?></textarea> <br />
      <?php echo elgg_echo("videochat:layoutCode_descr"); ?><br /><br />   
      <label><?php echo elgg_echo("videochat:chatTextColor"); ?>:</label><br />
      <input name='chatTextColor' value='<?php echo $chatTextColor; ?>' size="10"><br />
      <?php echo elgg_echo("videochat:chatTextColor_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:disableVideo"); ?>:</label><br />
      <?php selectAccess ('disableVideo',$disableVideo) ?>
      <?php echo elgg_echo("videochat:disableVideo_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:disableSound"); ?>:</label><br />
      <?php selectAccess ('disableSound',$disableSound) ?>
      <?php echo elgg_echo("videochat:disableSound_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:showTimer"); ?>:</label><br />
      <?php selectAccess ('showTimer',$showTimer) ?>
      <?php echo elgg_echo("videochat:showTimer_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:disableEmoticons"); ?>:</label><br />
      <?php selectAccess ('disableEmoticons',$disableEmoticons) ?>
      <?php echo elgg_echo("videochat:disableEmoticons_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:showTextChat"); ?>:</label><br />
      <?php selectAccess ('showTextChat',$showTextChat) ?>
      <?php echo elgg_echo("videochat:showTextChat_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:sendTextChat"); ?>:</label><br />
      <?php selectAccess ('sendTextChat',$sendTextChat) ?>
      <?php echo elgg_echo("videochat:sendTextChat_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableP2P"); ?>:</label><br />
      <?php selectAccess ('enableP2P',$enableP2P) ?>
      <?php echo elgg_echo("videochat:enableP2P_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableServer"); ?>:</label><br />
      <?php selectAccess ('enableServer',$enableServer) ?>
      <?php echo elgg_echo("videochat:enableServer_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:configureConnection"); ?>:</label><br />
      <?php selectAccess ('configureConnection',$configureConnection) ?>
      <?php echo elgg_echo("videochat:configureConnection_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableNext"); ?>:</label><br />
      <?php selectAccess ('enableNext',$enableNext) ?>
      <?php echo elgg_echo("videochat:enableNext_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableBuzz"); ?>:</label><br />
      <?php selectAccess ('enableBuzz',$enableBuzz) ?>
      <?php echo elgg_echo("videochat:enableBuzz_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableSoundFx"); ?>:</label><br />
      <?php selectAccess ('enableSoundFx',$enableSoundFx) ?>
      <?php echo elgg_echo("videochat:enableSoundFx_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:requestSnapshot"); ?>:</label><br />
      <?php selectAccess ('requestSnapshot',$requestSnapshot) ?>
      <?php echo elgg_echo("videochat:requestSnapshot_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:autoSnapshots"); ?>:</label><br />
      <?php selectAccess ('autoSnapshots',$autoSnapshots) ?>
      <?php echo elgg_echo("videochat:autoSnapshots_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:camPicture"); ?>:</label><br />
      <?php selectAccess ('camPicture',$camPicture) ?>
      <?php echo elgg_echo("videochat:camPicture_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableButtonLabels"); ?>:</label><br />
      <?php selectAccess ('enableButtonLabels',$enableButtonLabels) ?>
      <?php echo elgg_echo("videochat:enableButtonLabels_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableFullscreen"); ?>:</label><br />
      <?php selectAccess ('enableFullscreen',$enableFullscreen) ?>
      <?php echo elgg_echo("videochat:enableFullscreen_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableSwap"); ?>:</label><br />
      <?php selectAccess ('enableSwap',$enableSwap) ?>
      <?php echo elgg_echo("videochat:enableSwap_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableLogout"); ?>:</label><br />
      <?php selectAccess ('enableLogout',$enableLogout) ?>
      <?php echo elgg_echo("videochat:enableLogout_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableLogo"); ?>:</label><br />
      <?php selectAccess ('enableLogo',$enableLogo) ?>
      <?php echo elgg_echo("videochat:enableLogo_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableHeaders"); ?>:</label><br />
      <?php selectAccess ('enableHeaders',$enableHeaders) ?>
      <?php echo elgg_echo("videochat:enableHeaders_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableTitles"); ?>:</label><br />
      <?php selectAccess ('enableTitles',$enableTitles) ?>
      <?php echo elgg_echo("videochat:enableTitles_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:visitor"); ?>:</label><br />
      <select name="visitor">
      <?php if ($visitor==1) echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
        elseif ($visitor==0) echo "<option selected=\"selected\" value=\"0\">no</option><option value=\"1\">yes</option></select><br />";
        else echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
      ?>
      <?php echo elgg_echo("videochat:visitor_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:verboseLevel"); ?>:</label><br />
      <select name="verboseLevel"><option selected="selected" value="<?php echo $verboseLevel; ?>"><?php if ($verboseLevel == 0) echo "Nothing"; elseif ($verboseLevel == 1) echo "Failure"; elseif ($verboseLevel == 2) echo "Warning"; elseif ($verboseLevel == 3) echo "Success"; else echo "Action"; ?></option><option value="0">Nothing</option><option value="1">Failure</option><option value="2">Warning</option><option value="3">Success</option><option value="4">Action</option></select> <br />
      <?php echo elgg_echo("videochat:verboseLevel_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:userList"); ?>:</label><br />
      <textarea name="userList" cols="32" rows="3"><?php echo $userList; ?></textarea> <br />
      <?php echo elgg_echo("videochat:userList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:moderatorList"); ?>:</label><br />
      <textarea name="moderatorList" cols="32" rows="3"><?php echo $moderatorList; ?></textarea> <br />
      <?php echo elgg_echo("videochat:moderatorList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:cleanUp"); ?>:</label><br />
      <input name='cleanUp' value='<?php echo $cleanUp; ?>' size="10"><br />
      <?php echo elgg_echo("videochat:cleanUp_descr"); ?><br /><br />
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
