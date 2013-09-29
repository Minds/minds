<?php
/**
 * Created by Roy Cohen.
 * User: root
 * Date: 7/22/13
 * Time: 11:39 AM
 * This action should be used in order to list all Albums of loged in user.
 */

elgg_load_library('archive:kaltura');
elgg_load_library('tidypics:upload');

$kmodel = KalturaModel::getInstance();

$ks = $kmodel->getClientSideSession();
$serviceUrl = elgg_get_plugin_setting('kaltura_server_url', 'archive');
$kaltura = new stdClass();
$kaltura->ks = $ks;
$kaltura->serviceUrl = $serviceUrl;


echo json_encode($kaltura);
exit;