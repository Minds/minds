<?php

$generated =  base_convert((time()-1224000000).rand(0,10),10,36);
$rtmp_server = "rtmp://localhost/videowhisper";

// function view select_setting
function selectSetting ($data,$vcons_data,$default) {
  $select_name = array ("no","yes");
  echo "<select name=$data>";
  $datax = datalist_get($vcons_data);
  if (isset ($datax)) {
    for ($no=0; $no<2; $no++) {
      if ($no==datalist_get($vcons_data))
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
<div class="videoconsultation_new">
	<form action="<?php echo elgg_add_action_tokens_to_url($vars['url']."action/videoconsultation/setting"); ?>" method="post" name="settingForm">
      <label><?php echo elgg_echo("videoconsultation:rtmp_server"); ?>:</label><br /> 
			<input name='rtmp_server' value="<?php if (datalist_get('vcons_rtmp_server')) echo datalist_get('vcons_rtmp_server'); else echo 'rtmp://localhost/videowhisper'; ?>" id="videoconsultation_large-rtmp_server" size="70"><br /> 
			<?php echo elgg_echo("videoconsultation:rtmp_server_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:rtmp_amf"); ?>:</label><br />
			<input name='rtmp_amf' value="<?php if (datalist_get('vcons_rtmp_amf')) echo datalist_get('vcons_rtmp_amf'); else echo 'AMF3'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconsultation:rtmp_amf_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconsultation:rtmfp_server"); ?>:</label><br /> 
		<input name='rtmfp_server' value="<?php if (datalist_get('vcons_rtmfp_server')) echo datalist_get('vcons_rtmfp_server'); else echo 'rtmfp://stratus.adobe.com/f1533cc06e4de4b56399b10d-1a624022ff71/'; ?>" id="livestreaming_large-rtmfp_server" size="70"><br /> 
	  <?php echo elgg_echo("videoconsultation:rtmfp_server_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:p2pGroup"); ?>:</label><br /> 
		<input name='p2pGroup' value="<?php if (datalist_get('vcons_p2pGroup')) echo datalist_get('vcons_p2pGroup'); else echo 'VideoWhisper'; ?>" size="10"><br /> 
	  <?php echo elgg_echo("videoconsultation:p2pGroup_descr"); ?><br /><br />
      <label><?php echo elgg_echo("videoconsultation:camMaxBandwidth"); ?>:</label><br />
      <input name='camMaxBandwidth' value="<?php if (datalist_get('vcons_camMaxBandwidth')) echo datalist_get('vcons_camMaxBandwidth'); else echo '81920'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconsultation:camMaxBandwidth_descr"); ?><br /><br /> 
      <label><?php echo elgg_echo("videoconsultation:bufferFullPlayback"); ?>:</label><br />
      <input name='bufferFullPlayback' value="<?php if (datalist_get('vcons_bufferFullPlayback')) echo datalist_get('vcons_bufferFullPlayback'); else echo '0.1'; ?>"  size="10"><br />
      <?php echo elgg_echo("videoconsultation:bufferFullPlayback_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconsultation:bufferLivePlayback"); ?>:</label><br />
      <input name='bufferLivePlayback' value="<?php if (datalist_get('vcons_bufferLivePlayback')) echo datalist_get('vcons_bufferLivePlayback'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconsultation:bufferLivePlayback_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconsultation:bufferFull"); ?>:</label><br />
      <input name='bufferFull' value="<?php if (datalist_get('vcons_bufferFull')) echo datalist_get('vcons_bufferFull'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconsultation:bufferFull_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconsultation:bufferLive"); ?>:</label><br />
      <input name='bufferLive' value="<?php if (datalist_get('vcons_bufferLive')) echo datalist_get('vcons_bufferLive'); else echo '0.1'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconsultation:bufferLive_descr"); ?><br /> <br />
			<label><?php echo elgg_echo("videoconsultation:disableBandwidthDetection"); ?>:</label><br />
      <?php selectSetting ('disableBandwidthDetection','vcons_disableBandwidthDetection',0); ?>
      <?php echo elgg_echo("videoconsultation:disableBandwidthDetection_descr"); ?><br /><br /> 
      <label><?php echo elgg_echo("videoconsultation:disableUploadDetection"); ?>:</label><br />
      <?php selectSetting ('disableUploadDetection','vcons_disableUploadDetection',0); ?>
      <?php echo elgg_echo("videoconsultation:disableUploadDetection_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconsultation:limitByBandwidth"); ?>:</label><br />
      <?php selectSetting ('limitByBandwidth','vcons_limitByBandwidth',1); ?>
      <?php echo elgg_echo("videoconsultation:limitByBandwidth_descr"); ?><br /> <br />
			<label><?php echo elgg_echo("videoconsultation:floodProtection"); ?>:</label><br />
			<input name='floodProtection' value="<?php if (datalist_get('vcons_floodProtection')) echo datalist_get('vcons_floodProtection'); else echo '3'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconsultation:floodProtection_descr"); ?><br /> <br />
      <label><?php echo elgg_echo("videoconsultation:statusInterval"); ?>:</label><br />
      <input name='statusInterval' value="<?php if (datalist_get('vcons_statusInterval')) echo datalist_get('vcons_statusInterval'); else echo '10000'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconsultation:statusInterval_descr"); ?><br />  <br />
      <label><?php echo elgg_echo("videoconsultation:visitor"); ?>:</label><br />
      <?php selectSetting ('visitor','vcons_visitor',1); ?>
      <?php echo elgg_echo("videoconsultation:visitor_descr"); ?><br /> <br />
  	  <label><?php echo elgg_echo("videoconsultation:availability"); ?>:</label><br />
	    <input name='availability' value="<?php if (datalist_get('vcons_availability')) echo datalist_get('vcons_availability'); else echo '0'; ?>" size="10"><br />
      <?php echo elgg_echo("videoconsultation:availability_descr"); ?><br /> <br />

			<br />
<?php
echo elgg_view('input/submit', array(
	'value' => elgg_echo('save'),
	'name' => 'save',
));
?>
	</form>
</div>
