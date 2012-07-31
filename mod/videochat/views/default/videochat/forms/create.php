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

// chatTextColor
$chatTextColor = "#";
for ($i=0;$i<3;$i++) $chatTextColor .= rand(0,70);

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
	
<div class="videochat_new">

	<form action="<?php echo elgg_add_action_tokens_to_url($vars['url']."action/videochat/create"); ?>" method="post" name="roomForm">
			<label><?php echo elgg_echo("videochat:name"); ?>:</label><br />

			<input type="text" id="room" maxlength="22" size="22" name='room' value='<?php if (get_input ('roomname')) echo $roomnm; else echo $generated ?>' onKeyDown="textCounter(document.roomForm.room,document.roomForm.remLen1,22); censorName()" onKeyUp="textCounter(document.roomForm.room,document.roomForm.remLen1,22); censorName()" onChange="censorName()" >
			 <div  style='float:right'><input readonly type="text" name="remLen1" size="3" maxlength="3" value="22"></div>
			
<?php echo $msg ?><br />
      <?php echo elgg_echo("videochat:name_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videochat:description"); ?>:</label><br />
      <textarea name="description" cols="32" rows="3"></textarea> <br />
      <?php echo elgg_echo("videochat:description_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:welcome"); ?>:</label><br />
      <textarea name="welcome" cols="32" rows="3">Welcome to videochat room.</textarea><br />
			<?php echo elgg_echo("videochat:welcome_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:resolution"); ?>:</label><br />
      <select name="resolution"><option selected="selected" value="320x240">320x240</option><option value="160x120">160x120</option><option value="176x144">176x144</option><option value="352x288">352x288</option><option value="640x480">640x480</option></select><br />
			<?php echo elgg_echo("videochat:resolution_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:camfps"); ?>:</label><br />
      <select name="camfps"><option selected="selected" value="15">15</option><option value="10">10</option><option value="12">12</option><option value="20">20</option><option value="25">25</option><option value="30">30</option></select> <br />
			<?php echo elgg_echo("videochat:camfps_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:micrate"); ?>:</label><br />
      <select name="micrate"><option selected="selected" value="11">11</option><option value="22">22</option><option value="44">44</option><option value="48">48</option></select> <br />
      <?php echo elgg_echo("videochat:micrate_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:camBandwidth"); ?>:</label><br />
      <input name='camBandwidth' value='40960' size="10"><br />
      <?php echo elgg_echo("videochat:camBandwidth_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:filterRegex"); ?>:</label><br />
			<input name='filterRegex' value='(?i)(fuck|cunt)(?-i)' size="70"> <br />
			<?php echo elgg_echo("videochat:filterRegex_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:filterReplace"); ?>:</label><br />
			<input name='filterReplace' value=' ** ' maxlength="22" size="22"> <br />
			<?php echo elgg_echo("videochat:filterReplace_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:showCamSettings"); ?>:</label><br />
      <?php selectAccess ('showCamSettings',0) ?>
      <?php echo elgg_echo("videochat:showCamSettings_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:configureSource"); ?>:</label><br />
      <?php selectAccess ('configureSource',0) ?>
      <?php echo elgg_echo("videochat:configureSource_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:camBandwidth"); ?>:</label><br />
      <input name='camBandwidth' value='40960' size="10"><br />
      <?php echo elgg_echo("videochat:camBandwidth_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:videoW"); ?>:</label><br />
      <input name='videoW' value='480' size="10"><br />
      <?php echo elgg_echo("videochat:videoW_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:videoH"); ?>:</label><br />
      <input name='videoH' value='365' size="10"><br />
      <?php echo elgg_echo("videochat:videoH_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:video2W"); ?>:</label><br />
      <input name='video2W' value='480' size="10"><br />
      <?php echo elgg_echo("videochat:video2W_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:video2H"); ?>:</label><br />
      <input name='video2H' value='365' size="10"><br />
      <?php echo elgg_echo("videochat:video2H_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:layoutCode"); ?>:</label><br />
      <textarea name="layoutCode" cols="32" rows="3">id=soundfx&x=766&y=571; id=bFul&x=15&y=105; id=VideoSlot2&x=510&y=140; id=ChatSlot&x=250&y=505; id=VideoSlot1&x=10&y=140; id=TextInput&x=250&y=670; id=head2&x=510&y=100; id=logo&x=389&y=25; id=bSnd&x=920&y=107; id=head&x=10&y=100; id=next&x=186&y=521; id=bVid&x=885&y=109; id=connection&x=186&y=571; id=bLogout&x=950&y=10; id=bFul2&x=955&y=105; id=bSwap&x=120&y=111; id=bSwap2&x=850&y=111; id=snapshot&x=766&y=621; id=camera&x=186&y=621; id=bCam&x=85&y=109; id=bMic&x=50&y=107; id=buzz&x=766&y=521</textarea> <br />
      <?php echo elgg_echo("videochat:layoutCode_descr"); ?><br /><br />   
      <label><?php echo elgg_echo("videochat:chatTextColor"); ?>:</label><br />
      <input name='chatTextColor' value='<?php echo $chatTextColor; ?>' size="10"><br />
      <?php echo elgg_echo("videochat:chatTextColor_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:disableVideo"); ?>:</label><br />
      <?php selectAccess ('disableVideo',0) ?>
      <?php echo elgg_echo("videochat:disableVideo_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:disableSound"); ?>:</label><br />
      <?php selectAccess ('disableSound',0) ?>
      <?php echo elgg_echo("videochat:disableSound_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:showTimer"); ?>:</label><br />
      <?php selectAccess ('showTimer',0) ?>
      <?php echo elgg_echo("videochat:showTimer_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:disableEmoticons"); ?>:</label><br />
      <?php selectAccess ('disableEmoticons',4) ?>
      <?php echo elgg_echo("videochat:disableEmoticons_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:showTextChat"); ?>:</label><br />
      <?php selectAccess ('showTextChat',0) ?>
      <?php echo elgg_echo("videochat:showTextChat_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:sendTextChat"); ?>:</label><br />
      <?php selectAccess ('sendTextChat',0) ?>
      <?php echo elgg_echo("videochat:sendTextChat_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableP2P"); ?>:</label><br />
      <?php selectAccess ('enableP2P',0) ?>
      <?php echo elgg_echo("videochat:enableP2P_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableServer"); ?>:</label><br />
      <?php selectAccess ('enableServer',0) ?>
      <?php echo elgg_echo("videochat:enableServer_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:configureConnection"); ?>:</label><br />
      <?php selectAccess ('configureConnection',0) ?>
      <?php echo elgg_echo("videochat:configureConnection_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableNext"); ?>:</label><br />
      <?php selectAccess ('enableNext',0) ?>
      <?php echo elgg_echo("videochat:enableNext_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableBuzz"); ?>:</label><br />
      <?php selectAccess ('enableBuzz',0) ?>
      <?php echo elgg_echo("videochat:enableBuzz_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableSoundFx"); ?>:</label><br />
      <?php selectAccess ('enableSoundFx',0) ?>
      <?php echo elgg_echo("videochat:enableSoundFx_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:requestSnapshot"); ?>:</label><br />
      <?php selectAccess ('requestSnapshot',0) ?>
      <?php echo elgg_echo("videochat:requestSnapshot_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:autoSnapshots"); ?>:</label><br />
      <?php selectAccess ('autoSnapshots',0) ?>
      <?php echo elgg_echo("videochat:autoSnapshots_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:camPicture"); ?>:</label><br />
      <?php selectAccess ('camPicture',4) ?>
      <?php echo elgg_echo("videochat:camPicture_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableButtonLabels"); ?>:</label><br />
      <?php selectAccess ('enableButtonLabels',0) ?>
      <?php echo elgg_echo("videochat:enableButtonLabels_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableFullscreen"); ?>:</label><br />
      <?php selectAccess ('enableFullscreen',0) ?>
      <?php echo elgg_echo("videochat:enableFullscreen_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableSwap"); ?>:</label><br />
      <?php selectAccess ('enableSwap',0) ?>
      <?php echo elgg_echo("videochat:enableSwap_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableLogout"); ?>:</label><br />
      <?php selectAccess ('enableLogout',0) ?>
      <?php echo elgg_echo("videochat:enableLogout_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableLogo"); ?>:</label><br />
      <?php selectAccess ('enableLogo',0) ?>
      <?php echo elgg_echo("videochat:enableLogo_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableHeaders"); ?>:</label><br />
      <?php selectAccess ('enableHeaders',0) ?>
      <?php echo elgg_echo("videochat:enableHeaders_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:enableTitles"); ?>:</label><br />
      <?php selectAccess ('enableTitles',0) ?>
      <?php echo elgg_echo("videochat:enableTitles_descr"); ?><br /><br />
			<label><?php echo elgg_echo("videochat:visitor"); ?>:</label><br />
      <select name="visitor">
      <?php if (datalist_get('vchat_visitor')==1) echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
        elseif (datalist_get('vchat_visitor')==0) echo "<option selected=\"selected\" value=\"0\">no</option><option value=\"1\">yes</option></select><br />";
        else echo "<option selected=\"selected\" value=\"1\">yes</option><option value=\"0\">no</option></select><br />";
      ?>
      <?php echo elgg_echo("videochat:visitor_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:verboseLevel"); ?>:</label><br />
      <select name="verboseLevel"><option selected="selected" value="0">Nothing</option><option value="1">Failure</option><option value="2">Warning</option><option value="3">Success</option><option value="4">Action</option></select> <br />
      <?php echo elgg_echo("videochat:verboseLevel_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:userList"); ?>:</label><br />
      <textarea name="userList" cols="32" rows="3"></textarea> <br />
      <?php echo elgg_echo("videochat:userList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:moderatorList"); ?>:</label><br />
      <textarea name="moderatorList" cols="32" rows="3"><?php echo $username; ?></textarea> <br />
      <?php echo elgg_echo("videochat:moderatorList_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:cleanUp"); ?>:</label><br />
      <input name='cleanUp' value='0' size="10"><br />
      <?php echo elgg_echo("videochat:cleanUp_descr"); ?><br /><br />
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
