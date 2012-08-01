<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

class KalturaModel {
	var $session;

    function __construct()
    {
		$config = KalturaHelpers::getKalturaConfiguration();
        $this->client = &new KalturaClient($config); // & is needed because of the subclasses inititailzation inside the KalturaClient constructor
    }
	function getInstance()
    {
        static $instance = null;

        if ($instance == null)
        {
            $instance = &new KalturaModel();
        }

        return $instance;
    }

    function startSession()
    {
    	$ks = $this->getAdminSession("edit:*");
		$this->client->setKs($ks);
		$this->session = $ks;
    }

	function getAdminSession($privileges = "")
    {
        $userId = KalturaHelpers::getLoggedUserId();
        $partnerId = elgg_get_plugin_setting('partner_id', 'kaltura_video');
        $secret = elgg_get_plugin_setting('admin_secret', 'kaltura_video');
		return $this->client->session->start($secret, $userId, KalturaSessionType_ADMIN, $partnerId, 86400, $privileges);
    }

    function getClientSideSession($privileges = "")
    {
        $userId = KalturaHelpers::getLoggedUserId();
        $partnerId = elgg_get_plugin_setting('partner_id', 'kaltura_video');
        $secret = elgg_get_plugin_setting('secret', 'kaltura_video');
		return $this->client->session->start($secret, $userId, KalturaSessionType_USER, $partnerId, 86400, $privileges);
    }

    function getSecrets($partnerId, $email, $password)
    {
        return $this->client->partner->getSecrets($partnerId, $email, $password);
    }

	function getUiConf($uiConf)
	{
		if (!$this->session)
			$this->startSession();

		return $this->client->uiConf->get($uiConf);
	}

	function getEntry($entryId)
	{
		if (!$this->session)
			$this->startSession();

		return $this->client->baseEntry->get($entryId);
	}

	function updateMediaEntry($mediaEntryId, $mediaEntry)
	{
		if (!$this->session)
			$this->startSession();

		return $this->client->media->update($mediaEntryId, $mediaEntry);
	}

    function updateMixEntry($mixEntryId, $mediaEntry)
	{
		if (!$this->session)
			$this->startSession();

		return $this->client->mixing->update($mixEntryId, $mediaEntry);
	}

    function addMixEntry($mixEntry)
	{
		if (!$this->session)
			$this->startSession();

		return $this->client->mixing->add($mixEntry);
	}

	function appendMediaToMix($mixEntryId, $mediaEntryId)
	{
	    if (!$this->session)
			$this->startSession();

		return $this->client->mixing->appendMediaEntry($mixEntryId, $mediaEntryId);
	}

	function listMixEntries($pageSize, $page)
	{
		if (!$this->session)
			$this->startSession();

		$filter = new KalturaBaseEntryFilter();
		$filter->orderBy = "-createdAt";

		$pager = new KalturaFilterPager();
		$pager->pageSize = $pageSize;
		$pager->pageIndex = $page;

		return $this->client->mixing->listAction($filter, $pager);
	}

	function listMixMediaEntries($mixEntryId)
	{
		if (!$this->session)
			$this->startSession();

		return $this->client->mixing->getReadyMediaEntries($mixEntryId);
	}

	function listUiConf($pageSize, $page)
	{
		if (!$this->session)
			$this->startSession();

		$filter = new KalturaBaseEntryFilter();
		$filter->orderBy = "-createdAt";

		$pager = new KalturaFilterPager();
		$pager->pageSize = $pageSize;
		$pager->pageIndex = $page;

		return $this->client->uiConf->listAction($filter, $pager);
	}
	function listEntries($pageSize, $page)
	{
		if (!$this->session)
			$this->startSession();

		$filter = new KalturaBaseEntryFilter();
		$filter->orderBy = "-createdAt";

		$pager = new KalturaFilterPager();
		$pager->pageSize = $pageSize;
		$pager->pageIndex = $page;

		return $this->client->baseEntry->listAction($filter, $pager);
	}
	function listWidgets($pageSize, $page,$uiConfIdEqual=null,$entryIdEqual=null)
	{
		if (!$this->session)
			$this->startSession();

		$filter = new KalturaBaseEntryFilter();
		$filter->orderBy = "-createdAt";
		if($uiConfIdEqual) {
			$filter->uiConfIdEqual = $uiConfIdEqual;
		}
		if($entryIdEqual) {
			$filter->entryIdEqual = $entryIdEqual;
		}
		$pager = new KalturaFilterPager();
		$pager->pageSize = $pageSize;
		$pager->pageIndex = $page;

		return $this->client->widget->listAction($filter, $pager);
	}

	function deleteEntry($mediaEntryId)
	{
		if (!$this->session)
			$this->startSession();

		return $this->client->baseEntry->delete($mediaEntryId);
	}
	function addWidget($entryId, $uiConfId)
	{
		if (!$this->session)
			$this->startSession();

		$widget = new KalturaWidget();
		$widget->entryId = $entryId;
		$widget->uiConfId = $uiConfId;
		return $this->client->widget->add($widget);
	}

	function getWidget($widgetId)
	{
		if (!$this->session)
			$this->startSession();

		return $this->client->widget->get($widgetId);
	}
    function pingTest()
	{
		return $this->client->system->ping();
	}

	function registerPartner($partner)
	{
	    return $this->client->partner->register($partner);
	}

	function addMediaEntry($mediaEntry, $fileData)
	{
	    if (!$this->session)
			$this->startSession();

		$fileKey = $this->client->media->upload($fileData);
		$mediaEntry = $this->client->media->addFromUploadedFile($mediaEntry, $fileKey);
		return $mediaEntry;
	}
	
	/*Get flavour info */
	 function getflavourAssets($entryId)
    {
        return $this->client->flavorAsset->getByEntryId($entryId);
    }
}

class KalturaHelpers
{
    function getKalturaConfiguration()
    {
		$partner_id = elgg_get_plugin_setting('partner_id', 'kaltura_video');
		if($partner_id) $config = new KalturaConfiguration($partner_id);
		else  $config = new KalturaConfiguration();
    	$config->serviceUrl = KalturaHelpers::getServerUrl();
    	return $config;
    }
	function getServerUrl()
    {

		$type = elgg_get_plugin_setting('kaltura_server_type', 'kaltura_video');
		$url = elgg_get_plugin_setting('kaltura_server_url', 'kaltura_video');
    	if(empty($url) || $type=='corp' || empty($type)) $url = KALTURA_SERVER_URL;

    	// remove the last slash from the url
    	if (substr($url, strlen($url) - 1, 1) == '/')
    		$url = substr($url, 0, strlen($url) - 1);

    	return $url;
    }


    function getCdnUrl()
    {
    	$url = KALTURA_CDN_URL;

    	// remove the last slash from the url
    	if (substr($url, strlen($url) - 1, 1) == '/')
    		$url = substr($url, 0, strlen($url) - 1);

    	return $url;
    }

    function getLoggedUserId()
    {
    	global $user_ID;

    	if (!$user_ID)
    		return KALTURA_ANONYMOUS_USER_ID;
    	else
        	return $user_ID;
    }

	function getContributionWizardFlashVars($ks, $entryId = null)
	{
		$flashVars = array();
		$flashVars["userId"] 		= KalturaHelpers::getLoggedUserId();
		$flashVars["sessionId"] 	= $ks;
		$flashVars["partnerId"] 	= elgg_get_plugin_setting("partner_id","kaltura_video");
		$flashVars["subPartnerId"] 	= elgg_get_plugin_setting("subp_id", "kaltura_video");
		if ($entryId)
		    $flashVars["kshowId"] 		= "entry-".$entryId;
		else
		    $flashVars["kshowId"] 		= "-2";
		$flashVars["afterAddentry"] = "renameAndEditMixVideo";
		if(in_array(elgg_get_plugin_setting("alloweditor","kaltura_video"), array('simple','no'))) {
			$flashVars["afterAddentry"] = "onEditorSave";
		}
		$flashVars["close"] 		= "deleteVideo";
		$flashVars["termsOfUse"] 	= "http://corp.kaltura.com/static/tandc" ;
		//$flashVars["lang"] 	= $_SESSION['user']->language ;

		return $flashVars;
	}
	function getSimpleEditorFlashVars($ks, $entryId)
	{
		$flashVars = array();
		$flashVars["entryId"] 		= $entryId;
		$flashVars["kshowId"] 		= "entry-".$entryId;
		$flashVars["partnerId"] 	= elgg_get_plugin_setting("partner_id","kaltura_video");
		$flashVars["subPartnerId"] 	= elgg_get_plugin_setting("subp_id", "kaltura_video");
		$flashVars["subpId"]     	= elgg_get_plugin_setting("subp_id", "kaltura_video");
		$flashVars["uid"] 		    = KalturaHelpers::getLoggedUserId();
		$flashVars["ks"] 			= $ks;
		$flashVars["backF"] 		= "onEditorBack";
		$flashVars["saveF"] 		= "onEditorSave";
		//$flashVars["lang"] 	= $_SESSION['user']->language ;

		return $flashVars;
	}

	function getKalturaPlayerFlashVars($uiConfId = null, $ks = null, $entryId = null)
	{
		$flashVars = array();
		$flashVars["partnerId"] 	= elgg_get_plugin_setting("partner_id","kaltura_video");
		$flashVars["subPartnerId"] 	= elgg_get_plugin_setting("subp_id", "kaltura_video");
		$flashVars["uid"] 		    = KalturaHelpers::getLoggedUserId();

		if ($ks)
		    $flashVars["ks"] 		= $ks;
	    if ($uiConfId)
	        $flashVars["uiConfId"] 	= $ks;
        if ($entryId)
            $flashVars["entryId"] 	= $entryId;

		return $flashVars;
	}

	function flashVarsToString($flashVars)
	{
		$flashVarsStr = "";
		foreach($flashVars as $key => $value)
		{
			$flashVarsStr .= ($key . "=" . $value . "&");
		}
		return substr($flashVarsStr, 0, strlen($flashVarsStr) - 1);
	}

	function getPlayers()
	{
		global $KALTURA_GLOBAL_UICONF;
		$t = elgg_get_plugin_setting('kaltura_server_type',"kaltura_video");
		if(empty($t)) $t = 'corp';
		return $KALTURA_GLOBAL_UICONF['kdp'][$t];
	}

	function getPlayerByType($type)
	{
		$players = KalturaHelpers::getPlayers();
		if (array_key_exists($type, $players))
		{
			$player = $players[$type];
		}
		else
		{
			//$player = $players[elgg_get_plugin_setting("kaltura_default_player_type","kaltura_video")];
			$player = $players['grey'];
		}

		return $player;
	}

	function calculatePlayerHeight($type, $width, $playerRatio = "4:3")
	{
		$player = KalturaHelpers::getPlayerByType($type);

		$aspectRatio = $playerRatio;
		$hSpacer = (@$player["horizontalSpacer"] ? $player["horizontalSpacer"] : 0);
		$vSpacer = (@$player["verticalSpacer"] ? $player["verticalSpacer"] : 0);

		switch($aspectRatio)
		{
			case "4:3":
				$screenHeight = ($width - $hSpacer) / 4 * 3;
				$height = $screenHeight + $vSpacer;
				break;
			case "16:9":
				$screenHeight = ($width - $hSpacer) / 16 * 9;
				$height = $screenHeight + $vSpacer;
				break;
		}

		return round($height);
	}

	function getSwfUrlForBaseWidget($uiConf)
	{
		$player = KalturaHelpers::getPlayerByType($type);
		if(empty($uiConf)) $uiConf = $player["uiConfId"];
		return KalturaHelpers::getServerUrl() . "/index.php/kwidget/wid/_" . elgg_get_plugin_setting("partner_id","kaltura_video") . "/ui_conf_id/" . $uiConf;
	}

	function getSwfUrlForWidget($widgetId = null, $uiConfId = null)
	{
	    if (!$widgetId)
	        $widgetId = "_" . elgg_get_plugin_setting("partner_id","kaltura_video");

	  	$player = KalturaHelpers::getPlayerByType($type);
		if(empty($uiConf)) $uiConf = $player["uiConfId"];
		return KalturaHelpers::getServerUrl() . "/index.php/kwidget/wid/_" . elgg_get_plugin_setting("partner_id","kaltura_video") . "/ui_conf_id/" . $uiConf;

	}

	function getContributionWizardUrl($uiConfId)
	{
		return KalturaHelpers::getServerUrl() . "/kcw/ui_conf_id/" . $uiConfId;
	}

	function getSimpleEditorUrl($uiConfId)
	{
		return KalturaHelpers::getServerUrl() . "/kse/ui_conf_id/" . $uiConfId;
	}
}
?>
