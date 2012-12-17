<?php
/**
 * Contribution wizard page
 */
elgg_set_context('upload');
require_once(dirname(dirname(dirname(__FILE__)))."/kaltura/api_client/includes.php");
require_once(dirname(dirname(dirname(__FILE__)))."/kaltura/editor/init.php");
global $KALTURA_GLOBAL_UICONF;

if(!extension_loaded('curl')) {
	echo kaltura_get_error_page('',elgg_echo('kalturavideo:error:curl'));
	die;
}

$user_guid = $_SESSION['user']->getGUID();
$container_guid = $user_guid;
if($page_owner = elgg_get_page_owner_entity()) {
	if($page_owner instanceof ElggGroup) {
		$user_guid = $_SESSION['user']->getGUID();
		$container_guid = $page_owner->getGUID();
	}
}
$kmodel = KalturaModel::getInstance();
$ks = $kmodel->getClientSideSession();
/*
try {
	//get the current session
	
	$ks = $kmodel->getClientSideSession();

	$mixEntry = new KalturaMixEntry();
	$mixEntry->name = elgg_echo('kalturavideo:title:video');
    $mixEntry->editorType = KalturaEditorType_SIMPLE;
    $mixEntry->adminTags = KALTURA_ADMIN_TAGS;
    $mixEntry = $kmodel->addMixEntry($mixEntry);
	$entryId = $mixEntry->id;
}
catch(Exception $e) {
	$error = $e->getMessage();
}

if (!$entryId && !$error) {
	$error = elgg_echo('kalturavideo:error:noid');
}
*/
if($error) {
	echo kaltura_get_error_page('',$error);
	die;
}
else {

	//create the elgg object
	///$ob = kaltura_update_object($mixEntry,null,ACCESS_PRIVATE,$user_guid,$container_guid);
	//add to the river
	$viewData = array();

	$kcw = elgg_get_plugin_setting('defaultkcw',"kaltura_video");
	if($kcw == 'custom') $kcw_uid = elgg_get_plugin_setting('custom_kcw',"kaltura_video");
	else {
		$t = get_plugin_setting('kaltura_server_type',"kaltura_video");
		if(empty($t)) $t = 'corp';
		$editors = $KALTURA_GLOBAL_UICONF['kcw'][$t];
		$kswf = $editors[$kcw];
		if(empty($kswf)) $kswf = current($editors);
		$kcw_uid = $kswf['uiConfId'];
	}

	$viewData["flashVars"] 	= KalturaHelpers::getContributionWizardFlashVars($ks);
    //$viewData["flashVars"]["showCloseButton"] 	= "false";
    $viewData["swfUrl"]    	= KalturaHelpers::getContributionWizardUrl($kcw_uid);
    //$viewData["entryId"] = $entryId;
    //$viewData["flashVars"]["kshowId"] = "entry-".$entryId;

    $flashVarsStr = KalturaHelpers::flashVarsToString($viewData["flashVars"]);

	$height = 360;
	$width = 680;

	$widget = '<object id="kaltura_contribution_wizard" type="application/x-shockwave-flash" allowScriptAccess="always" allowNetworking="all" height="400px" width="990px" data="'. $viewData["swfUrl"] . '">'.
		'<param name="allowScriptAccess" value="always" />'.
		'<param name="allowNetworking" value="all" />'.
		'<param name="bgcolor" value=#FFF />'.
		'<param name="movie" value="'.$viewData["swfUrl"] . '"/>'.
    	'<param name="flashVars" value="' . $flashVarsStr . '" />' .
	'</object>';
}

$body = elgg_view_layout("one_column", array(
					'content' => $widget, 
					'sidebar' => false,
					'filter_override' => elgg_view('page/layouts/content/archive_filter', $vars),
					'title' => elgg_echo('archive:upload:videoaudio')
					));
					
// Display page
echo elgg_view_page(elgg_echo('archive:upload:videoaudio'),$body);
