<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

	require_once($CONFIG->pluginspath."kaltura_video/kaltura/api_client/includes.php");
	// Make sure we're logged as admin
	admin_gatekeeper();
	// Make sure action is secure
	action_gatekeeper();

	// Get input data
	$type = get_input('type');

	//partner wizard is not handled here
	if($type == 'server') {
		$server_type = get_input('kaltura_server_type');
		$server_url = get_input('kaltura_server_url');
		$partnerId = get_input('partner_id');
		$email = get_input('email');
		$password = get_input('password');

		$error = '';

		if ($server_type) {
			set_plugin_setting("kaltura_server_type",$server_type,"kaltura_video");
			set_plugin_setting("kaltura_server_url",$server_url,"kaltura_video");
		}
		if ($partnerId && $email && $password) {
			set_plugin_setting("partner_id",$partnerId,"kaltura_video");
			set_plugin_setting("email",$email,"kaltura_video");
			set_plugin_setting("password","","kaltura_video");
			//password will be registered if Kaltura login is ok

			$partner = new KalturaPartner();

			try {
				$kmodel = KalturaModel::getInstance();
				$partner = $kmodel->getSecrets($partnerId, $email, $password);

				$partnerId = $partner->id;
				$subPartnerId = $partnerId * 100;
				$secret = $partner->secret;
				$adminSecret = $partner->adminSecret;
				$cmsUser = $partner->adminEmail;

				//Register Elgg vars
				set_plugin_setting("user",$cmsUser,"kaltura_video");
				set_plugin_setting("password",$password,"kaltura_video");
				set_plugin_setting("subp_id", $subPartnerId,"kaltura_video");
				set_plugin_setting("secret", $secret,"kaltura_video");
				set_plugin_setting("admin_secret", $adminSecret,"kaltura_video");

				system_message(elgg_echo("kalturavideo:registeredok"));
				forward(get_config('url')."pg/kaltura_video_admin/?type=$type");
			}
			catch(Exception $e) {
				$error = $e->getMessage();
				
			}
		}
		else {
			$error = elgg_echo("kalturavideo:mustenterfields");
		}
		if($error) {
			register_error($error);
			forward(get_config('url')."pg/kaltura_video_admin/?type=$type");
		}
	}
	elseif($type == 'custom') {
		$defaultplayer = get_input('defaultplayer');
		$defaulteditor = get_input('defaulteditor');
		$defaultkcw = get_input('defaultkcw');
		$custom_kdp = trim(get_input('custom_kdp'));
		$custom_kcw = trim(get_input('custom_kcw'));
		$custom_kse = trim(get_input('custom_kse'));

		$ok = true;
		if($defaultplayer=='custom') {
			//check the uid_conf
			if(empty($custom_kdp)) {
				$ok = false;
				register_error(elgg_echo("kalturavideo:error:uiconf"));
			}
			else {
				//check if exists
				try {
					//open the kaltura instance
					$kmodel = KalturaModel::getInstance();
					//check the uiconf
					$result = $kmodel->getUiConf($custom_kdp);
					//if no exception it's ok
					set_plugin_setting("custom_kdp",$custom_kdp,"kaltura_video");
					if($result->width && $result->height) {
						set_plugin_setting("custom_kdp_width",$result->width,"kaltura_video");
						set_plugin_setting("custom_kdp_height",$result->height,"kaltura_video");
					}
				}
				catch(Exception $e) {
					$ok = false;
					$error = $e->getMessage();
					register_error(elgg_echo("kalturavideo:error:uiconf")." $error");
				}
			}
		}
		if($defaultkcw=='custom' && $ok) {
			//check the uid_conf
			if(empty($custom_kcw)) {
				$ok = false;
				register_error(elgg_echo("kalturavideo:error:uiconf"));
			}
			else {
				//check if exists
				try {
					//open the kaltura instance
					$kmodel = KalturaModel::getInstance();
					//check the uiconf
					$result = $kmodel->getUiConf($custom_kcw);
					//if no exception it's ok
					set_plugin_setting("custom_kcw",$custom_kcw,"kaltura_video");
				}
				catch(Exception $e) {
					$ok = false;
					$error = $e->getMessage();
					register_error(elgg_echo("kalturavideo:error:uiconf")." $error");
				}
			}
		}
		if($defaulteditor=='custom' && $ok) {
			//check the uid_conf
			if(empty($custom_kse)) {
				$ok = false;
				register_error(elgg_echo("kalturavideo:error:uiconf"));
			}
			else {
				//check if exists
				try {
					//open the kaltura instance
					$kmodel = KalturaModel::getInstance();
					//check the uiconf
					$result = $kmodel->getUiConf($custom_kse);
					//if no exception it's ok
					set_plugin_setting("custom_kse",$custom_kse,"kaltura_video");
				}
				catch(Exception $e) {
					$ok = false;
					$error = $e->getMessage();
					register_error(elgg_echo("kalturavideo:error:uiconf")." $error");
				}
			}
		}
		if($ok) {
			set_plugin_setting("defaultplayer",$defaultplayer,"kaltura_video");
			set_plugin_setting("defaulteditor",$defaulteditor,"kaltura_video");
			set_plugin_setting("defaultkcw",$defaultkcw,"kaltura_video");
			system_message(elgg_echo("kalturavideo:playerupdated"));
		}
	}
	elseif($type == 'behavior') {
		$addbutton = get_input('addbutton');
		$alloweditor = get_input('alloweditor');
		$enablerating = get_input('enablerating');
		$enableindexwidget = get_input('enableindexwidget');
		$numindexvideos = get_input('numindexvideos');

		$ok = '';
		if($addbutton) $ok = set_plugin_setting("addbutton",$addbutton,"kaltura_video");
		if($alloweditor && $ok) $ok =set_plugin_setting("alloweditor",$alloweditor,"kaltura_video");
		if($enablerating && $ok) $ok =set_plugin_setting("enablerating",$enablerating,"kaltura_video");
		if($enableindexwidget && $ok) $ok =set_plugin_setting("enableindexwidget",$enableindexwidget,"kaltura_video");
		if($numindexvideos && $ok) $ok =set_plugin_setting("numindexvideos",$numindexvideos,"kaltura_video");

		if($ok) system_message(elgg_echo("admin:configuration:success"));
		else register_error(elgg_echo("admin:configuration:fail"));
	}

	//by default return and do nothing
	forward(get_config('url')."pg/kaltura_video_admin/?type=$type");

?>
