<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/
	global $KALTURA_GLOBAL_UICONF, $JQUERY_LIB, $KALTURA_TINYMCE_PATHS;
	//this prefix is used to identify the users of kaltura that are created in Elgg
	define("KALTURA_ELGG_USER_PREFIX", "Elgg_");
	//the admin tags for kaltura CMS, every created/modified video from Elgg will have this tags
	define("KALTURA_ADMIN_TAGS", "elgg_plugin");

	$elgg_version = get_version(true);
	//FOR ELGG 1.5 or 1.2 jquery-1.2.6.pack.js
	if(version_compare($elgg_version,"1.6")==-1) $JQUERY_LIB = 'jquery-1.2.6.pack.js';
	else $JQUERY_LIB = 'jquery-1.3.2.min.js';

	// Plugin name => path to longtext view
	// relative to $CONFIG->pluginspath
	$KALTURA_TINYMCE_PATHS = array(
		'tinymce' => "tinymce/views/default/input/longtext.php",
		'tinymce_adv' => "tinymce_adv/views/default/input/longtext.php",
		'tinymcebrowser' => "tinymcebrowser/views/default/input/longtext.php"
	);
	//no more needed to modify this vars, please go to Administration -> Kaltura Video Admin to activate KalturaCE
	define("KALTURA_SERVER_URL", "http://www.kaltura.com");
	define("KALTURA_CDN_URL", "http://cdn.kaltura.com");
	define("KALTURA_ANONYMOUS_USER_ID", "Anonymous");

	$KALTURA_GLOBAL_UICONF = array(
		"kdp" => array(
			"corp" => array(
				'light' => array("name" => "Light", "uiConfId" => 48411, "width" => 400, "height" => 335),
				'dark' => array("name" => "Dark", "uiConfId" => 48410, "width" => 400, "height" => 335)
				),//end corp
			"ce" => array(
				'light' => array("name" => "Light", "uiConfId" => 48411, "width" => 400, "height" => 335),
				'dark' => array("name" => "Dark", "uiConfId" => 48410, "width" => 400, "height" => 335)
				)//end ce
		),//end player
		"kcw" => array(
			"corp" => array(
				'light' => array("name" => "Light", "uiConfId" => 1000199),
				'dark' => array("name" => "Dark", "uiConfId" => 1000198)
				),//end corp
			"ce" => array(
				'cw1' => array("name" => "cw for KMC (680x480px)", "uiConfId" => 36202),
				)//end ce
		),//end kcw
		"kse" => array(
			"corp" => array(
				'dark' => array("name" => "Dark", "uiConfId" => 1000200),
				'light' => array("name" => "Light", "uiConfId" => 1000201)
				),//end corp
			"ce" => array(
				'se1' => array("name" => "samplekit simple editor (890x546px)", "uiConfId" => 36300)
				)//end ce
		)//end kse
	);

/* Other definitions... */
define("KalturaEditorType_SIMPLE", 1);
define("KalturaEditorType_ADVANCED", 2);

define("KalturaEntryStatus_ERROR_CONVERTING", -1);
define("KalturaEntryStatus_IMPORT", 0);
define("KalturaEntryStatus_PRECONVERT", 1);
define("KalturaEntryStatus_READY", 2);
define("KalturaEntryStatus_DELETED", 3);
define("KalturaEntryStatus_PENDING", 4);
define("KalturaEntryStatus_MODERATE", 5);
define("KalturaEntryStatus_BLOCKED", 6);

define("KalturaEntryType_AUTOMATIC", -1);
define("KalturaEntryType_MEDIA_CLIP", 1);
define("KalturaEntryType_MIX", 2);
define("KalturaEntryType_PLAYLIST", 5);
define("KalturaEntryType_DATA", 6);
define("KalturaEntryType_DOCUMENT", 10);

define("KalturaLicenseType_UNKNOWN", -1);
define("KalturaLicenseType_NONE", 0);
define("KalturaLicenseType_CC25", 1);
define("KalturaLicenseType_CC3", 2);

define("KalturaMediaType_VIDEO", 1);
define("KalturaMediaType_IMAGE", 2);
define("KalturaMediaType_AUDIO", 5);

define("KalturaNotificationType_ENTRY_ADD", 1);
define("KalturaNotificationType_ENTR_UPDATE_PERMISSIONS", 2);
define("KalturaNotificationType_ENTRY_DELETE", 3);
define("KalturaNotificationType_ENTRY_BLOCK", 4);
define("KalturaNotificationType_ENTRY_UPDATE", 5);
define("KalturaNotificationType_ENTRY_UPDATE_THUMBNAIL", 6);
define("KalturaNotificationType_ENTRY_UPDATE_MODERATION", 7);
define("KalturaNotificationType_USER_ADD", 21);
define("KalturaNotificationType_USER_BANNED", 26);

define("KalturaPlaylistType_DYNAMIC", 10);
define("KalturaPlaylistType_STATIC_LIST", 3);
define("KalturaPlaylistType_EXTERNAL", 101);

define("KalturaSearchProviderType_FLICKR", 3);
define("KalturaSearchProviderType_YOUTUBE", 4);
define("KalturaSearchProviderType_MYSPACE", 7);
define("KalturaSearchProviderType_PHOTOBUCKET", 8);
define("KalturaSearchProviderType_JAMENDO", 9);
define("KalturaSearchProviderType_CCMIXTER", 10);
define("KalturaSearchProviderType_NYPL", 11);
define("KalturaSearchProviderType_CURRENT", 12);
define("KalturaSearchProviderType_MEDIA_COMMONS", 13);
define("KalturaSearchProviderType_KALTURA", 20);
define("KalturaSearchProviderType_KALTURA_USER_CLIPS", 21);
define("KalturaSearchProviderType_ARCHIVE_ORG", 22);
define("KalturaSearchProviderType_KALTURA_PARTNER", 23);
define("KalturaSearchProviderType_METACAFE", 24);
define("KalturaSearchProviderType_SEARCH_PROXY", 28);

define("KalturaSessionType_USER", 0);
define("KalturaSessionType_ADMIN", 2);

define("KalturaSourceType_FILE", 1);
define("KalturaSourceType_WEBCAM", 2);
define("KalturaSourceType_URL", 5);
define("KalturaSourceType_SEARCH_PROVIDER", 6);

define("KalturaUiConfCreationMode_WIZARD", 2);
define("KalturaUiConfCreationMode_ADVANCED", 3);

define("KalturaUiConfObjType_PLAYER", 1);
define("KalturaUiConfObjType_CONTRIBUTION_WIZARD", 2);
define("KalturaUiConfObjType_SIMPLE_EDITOR", 3);
define("KalturaUiConfObjType_ADVANCED_EDITOR", 4);
define("KalturaUiConfObjType_PLAYLIST", 5);
define("KalturaUiConfObjType_APP_STUDIO", 6);

define("KalturaWidgetSecurityType_NONE", 1);
define("KalturaWidgetSecurityType_TIMEHASH", 2);
?>
