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
$adsTimeout = get_input('adsTimeout');
$adsInterval = get_input('adsInterval');
$statusInterval = get_input('statusInterval');
$ws_ads = get_input('ws_ads');
$floodProtection = get_input('floodProtection');
$enableRTMP = get_input('enableRTMP');
$enableP2P = get_input('enableP2P');
$supportRTMP = get_input('supportRTMP');
$supportP2P = get_input('supportP2P');
$alwaysRTMP = get_input('alwaysRTMP');
$alwaysP2P = get_input('alwaysP2P');
$visitor = get_input('visitor');
$cleanUp = get_input('cleanUp');

datalist_set('vconf_rtmp_server',$rtmp_server);
datalist_set('vconf_rtmp_amf',$rtmp_amf);
datalist_set('vconf_rtmfp_server',$rtmfp_server);
datalist_set('vconf_p2pGroup',$p2pGroup);
datalist_set('vconf_camMaxBandwidth',$camMaxBandwidth);
datalist_set('vconf_bufferFullPlayback',$bufferFullPlayback);
datalist_set('vconf_bufferLivePlayback',$bufferLivePlayback);
datalist_set('vconf_bufferFull',$bufferFull);
datalist_set('vconf_bufferLive',$bufferLive);
datalist_set('vconf_disableBandwidthDetection',$disableBandwidthDetection);
datalist_set('vconf_disableUploadDetection',$disableUploadDetection);
datalist_set('vconf_limitByBandwidth',$limitByBandwidth);
datalist_set('vconf_adsTimeout',$adsTimeout);
datalist_set('vconf_adsInterval',$adsInterval);
datalist_set('vconf_statusInterval',$statusInterval);
datalist_set('vconf_ws_ads',$ws_ads);
datalist_set('vconf_floodProtection',$floodProtection);
datalist_set('vconf_enableRTMP',$enableRTMP);
datalist_set('vconf_enableP2P',$enableP2P);
datalist_set('vconf_supportRTMP',$supportRTMP);
datalist_set('vconf_supportP2P',$supportP2P);
datalist_set('vconf_alwaysRTMP',$alwaysRTMP);
datalist_set('vconf_alwaysP2P',$alwaysP2P);
datalist_set('vconf_visitor',$visitor);
datalist_set('vconf_cleanUp',$cleanUp);

// Success message
system_message(elgg_echo("videoconference:settingsaved"));

// Forward
$ver=explode('.', get_version(true));	
if ($ver[1]>7) 	forward("videoconference/settings");
else		forward("pg/videoconference/settings");


?>
