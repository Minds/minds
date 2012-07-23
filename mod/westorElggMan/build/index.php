<?php
	// Load Elgg engine
	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");

	$adminOnlyOption = westorElggMan_get_plugin_setting('adminOnlyOption', 'westorElggMan');

	$isAdmin = method_exists ( $_SESSION['user'] , "isAdmin" ) ? $_SESSION['user']->isAdmin() : ($_SESSION['user']->admin || $_SESSION['user']->siteadmin);

	if (westorElggMan_isloggedin() && $_SESSION['user']->username  ) {
    if ($adminOnlyOption == 'yes' && ! $isAdmin) {
			$body = '<br><br>' . elgg_echo('ElggMan_:adminError') . '<br><br>';
		} else {
			$body = '<script type="text/javascript">';
			$body .= 'var ElggMan_service_url="' . $CONFIG->url . 'mod/westorElggMan/services/";';
			$body .= 'var ElggMan_service_name="qooxdoo.elggMan";';
			$body .= 'var ElggMan_mod_dir="' . $CONFIG->url . 'mod/westorElggMan/";';
			$body .= 'var ElggMan_uid="' . $_SESSION['user']->username .'";';
			$body .= 'var ElggMan_Theme="' . (westorElggMan_get_plugin_setting('theme', 'westorElggMan') ? westorElggMan_get_plugin_setting('theme', 'westorElggMan') : "dark") .'";';
			$body .= 'var ElggMan_PluginWidth=' . (westorElggMan_get_plugin_setting('pluginWidth', 'westorElggMan') ? westorElggMan_get_plugin_setting('pluginWidth', 'westorElggMan') : '920') .';';
      $body .= 'var ElggMan_PollingInterval=' . (westorElggMan_get_plugin_setting('pollingInterval', 'westorElggMan') ? westorElggMan_get_plugin_setting('pollingInterval', 'westorElggMan') : '20') .';';
			//			$body .= 'var ElggMan_PluginWidth="980";';


			// if ($_SESSION['user']->admin || $_SESSION['user']->siteadmin) {  // this is only a display option, security will be checked at backend
      if ($isAdmin) {
				$body .= 'var ElggMan_Admin=true;';
			} else {
				$body .= 'var ElggMan_Admin=false;';
			}

			if (westorElggMan_get_plugin_setting('allowSendToAllOption', 'westorElggMan') == 'no') {
				$body .= 'var ElggMan_SendToAll=false;';
			} else {
				$body .= 'var ElggMan_SendToAll=true;';
			}

			// all language specific fields
			foreach($CONFIG->translations["en"] as $key=>$val){
				if (substr($key,0,8) == 'ElggMan:') {
					$body .= 'var ' . str_replace(':','_',$key) . '="'.elgg_echo($key).'";';
				}
			}
			$body .= '</script>';
			$body .= '<script type="text/javascript" src="gzqx.php"></script>';

			// Fallback, if browser accepts gz, but firewall filters compressed content
			$body .= '<script type="text/javascript">';
			$body .= 'if( typeof(qx) == "undefined" ) {';
			$body .= 'document.write("<SCR" + "IPT LANGUAGE=\'JavaScript\' SRC=\'script/westorelggman.js\' TYPE=\'text/javascript\'><\/SCR" + "IPT>");';
			$body .= '}';
			$body .= '</script>';
			$body .= '<div id="content_area_user_title"><h2>' . elgg_echo('ElggMan_:intro') . '</h2></div>';
			$body .= '
<div id="qxElement" style="margin:10px 0"><br>
<img src="'.$CONFIG->wwwroot.'mod/westorElggMan/graphics/indicator_waitanim.gif" alt="wait" width="16" height="16" hspace="7" border="0" align="left" style="margin-left:20px"> &nbsp; ' . elgg_echo('ElggMan_:loading') . '
</div>
';

		}

	} else {
		$body = '<br><br>' . elgg_echo('ElggMan_:sessionError') . '<br><br>';

	}
	westorElggMan_page_draw("ElggMan",$body);
?>