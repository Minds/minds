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
$tokenKey = get_input('tokenKey');
$snapshotsTime = get_input('snapshotsTime');
$camMaxBandwidth = get_input('camMaxBandwidth');
$bufferFull = get_input('bufferFull');
$bufferLive = get_input('bufferLive');
$bufferFull2 = get_input('bufferFull2');
$bufferLive2 = get_input('bufferLive2');
$disableBandwidthDetection = get_input('disableBandwidthDetection');
$limitByBandwidth = get_input('limitByBandwidth');
$externalInterval = get_input('externalInterval');
$floodProtection = get_input('floodProtection');
$floodProtection2 = get_input('floodProtection2');
$generateSnapshots = get_input('generateSnapshots');
$externalInterval2 = get_input('externalInterval2');
$adsTimeout = get_input('adsTimeout');
$adsInterval = get_input('adsInterval');
$statusInterval = get_input('statusInterval');
$ws_ads = get_input('ws_ads');
$visitor = get_input('visitor');
$availability = get_input('availability');
$serverProxy = get_input('serverProxy');
$enableRTMP = get_input('enableRTMP');
$enableP2P = get_input('enableP2P');
$videoCodec = get_input('videoCodec');
$codecProfile = get_input('codecProfile');
$codecLevel = get_input('codecLevel');
$soundCodec = get_input('soundCodec');
$supportRTMP = get_input('supportRTMP');
$supportP2P = get_input('supportP2P');
$alwaysRTMP = get_input('alwaysRTMP');
$alwaysP2P = get_input('alwaysP2P'); 

datalist_set('lstr_rtmp_server',$rtmp_server);
datalist_set('lstr_rtmfp_server',$rtmfp_server);
datalist_set('lstr_p2pGroup',$p2pGroup);
datalist_set('lstr_rtmp_amf',$rtmp_amf);
datalist_set('lstr_camMaxBandwidth',$camMaxBandwidth);
datalist_set('lstr_tokenKey',$tokenKey);
datalist_set('lstr_snapshotsTime',$snapshotsTime);
datalist_set('lstr_bufferFull',$bufferFull);
datalist_set('lstr_bufferLive',$bufferLive);
datalist_set('lstr_bufferFull2',$bufferFull2);
datalist_set('lstr_bufferLive2',$bufferLive2);		
datalist_set('lstr_disableBandwidthDetection',$disableBandwidthDetection);
datalist_set('lstr_limitByBandwidth',$limitByBandwidth);
datalist_set('lstr_externalInterval',$externalInterval);
datalist_set('lstr_floodProtection',$floodProtection);
datalist_set('lstr_floodProtection2',$floodProtection2);
datalist_set('lstr_generateSnapshots',$generateSnapshots);
datalist_set('lstr_externalInterval2',$externalInterval2);
datalist_set('lstr_adsTimeout',$adsTimeout);
datalist_set('lstr_adsInterval',$adsInterval);
datalist_set('lstr_statusInterval',$statusInterval);
datalist_set('lstr_ws_ads',$ws_ads);
datalist_set('lstr_visitor',$visitor);
datalist_set('lstr_availability',$availability);
datalist_set('lstr_serverProxy',$serverProxy);
datalist_set('lstr_enableRTMP',$enableRTMP);
datalist_set('lstr_enableP2P',$enableP2P);
datalist_set('lstr_videoCodec',$videoCodec);
datalist_set('lstr_codecProfile',$codecProfile);
datalist_set('lstr_codecLevel',$codecLevel);
datalist_set('lstr_soundCodec',$soundCodec);
datalist_set('lstr_supportRTMP',$supportRTMP);
datalist_set('lstr_supportP2P',$supportP2P);
datalist_set('lstr_alwaysRTMP',$alwaysRTMP);
datalist_set('lstr_alwaysP2P',$alwaysP2P);

// Success message
system_message(elgg_echo("livestreaming:settingsaved"));

// Forward
$ver=explode('.', get_version(true));	
if ($ver[1]>7) 	forward("livestreaming/settings");
else		forward("pg/livestreaming/settings");

?>
