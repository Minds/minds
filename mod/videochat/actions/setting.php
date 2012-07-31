<?php

	/**
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 */


	// Make sure we're logged in (send us to the front page if not)
		if (!isloggedin()) forward();

		$rtmp_server = get_input('rtmp_server');
		$rtmp_amf = get_input('rtmp_amf');
		$rtmfp_server = get_input('rtmfp_server');
		$snapshotsTime = get_input('snapshotsTime');
		$camMaxBandwidth = get_input('camMaxBandwidth');
		$bufferFullPlayback = get_input('bufferFullPlayback');
		$bufferLivePlayback = get_input('bufferLivePlayback');
		$bufferFull = get_input('bufferFull');
		$bufferLive = get_input('bufferLive');
		$disableBandwidthDetection = get_input('disableBandwidthDetection');
		$disableUploadDetection = get_input('disableUploadDetection');
		$limitByBandwidth = get_input('limitByBandwidth');
		$adsTimeout = get_input('adsTimeout');
		$adsInterval = get_input('adsInterval');
		$adServer = get_input('adServer');
		$visitor = get_input('visitor');
		$availability = get_input('availability');
		$serverProxy = get_input('serverProxy');

		datalist_set('vchat_rtmp_server',$rtmp_server);
		datalist_set('vchat_rtmp_amf',$rtmp_amf);
		datalist_set('vchat_rtmfp_server',$rtmfp_server);
		datalist_set('vchat_snapshotsTime',$snapshotsTime);
		datalist_set('vchat_camMaxBandwidth',$camMaxBandwidth);
		datalist_set('vchat_bufferFullPlayback',$bufferFullPlayback);
		datalist_set('vchat_bufferLivePlayback',$bufferLivePlayback);
		datalist_set('vchat_bufferFull',$bufferFull);
		datalist_set('vchat_bufferLive',$bufferLive);
		datalist_set('vchat_disableBandwidthDetection',$disableBandwidthDetection);
		datalist_set('vchat_disableUploadDetection',$disableUploadDetection);
		datalist_set('vchat_limitByBandwidth',$limitByBandwidth);
		datalist_set('vchat_adsTimeout',$adsTimeout);
		datalist_set('vchat_adsInterval',$adsInterval);
		datalist_set('vchat_adServer',$adServer);
		datalist_set('vchat_visitor',$visitor);
		datalist_set('vchat_availability',$availability);
		datalist_set('vchat_serverProxy',$serverProxy);

	// Success message
			system_message(elgg_echo("videochat:settingsaved"));

  // Forward
  $ver=explode('.', get_version(true));	
  if ($ver[1]>7) 	forward("videochat/settings");
  else		forward("pg/videochat/settings");

?>
