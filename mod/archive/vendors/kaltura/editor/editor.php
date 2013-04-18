<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

require_once(dirname(dirname(__FILE__))."/api_client/includes.php");
global $KALTURA_GLOBAL_UICONF;

if(!extension_loaded('curl')) {
	echo kaltura_get_error_page('',elgg_echo('kalturavideo:error:curl'));
	die;
}

$entryId = @$_REQUEST['entryId'];

if (!$entryId) {
	$error = elgg_echo('kalturavideo:error:noid');
}

if(elgg_get_plugin_setting("alloweditor","kaltura_video") == 'no') {
	$error = elgg_echo('kalturavideo:alloweditor:notallowed');
}

$ob = kaltura_get_entity($entryId);
$metadata = kaltura_get_metadata($ob);

if(!$error) {
	try {
		//check if user is allowed to edit this video
		if($metadata->kaltura_video_cancollaborate) {
			if($uob = get_user($ob->owner_guid)) {
				//change the user ID, for the creator of the video
				$user_ID = KALTURA_ELGG_USER_PREFIX.$uob->username;
				if($ob->container_guid != $user_guid)
					$user_ID .= ":".$ob->container_guid;
			}
		}

		//get the current session
		$kmodel = KalturaModel::getInstance();
		$entry = $kmodel->getEntry($entryId);
		$ks = $kmodel->getClientSideSession("edit:*");

		$kse = elgg_get_plugin_setting('defaulteditor',"kaltura_video");
		if($kse == 'custom') $kse_uid = elgg_get_plugin_setting('custom_kse',"kaltura_video");
		else {
			$t = elgg_get_plugin_setting('kaltura_server_type',"kaltura_video");
			if(empty($t)) $t = 'corp';
			$editors = $KALTURA_GLOBAL_UICONF['kse'][$t];
			$kswf = $editors[$kse];
			if(empty($kswf)) $kswf = current($editors);
			$kse_uid = $kswf['uiConfId'];
		}

                $viewData["swfUrl"] 	= KalturaHelpers::getSimpleEditorUrl($kse_uid);
		$viewData["flashVars"] 	= KalturaHelpers::getSimpleEditorFlashVars($ks, $entryId);

		if($metadata->kaltura_video_cancollaborate || $metadata->kaltura_video_editable) {
			//create the widget if not exists
			kaltura_update_object($entry,$kmodel,null,null,null,true);
		}
		else $error = elgg_echo('kalturavideo:edit:notallowed');

	}
	catch(Exception $e) {
		$error = $e->getMessage();
	}
}

if($error) {
	echo kaltura_get_error_page('',$error);
	die;
}

$width = 890;
$height = 546;
$flashVarsStr = KalturaHelpers::flashVarsToString($viewData["flashVars"]);

$editor = '<object id="kaltura_contribution_wizard" type="application/x-shockwave-flash" allowScriptAccess="always" allowNetworking="all" height="' . $height . '" width="' . $width . '" data="'.$viewData["swfUrl"] . '">'.
	'<param name="allowScriptAccess" value="always" />'.
	'<param name="allowNetworking" value="all" />'.
	'<param name="bgcolor" value=#000000 />'.
	'<param name="movie" value="'.$viewData["swfUrl"] . '"/>'.
	'<param name="flashVars" value="' . $flashVarsStr . '" />' .
'</object>';

echo $editor;
?>
<script type='text/javascript'>
	var entryId = "<?php echo $entryId; ?>";
</script>
