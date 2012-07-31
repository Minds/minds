<?php

// function view select_setting
function selectSetting ($data,$lstr_data,$default) {
  $select_name = array ("no","yes");
  echo "<select name=$data>";
  $datax = datalist_get($lstr_data);
  if (isset ($datax)) {
    for ($no=0; $no<2; $no++) {
      if ($no==datalist_get($lstr_data))
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
<div class="livestreaming_new">
	<form action="<?php echo elgg_add_action_tokens_to_url($vars['url']."action/livestreaming/setting"); ?>" method="post" name="settingForm">
      <label><?php echo elgg_echo("livestreaming:rtmp_server"); ?>:</label><br /> 
		<input name='rtmp_server' value="<?php if (datalist_get('lstr_rtmp_server')) echo datalist_get('lstr_rtmp_server'); else echo 'rtmp://localhost/videowhisper'; ?>" id="livestreaming_large-rtmp_server" size="70"><br /> 
	  <?php echo elgg_echo("livestreaming:rtmp_server_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:rtmp_amf"); ?>:</label><br />
		<input name='rtmp_amf' value="<?php if (datalist_get('lstr_rtmp_amf')) echo datalist_get('lstr_rtmp_amf'); else echo 'AMF3'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:rtmp_amf_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("livestreaming:rtmfp_server"); ?>:</label><br /> 
		<input name='rtmfp_server' value="<?php if (datalist_get('lstr_rtmfp_server')) echo datalist_get('lstr_rtmfp_server'); else echo 'rtmfp://stratus.adobe.com/f1533cc06e4de4b56399b10d-1a624022ff71/'; ?>" id="livestreaming_large-rtmfp_server" size="70"><br /> 
	  <?php echo elgg_echo("livestreaming:rtmfp_server_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:enableRTMP"); ?>:</label><br /> 
      <?php selectSetting ('enableRTMP','lstr_enableRTMP',1); ?>
	  <?php echo elgg_echo("livestreaming:enableRTMP_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:enableP2P"); ?>:</label><br /> 
      <?php selectSetting ('enableP2P','lstr_enableP2P',0); ?>
	  <?php echo elgg_echo("livestreaming:enableP2P_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:supportRTMP"); ?>:</label><br /> 
      <?php selectSetting ('supportRTMP','lstr_supportRTMP',1); ?>
	  <?php echo elgg_echo("livestreaming:supportRTMP_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:supportP2P"); ?>:</label><br /> 
      <?php selectSetting ('supportP2P','lstr_supportP2P',1); ?>
	  <?php echo elgg_echo("livestreaming:supportP2P_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:alwaysRTMP"); ?>:</label><br /> 
      <?php selectSetting ('alwaysRTMP','lstr_alwaysRTMP',0); ?>
	  <?php echo elgg_echo("livestreaming:alwaysRTMP_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:alwaysP2P"); ?>:</label><br /> 
      <?php selectSetting ('alwaysP2P','lstr_alwaysP2P',0); ?>
	  <?php echo elgg_echo("livestreaming:alwaysP2P_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:serverProxy"); ?>:</label><br /> 
		<input name='serverProxy' value="<?php if (datalist_get('lstr_serverProxy')) echo datalist_get('lstr_serverProxy'); else echo 'best'; ?>" size="10"><br /> 
	  <?php echo elgg_echo("livestreaming:serverProxy_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:p2pGroup"); ?>:</label><br /> 
		<input name='p2pGroup' value="<?php if (datalist_get('lstr_p2pGroup')) echo datalist_get('lstr_p2pGroup'); else echo 'VideoWhisper'; ?>" size="10"><br /> 
	  <?php echo elgg_echo("livestreaming:p2pGroup_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:tokenKey"); ?>:</label><br />
		<input name='tokenKey' value="<?php if (datalist_get('lstr_tokenKey')) echo datalist_get('lstr_tokenKey'); else echo 'VideoWhisper'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:tokenkey_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("livestreaming:videoCodec"); ?>:</label><br /> 
		<input name='videoCodec' value="<?php if (datalist_get('lstr_videoCodec')) echo datalist_get('lstr_videoCodec'); else echo 'H264'; ?>" size="10"><br /> 
	  <?php echo elgg_echo("livestreaming:videoCodec_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:codecProfile"); ?>:</label><br /> 
		<input name='codecProfile' value="<?php if (datalist_get('lstr_codecProfile')) echo datalist_get('lstr_codecProfile'); else echo 'main'; ?>" size="10"><br /> 
	  <?php echo elgg_echo("livestreaming:codecProfile_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:codecLevel"); ?>:</label><br /> 
		<input name='codecLevel' value="<?php if (datalist_get('lstr_codecLevel')) echo datalist_get('lstr_codecLevel'); else echo '3.1'; ?>" size="10"><br /> 
	  <?php echo elgg_echo("livestreaming:codecLevel_descr"); ?><br /><br />
      <label><?php echo elgg_echo("livestreaming:soundCodec"); ?>:</label><br /> 
		<input name='soundCodec' value="<?php if (datalist_get('lstr_soundCodec')) echo datalist_get('lstr_soundCodec'); else echo 'Speex'; ?>" size="10"><br /> 
	  <?php echo elgg_echo("livestreaming:soundCodec_descr"); ?><br /><br />
			<label><?php echo elgg_echo("livestreaming:snapshotsTime"); ?>:</label><br />
			<input name='snapshotsTime' value="<?php if (datalist_get('lstr_snapshotsTime')) echo datalist_get('lstr_snapshotsTime'); else echo '60000'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:snapshotstime_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("livestreaming:camMaxBandwidth"); ?>:</label><br />
      <input name='camMaxBandwidth' value="<?php if (datalist_get('lstr_camMaxBandwidth')) echo datalist_get('lstr_camMaxBandwidth'); else echo '81920'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:camMaxBandwidth_descr"); ?><br /><br /> 
      <label><?php echo elgg_echo("livestreaming:bufferFull"); ?>:</label><br />
      <input name='bufferFull' value="<?php if (datalist_get('lstr_bufferFull')) echo datalist_get('lstr_bufferFull'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:bufferFull_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("livestreaming:bufferLive"); ?>:</label><br />
      <input name='bufferLive' value="<?php if (datalist_get('lstr_bufferLive')) echo datalist_get('lstr_bufferLive'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:bufferLive_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("livestreaming:bufferFull2"); ?>:</label><br />
      <input name='bufferFull2' value="<?php if (datalist_get('lstr_bufferFull2')) echo datalist_get('lstr_bufferFull2'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:bufferFull2_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("livestreaming:bufferLive2"); ?>:</label><br />
      <input name='bufferLive2' value="<?php if (datalist_get('lstr_bufferLive2')) echo datalist_get('lstr_bufferLive2'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:bufferLive2_descr"); ?><br /> <br />
	  <label><?php echo elgg_echo("livestreaming:disableBandwidthDetection"); ?>:</label><br />
      <?php selectSetting ('disableBandwidthDetection','lstr_disableBandwidthDetection',0); ?>
      <?php echo elgg_echo("livestreaming:disableBandwidthDetection_descr"); ?><br /><br /> 
      <label><?php echo elgg_echo("livestreaming:limitByBandwidth"); ?>:</label><br />
      <?php selectSetting ('limitByBandwidth','lstr_limitByBandwidth',0); ?>
      <?php echo elgg_echo("livestreaming:limitByBandwidth_descr"); ?><br /> <br />
	  <label><?php echo elgg_echo("livestreaming:floodProtection"); ?>:</label><br />
	  <input name='floodProtection' value="<?php if (datalist_get('lstr_floodProtection')) echo datalist_get('lstr_floodProtection'); else echo '1'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:floodProtection_descr"); ?><br /> <br />
	  <label><?php echo elgg_echo("livestreaming:floodProtection2"); ?>:</label><br />
	  <input name='floodProtection2' value="<?php if (datalist_get('lstr_floodProtection2')) echo datalist_get('lstr_floodProtection2'); else echo '3'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:floodProtection2_descr"); ?><br /> <br />
	  <label><?php echo elgg_echo("livestreaming:generateSnapshots"); ?>:</label><br />
      <?php selectSetting ('generateSnapshots','lstr_generateSnapshots',1); ?>
      <?php echo elgg_echo("livestreaming:generateSnapshots_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("livestreaming:externalInterval"); ?>:</label><br />
      <input name='externalInterval' value="<?php if (datalist_get('lstr_externalInterval')) echo datalist_get('lstr_externalInterval'); else echo '5000'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:externalInterval_descr"); ?><br /> <br />

      <label><?php echo elgg_echo("livestreaming:externalInterval2"); ?>:</label><br />
      <input name='externalInterval2' value="<?php if (datalist_get('lstr_externalInterval2')) echo datalist_get('lstr_externalInterval2'); else echo '6000'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:externalInterval2_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("livestreaming:adsTimeout"); ?>:</label><br />
      <input name='adsTimeout' value="<?php if (datalist_get('lstr_adsTimeout')) echo datalist_get('lstr_adsTimeout'); else echo '15000'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:adsTimeout_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("livestreaming:adsInterval"); ?>:</label><br />
      <input name='adsInterval' value="<?php if (datalist_get('lstr_adsInterval')) echo datalist_get('lstr_adsInterval'); else echo '240000'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:adsInterval_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("livestreaming:statusInterval"); ?>:</label><br />
      <input name='statusInterval' value="<?php if (datalist_get('lstr_statusInterval')) echo datalist_get('lstr_statusInterval'); else echo '10000'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:statusInterval_descr"); ?><br />  <br />
      <label><?php echo elgg_echo("livestreaming:ws_ads"); ?>:</label><br />
      <input name='ws_ads' value="<?php if (datalist_get('lstr_ws_ads')) echo datalist_get('lstr_ws_ads'); else echo 'ads.php'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:ws_ads_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("livestreaming:visitor"); ?>:</label><br />
      <?php selectSetting ('visitor','lstr_visitor',1); ?>
      <?php echo elgg_echo("livestreaming:visitor_descr"); ?><br /> <br />

	  <label><?php echo elgg_echo("livestreaming:availability"); ?>:</label><br />
	  <input name='availability' value="<?php if (datalist_get('lstr_availability')) echo datalist_get('lstr_availability'); else echo '0'; ?>" size="10"><br />
      <?php echo elgg_echo("livestreaming:availability_descr"); ?><br /> <br />
			<br />
<?php
echo elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'name' => 'save',
));
?>
	</form>
</div>
