<?php

// function view select_setting
function selectSetting ($data,$vchat_data,$default) {
  $select_name = array ("no","yes");
  echo "<select name=$data>";
  $datax = datalist_get($vchat_data);
  if (isset ($datax)) {
    for ($no=0; $no<2; $no++) {
      if ($no==datalist_get($vchat_data))
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

?>
<div class="videochat_new">
	<form action="<?php echo elgg_add_action_tokens_to_url($vars['url']."action/videochat/setting"); ?>" method="post" name="settingForm">
      <label><?php echo elgg_echo("videochat:rtmp_server"); ?>:</label><br /> 
			<input name='rtmp_server' value="<?php if (datalist_get('vchat_rtmp_server')) echo datalist_get('vchat_rtmp_server'); else echo 'rtmp://localhost/videowhisper'; ?>" id="videochat_large-rtmp_server" size="70"><br /> 
			<?php echo elgg_echo("videochat:rtmp_server_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videochat:rtmp_amf"); ?>:</label><br />
			<input name='rtmp_amf' value="<?php if (datalist_get('vchat_rtmp_amf')) echo datalist_get('vchat_rtmp_amf'); else echo 'AMF3'; ?>" size="10"><br />
      <?php echo elgg_echo("videochat:rtmp_amf_descr"); ?><br /> <br />
			<label><?php echo elgg_echo("videochat:rtmfp_server"); ?>:</label><br />
			<input name='rtmfp_server' value="<?php if (datalist_get('vchat_rtmfp_server')) echo datalist_get('vchat_rtmfp_server'); else echo 'rtmfp://stratus.adobe.com/f1533cc06e4de4b56399b10d-1a624022ff71/'; ?>" size="70"><br />
      <?php echo elgg_echo("videochat:rtmfp_server_descr"); ?><br /> <br />
			<label><?php echo elgg_echo("videochat:serverProxy"); ?>:</label><br />
			<input name='serverProxy' value="<?php if (datalist_get('vchat_serverProxy')) echo datalist_get('vchat_serverProxy'); else echo 'best'; ?>" size="70"><br />
      <?php echo elgg_echo("videochat:serverProxy_descr"); ?><br /> <br />
			<label><?php echo elgg_echo("videochat:snapshotstime"); ?>:</label><br />
			<input name='snapshotsTime' value="<?php if (datalist_get('vchat_snapshotsTime')) echo datalist_get('vchat_snapshotsTime'); else echo '20000'; ?>" size="10"><br />
      <?php echo elgg_echo("videochat:snapshotstime_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videochat:camMaxBandwidth"); ?>:</label><br />
      <input name='camMaxBandwidth' value="<?php if (datalist_get('vchat_camMaxBandwidth')) echo datalist_get('vchat_camMaxBandwidth'); else echo '81920'; ?>" size="10"><br />
      <?php echo elgg_echo("videochat:camMaxBandwidth_descr"); ?><br /><br /> 
      <label><?php echo elgg_echo("videochat:bufferFullPlayback"); ?>:</label><br />
      <input name='bufferFullPlayback' value="<?php if (datalist_get('vchat_bufferFullPlayback')) echo datalist_get('vchat_bufferFullPlayback'); else echo '0.1'; ?>"  size="10"><br />
      <?php echo elgg_echo("videochat:bufferFullPlayback_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videochat:bufferLivePlayback"); ?>:</label><br />
      <input name='bufferLivePlayback' value="<?php if (datalist_get('vchat_bufferLivePlayback')) echo datalist_get('vchat_bufferLivePlayback'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("videochat:bufferLivePlayback_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videochat:bufferFull"); ?>:</label><br />
      <input name='bufferFull' value="<?php if (datalist_get('vchat_bufferFull')) echo datalist_get('vchat_bufferFull'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("videochat:bufferFull_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videochat:bufferLive"); ?>:</label><br />
      <input name='bufferLive' value="<?php if (datalist_get('vchat_bufferLive')) echo datalist_get('vchat_bufferLive'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("videochat:bufferLive_descr"); ?><br /> <br />
			<label><?php echo elgg_echo("videochat:disableBandwidthDetection"); ?>:</label><br />
      <?php selectSetting ('disableBandwidthDetection','vchat_disableBandwidthDetection',0); ?>
      <?php echo elgg_echo("videochat:disableBandwidthDetection_descr"); ?><br /><br /> 
      <label><?php echo elgg_echo("videochat:disableUploadDetection"); ?>:</label><br />
      <?php selectSetting ('disableUploadDetection','vchat_disableUploadDetection',0); ?>
      <?php echo elgg_echo("videochat:disableUploadDetection_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videochat:limitByBandwidth"); ?>:</label><br />
      <?php selectSetting ('limitByBandwidth','vchat_limitByBandwidth',1); ?>
      <?php echo elgg_echo("videochat:limitByBandwidth_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videochat:adsTimeout"); ?>:</label><br />
      <input name='adsTimeout' value="<?php if (datalist_get('vchat_adsTimeout')) echo datalist_get('vchat_adsTimeout'); else echo '15000'; ?>" size="10"><br />
      <?php echo elgg_echo("videochat:adsTimeout_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videochat:adsInterval"); ?>:</label><br />
      <input name='adsInterval' value="<?php if (datalist_get('vchat_adsInterval')) echo datalist_get('vchat_adsInterval'); else echo '240000'; ?>" size="10"><br />
      <?php echo elgg_echo("videochat:adsInterval_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videochat:adServer"); ?>:</label><br />
      <input name='adServer' value="<?php if (datalist_get('vchat_adServer')) echo datalist_get('vchat_adServer'); else echo '2_ads.php'; ?>" size="10"><br />
      <?php echo elgg_echo("videochat:adServer_descr"); ?><br />  <br />
      <label><?php echo elgg_echo("videochat:visitor"); ?>:</label><br />
      <?php selectSetting ('visitor','vchat_visitor',1); ?>
      <?php echo elgg_echo("videochat:visitor_descr"); ?><br /> <br />

	  <label><?php echo elgg_echo("videochat:availability"); ?>:</label><br />
	  <input name='availability' value="<?php if (datalist_get('vchat_availability')) echo datalist_get('vchat_availability'); else echo '0'; ?>" size="10"><br />
    <?php echo elgg_echo("videochat:availability_descr"); ?><br /> <br />

<?php
echo elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'name' => 'save',
));
?>
	</form>
</div>
