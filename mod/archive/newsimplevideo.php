<?php

/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

	// Load Elgg engine
		require_once(dirname(__FILE__)."/kaltura/api_client/includes.php");
		gatekeeper();

	// Get the current page's owner
		if(!extension_loaded('curl')) {
			echo kaltura_get_error_page('',elgg_echo('kalturavideo:error:curl'));
			die;
		}

		$user_guid = $_SESSION['user']->getGUID();
		$container_guid = $user_guid;
		if($page_owner = page_owner_entity()) {
			if($page_owner instanceof ElggGroup) {
				$user_guid = $_SESSION['user']->getGUID();
				$container_guid = $page_owner->getGUID();
			}
		}

		//get the current session
		$kmodel = KalturaModel::getInstance();
		$ks = $kmodel->getClientSideSession();

		try {
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
		
		if($error) {
			echo kaltura_get_error_page('',$error);
			die;
		}
		else {

		    //create the elgg object
		    $ob = kaltura_update_object($mixEntry,null,ACCESS_PRIVATE,$user_guid,$container_guid);
		}

		$entity = kaltura_get_entity($mixEntry->id);

	// Display page
		$modal_view = get_input('modal_view');
		if($modal_view) {
		    $area2 = '<div style="width: 100%;"><a style="float: right;" class="close" href="#">[Close]</a></div><div class="clearfloat"></div>';
		    $area2 .= elgg_view_title(elgg_echo('kalturavideo:label:newsimplevideo'));
		    $area2 .= elgg_view("kaltura/newsimplevideo", array('entity' => $entity, 'modal_view' => 1));
		    echo $area2;
		}
		else {
		    $area2 = elgg_view_title(elgg_echo('kalturavideo:label:newsimplevideo'));
		    $area2 .= elgg_view("kaltura/newsimplevideo", array('entity' => $entity));
		    $body = elgg_view_layout("two_column_left_sidebar", '', $area2);
		    page_draw('kalturavideo:label:newsimplevideo',$body);
		}
?>
<script type="text/javascript" language="JavaScript">
function cancelAction()
{
    var entryId = '<?php echo $mixEntry->id ?>';
    if(confirm("If you leave this page without saving, all changes will be lost. Are you sure you want to leave this page?"))
    {
	$.ajax({url:'/mod/kaltura_video/ajax-update.php?delete_entry_id='+entryId, async: false});
	$(window).unbind('unload');
    }
    else
    {
	return false;
    }
}



$(document).ready(function () {
    $(window).unload(cancelAction);
    $("#kalturaPostForm").submit(function() {
	$(window).unbind('unload');
	
	return true;
    });
});
</script>
