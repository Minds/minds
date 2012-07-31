<?php

$rtmp_server = "rtmp://localhost/videowhisper";

// function view select_setting
function selectSetting ($data,$vconf_data,$default) {
  $select_name = array ("no","yes");
  echo "<select name=$data>";
  $datax = datalist_get($vconf_data);
  if (isset ($datax)) {
    for ($no=0; $no<2; $no++) {
      if ($no==datalist_get($vconf_data))
        echo "<option selected='selected' value=$no>$select_name[$no]</option>";
        else
        echo "<option value=$no>$select_name[$no]</option>";
    }
  } else for ($no=0; $no<2; $no++) {
          if ($no==$default)
          echo "<option selected='selected' value=$no>$select_name[$no]</option>";
            else
            echo "<option value=$no>$select_name[$no]</option>";
        }
          echo "</select> <br />"; 
}


if (defined('ACCESS_DEFAULT')) $access_id = ACCESS_DEFAULT;
else $access_id=2;

?>
<div class="videoconference_new">
	<form action="<?php echo elgg_add_action_tokens_to_url($vars['url']."action/videoconference/setting"); ?>" method="post" name="settingForm">
      <label><?php echo elgg_echo("videoconference:rtmp_server"); ?>:</label><br /> 
			<input name='rtmp_server' value="<?php if (datalist_get('vconf_rtmp_server')) echo datalist_get('vconf_rtmp_server'); else echo 'rtmp://localhost/videowhisper'; ?>" id="videoconference_large-rtmp_server" size="70"><br /> 
			<?php echo elgg_echo("videoconference:rtmp_server_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:rtmp_amf"); ?>:</label><br />
			<input name='rtmp_amf' value="<?php if (datalist_get('vconf_rtmp_amf')) echo datalist_get('vconf_rtmp_amf'); else echo 'AMF3'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconference:rtmp_amf_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconference:rtmfp_server"); ?>:</label><br /> 
		<input name='rtmfp_server' value="<?php if (datalist_get('vconf_rtmfp_server')) echo datalist_get('vconf_rtmfp_server'); else echo 'rtmfp://stratus.adobe.com/f1533cc06e4de4b56399b10d-1a624022ff71/'; ?>" id="livestreaming_large-rtmfp_server" size="70"><br /> 
	  <?php echo elgg_echo("videoconference:rtmfp_server_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:p2pGroup"); ?>:</label><br /> 
		<input name='p2pGroup' value="<?php if (datalist_get('vconf_p2pGroup')) echo datalist_get('vconf_p2pGroup'); else echo 'VideoWhisper'; ?>" size="10"><br /> 
	  <?php echo elgg_echo("videoconference:p2pGroup_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:enableRTMP"); ?>:</label><br /> 
      <?php selectSetting ('enableRTMP','vconf_enableRTMP',1); ?>
	  <?php echo elgg_echo("videoconference:enableRTMP_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:enableP2P"); ?>:</label><br /> 
      <?php selectSetting ('enableP2P','vconf_enableP2P',0); ?>
	  <?php echo elgg_echo("videoconference:enableP2P_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:supportRTMP"); ?>:</label><br /> 
      <?php selectSetting ('supportRTMP','vconf_supportRTMP',1); ?>
	  <?php echo elgg_echo("videoconference:supportRTMP_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:supportP2P"); ?>:</label><br /> 
      <?php selectSetting ('supportP2P','vconf_supportP2P',1); ?>
	  <?php echo elgg_echo("videoconference:supportP2P_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:alwaysRTMP"); ?>:</label><br /> 
      <?php selectSetting ('alwaysRTMP','vconf_alwaysRTMP',0); ?>
	  <?php echo elgg_echo("videoconference:alwaysRTMP_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:alwaysP2P"); ?>:</label><br /> 
      <?php selectSetting ('alwaysP2P','vconf_alwaysP2P',0); ?>
	  <?php echo elgg_echo("videoconference:alwaysP2P_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconference:camMaxBandwidth"); ?>:</label><br />
      <input name='camMaxBandwidth' value="<?php if (datalist_get('vconf_camMaxBandwidth')) echo datalist_get('vconf_camMaxBandwidth'); else echo '81920'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconference:camMaxBandwidth_descr"); ?><br /><br /> 
      <label><?php echo elgg_echo("videoconference:bufferFullPlayback"); ?>:</label><br />
      <input name='bufferFullPlayback' value="<?php if (datalist_get('vconf_bufferFullPlayback')) echo datalist_get('vconf_bufferFullPlayback'); else echo '0.1'; ?>"  size="10"><br />
      <?php echo elgg_echo("videoconference:bufferFullPlayback_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconference:bufferLivePlayback"); ?>:</label><br />
      <input name='bufferLivePlayback' value="<?php if (datalist_get('vconf_bufferLivePlayback')) echo datalist_get('vconf_bufferLivePlayback'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconference:bufferLivePlayback_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconference:bufferFull"); ?>:</label><br />
      <input name='bufferFull' value="<?php if (datalist_get('vconf_bufferFull')) echo datalist_get('vconf_bufferFull'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconference:bufferFull_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconference:bufferLive"); ?>:</label><br />
      <input name='bufferLive' value="<?php if (datalist_get('vconf_bufferLive')) echo datalist_get('vconf_bufferLive'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconference:bufferLive_descr"); ?><br /> <br />
			<label><?php echo elgg_echo("videoconference:disableBandwidthDetection"); ?>:</label><br />
      <?php selectSetting ('disableBandwidthDetection','vconf_disableBandwidthDetection',0); ?>
      <?php echo elgg_echo("videoconference:disableBandwidthDetection_descr"); ?><br /><br /> 
      <label><?php echo elgg_echo("videoconference:disableUploadDetection"); ?>:</label><br />
      <?php selectSetting ('disableUploadDetection','vconf_disableUploadDetection',0); ?>
      <?php echo elgg_echo("videoconference:disableUploadDetection_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconference:limitByBandwidth"); ?>:</label><br />
      <?php selectSetting ('limitByBandwidth','vconf_limitByBandwidth',1); ?>
      <?php echo elgg_echo("videoconference:limitByBandwidth_descr"); ?><br /> <br />
			<label><?php echo elgg_echo("videoconference:floodProtection"); ?>:</label><br />
			<input name='floodProtection' value="<?php if (datalist_get('vconf_floodProtection')) echo datalist_get('vconf_floodProtection'); else echo '3'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconference:floodProtection_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconference:adsTimeout"); ?>:</label><br />
      <input name='adsTimeout' value="<?php if (datalist_get('vconf_adsTimeout')) echo datalist_get('vconf_adsTimeout'); else echo '15000'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconference:adsTimeout_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconference:adsInterval"); ?>:</label><br />
      <input name='adsInterval' value="<?php if (datalist_get('vconf_adsInterval')) echo datalist_get('vconf_adsInterval'); else echo '240000'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconference:adsInterval_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconference:statusInterval"); ?>:</label><br />
      <input name='statusInterval' value="<?php if (datalist_get('vconf_statusInterval')) echo datalist_get('vconf_statusInterval'); else echo '10000'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconference:statusInterval_descr"); ?><br />  <br />
      <label><?php echo elgg_echo("videoconference:ws_ads"); ?>:</label><br />
      <input name='ws_ads' value="<?php if (datalist_get('vconf_ws_ads')) echo datalist_get('vconf_ws_ads'); else echo 'ads.php'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconference:ws_ads_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconference:visitor"); ?>:</label><br />
      <?php selectSetting ('visitor','vconf_visitor',1); ?>
      <?php echo elgg_echo("videoconference:visitor_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconference:cleanUp2"); ?>:</label><br />
      <input name='cleanUp' value="<?php if (datalist_get('vconf_cleanUp')) echo datalist_get('vconf_cleanUp'); else echo '0'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconference:cleanUp2_descr"); ?><br /><br />
			<br />
<?php
echo elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'name' => 'save',
));
?>
	</form>
</div>
