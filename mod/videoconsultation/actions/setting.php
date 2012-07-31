<?php

/**
	* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	*/


// Make sure we're logged in (send us to the front page if not)
if (!isloggedin()) forward();

$rtmp_server = get_input('rtmp_server');
$rtmp_amf = get_input('rtmp_amf');
$rtmfp_server = get_input('rtmfp_server');
$p2pGroup = get_input('p2pGroup');
$camMaxBandwidth = get_input('camMaxBandwidth');
$bufferFullPlayback = get_input('bufferFullPlayback');
$bufferLivePlayback = get_input('bufferLivePlayback');
$bufferFull = get_input('bufferFull');
$bufferLive = get_input('bufferLive');
$disableBandwidthDetection = get_input('disableBandwidthDetection');
$disableUploadDetection = get_input('disableUploadDetection');
$limitByBandwidth = get_input('limitByBandwidth');
$statusInterval = get_input('statusInterval');
$floodProtection = get_input('floodProtection');
$visitor = get_input('visitor');
$availability = get_input('availability');

datalist_set('vcons_rtmp_server',$rtmp_server);
datalist_set('vcons_rtmp_amf',$rtmp_amf);
datalist_set('vcons_rtmfp_server',$rtmfp_server);
datalist_set('vcons_p2pGroup',$p2pGroup);
datalist_set('vcons_camMaxBandwidth',$camMaxBandwidth);
datalist_set('vcons_bufferFullPlayback',$bufferFullPlayback);
datalist_set('vcons_bufferLivePlayback',$bufferLivePlayback);
datalist_set('vcons_bufferFull',$bufferFull);
datalist_set('vcons_bufferLive',$bufferLive);
datalist_set('vcons_disableBandwidthDetection',$disableBandwidthDetection);
datalist_set('vcons_disableUploadDetection',$disableUploadDetection);
datalist_set('vcons_limitByBandwidth',$limitByBandwidth);
datalist_set('vcons_statusInterval',$statusInterval);
datalist_set('vcons_floodProtection',$floodProtection);
datalist_set('vcons_visitor',$visitor);
datalist_set('vcons_availability',$availability);

// Success message
system_message(elgg_echo("videoconsultation:settingsaved"));

// Forward
$ver=explode('.', get_version(true));	
if ($ver[1]>7) 	forward("videoconsultation/settings");
else		forward("pg/videoconsultation/settings");
?>
