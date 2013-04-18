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


	if($type == 'partner_wizard') {
		$name 				= get_input('name');
		$email 				= get_input('email');
		$webSiteUrl 		= get_input('web_site_url');
		$phoneNumber 		= get_input('phone_number');
		$description 		= get_input('description');
		$contentCategory 	= get_input('content_category');
		$adultContent 		= (get_input('adult_content') == "yes" ? "1" : null);
		$agreeToTerms 		= get_input('agree_to_terms');

		$error = '';
		if( !is_email_address($email) ) {
			$error = elgg_echo('registration:notemail');
		}

		if ($agreeToTerms && empty($error)) {

			$partner = new KalturaPartner();
			$partner->name = $name;
			$partner->adminName = $name;
			$partner->adminEmail = $email;
			$partner->website = $webSiteUrl;
			$partner->phone = $phoneNumber;
			$partner->description = $description . "\Kaltura Elgg plugin|Elgg " . get_version(true);
			$partner->contentCategories = $contentCategory;
			$partner->adultContent = $adultContent;
			$partner->commercialUse = "non-commercial_use";
			$partner->type = "101";
			$partner->defConversionProfileType = "wp_default";

			try {
				$kmodel = KalturaModel::getInstance();
				$partner = $kmodel->registerPartner($partner);

				$partnerId = $partner->id;
				$subPartnerId = $partnerId * 100;
				$secret = $partner->secret;
				$adminSecret = $partner->adminSecret;
				$cmsUser = $partner->adminEmail;
				$cmsPassword = $partner->cmsPassword;

				//Register Elgg vars
				//server part
				set_plugin_setting("kaltura_server_type",'corp',"kaltura_video"); //automatic registering is only for kaltura.com
				set_plugin_setting("kaltura_server_url",'',"kaltura_video"); //default in definitions.php
				//partner part
				set_plugin_setting("partner_id",$partnerId,"kaltura_video");
				set_plugin_setting("email",$email,"kaltura_video");
				set_plugin_setting("user",$cmsUser,"kaltura_video");
				set_plugin_setting("password",$cmsPassword,"kaltura_video");
				set_plugin_setting("subp_id", $subPartnerId,"kaltura_video");
				set_plugin_setting("secret", $secret,"kaltura_video");
				set_plugin_setting("admin_secret", $adminSecret,"kaltura_video");

				system_message(elgg_echo("kalturavideo:registeredok"));
				forward(get_config('url')."pg/kaltura_video_admin/?type=server");
			}
			catch(Exception $e) {
				$error = $e->getMessage();
			}
		}
		elseif( empty($error) ) {
			$error = elgg_echo("kalturavideo:mustaggreeterms");
		}
		if($error) {
			register_error($error);
			forward(get_config('url')."pg/kaltura_video_admin/?type=$type");
		}
	}

	//by default return and do nothing
	forward(get_config('url')."pg/kaltura_video_admin/?type=$type");

?>
