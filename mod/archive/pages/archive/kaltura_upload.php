<?php
/**
 * Contribution wizard page
 */
 forward('archive/unavailable');
 exit;
elgg_set_context('upload');
elgg_load_library('archive:kaltura');
elgg_load_library('archive:kaltura:editor');

global $KALTURA_GLOBAL_UICONF;

$user_guid = elgg_get_logged_in_user_guid();
$container_guid = $user_guid;
if($page_owner = elgg_get_page_owner_entity()) {
	if($page_owner instanceof ElggGroup) {
		$container_guid = $page_owner->getGUID();
	}
}
$kmodel = KalturaModel::getInstance();
$ks = $kmodel->getClientSideSession();

//create the elgg object
///$ob = kaltura_update_object($mixEntry,null,ACCESS_PRIVATE,$user_guid,$container_guid);
//add to the river
$viewData = array();

$kcw_uid = elgg_get_plugin_setting('custom_kcw',"archive");

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


$body = elgg_view_layout("one_column", array(
					'content' => $widget, 
					'sidebar' => false,
					'filter_override' => elgg_view('page/layouts/content/archive_filter', $vars),
					'title' => elgg_echo('archive:upload:videoaudio')
					));
					
// Display page
echo elgg_view_page(elgg_echo('minds:archive:upload:videoaudio'),$body);
