<?php
	/*****************************************************************************\
	+-----------------------------------------------------------------------------+
	| Elgg Socialcommerce Plugin                                                  |
	| Copyright (c) 2009-20010 Cubet Technologies <socialcommerce@cubettech.com>  |
	| All rights reserved.                                                        |
	+-----------------------------------------------------------------------------+
	| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE "COPYRIGHT" |
	| FILE PROVIDED WITH THIS DISTRIBUTION. THE AGREEMENT TEXT IS ALSO AVAILABLE  |
	| AT THE FOLLOWING URL: http://socialcommerce.elgg.in/license.html            |
	|                                                                             |
	| THIS  AGREEMENT  EXPRESSES  THE  TERMS  AND CONDITIONS ON WHICH YOU MAY USE |
	| THIS  SOFTWARE   PROGRAM  AND   ASSOCIATED   DOCUMENTATION    THAT  CUBET   |
	| TECHNOLOGIES (hereinafter referred as "THE AUTHOR") IS FURNISHING OR MAKING |
	| AVAILABLE TO YOU WITH  THIS  AGREEMENT  (COLLECTIVELY,  THE  "SOFTWARE").   |
	| PLEASE   REVIEW   THE  TERMS  AND   CONDITIONS  OF  THIS  LICENSE AGREEMENT |
	| CAREFULLY   BEFORE   INSTALLING   OR  USING  THE  SOFTWARE.  BY INSTALLING, |
	| COPYING   OR   OTHERWISE   USING   THE   SOFTWARE,  YOU  AND  YOUR  COMPANY |
	| (COLLECTIVELY,  "YOU")  ARE  ACCEPTING  AND AGREEING  TO  THE TERMS OF THIS |
	| LICENSE   AGREEMENT.   IF  YOU    ARE  NOT  WILLING   TO  BE  BOUND BY THIS |
	| AGREEMENT, DO  NOT INSTALL OR USE THE SOFTWARE.  VARIOUS   COPYRIGHTS   AND |
	| OTHER   INTELLECTUAL   PROPERTY   RIGHTS    PROTECT   THE   SOFTWARE.  THIS |
	| AGREEMENT IS A LICENSE AGREEMENT THAT GIVES  YOU  LIMITED  RIGHTS   TO  USE |
	| THE  SOFTWARE   AND  NOT  AN  AGREEMENT  FOR SALE OR FOR  TRANSFER OF TITLE.|
	| THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY GRANTED BY THIS AGREEMENT.      |
	|                                                                             |
	+-----------------------------------------------------------------------------+
	\*****************************************************************************/

	/**
	 * Elgg view - icon
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	// Get engine
		require_once(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/engine/start.php");
		//echo dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/engine/start.php";
	global $CONFIG;
	
	$mime = $vars['mimetype'];
	if (isset($vars['thumbnail'])) {
		$thumbnail = $vars['thumbnail'];
	} else {
		$thumbnail = false;
	}
	
	$size = $vars['size'];
	if ($size != 'large') {
		$size = 'small';
	}
	
	$title = '';
	if(!empty($vars['title']))
	{
		$title = "title='".$vars['title']."' alt='".$vars['title']."'";
	}
	//echo "<a href=\"{$vars['url']}action/stores/icon?stores_guid={$vars['stores_guid']}\">hai</a>";
	// Handle 
	switch ($mime)
	{
		case 'image/jpg' 	:
		case 'image/jpeg' 	:
		case 'image/png' 	:
		case 'image/gif' 	:
		case 'image/bmp' 	: 
			if ($thumbnail) {
				if ($size == 'small') {
					$url = elgg_add_action_tokens_to_url("{$vars['url']}action/{$CONFIG->pluginname}/icon?stores_guid={$vars['stores_guid']}&mimetype={$mime}");
					echo "<img {$title} src=\"{$url}\" border=\"0\" />";
				} else {
					echo "<img {$title} src=\"{$vars['url']}mod/{$CONFIG->pluginname}/thumbnail.php?stores_guid={$vars['stores_guid']}&mimetype={$mime}\" border=\"0\" />";
				}
				
			} else 
			{
				if ($size == 'large') {
					echo "<img {$title} src=\"{$CONFIG->wwwroot}mod/{$CONFIG->pluginname}/graphics/icons/general_lrg.gif\" border=\"0\" />";
				} else {
					echo "<img {$title} src=\"{$CONFIG->wwwroot}mod/{$CONFIG->pluginname}/graphics/icons/general.gif\" border=\"0\" />";
				}
			}
			
		break;
		default : 
			if (!empty($mime) && elgg_view_exists("{$CONFIG->pluginname}/icon/{$mime}")) {
				echo elgg_view("{$CONFIG->pluginname}/icon/{$mime}", $vars);
			} else if (!empty($mime) && elgg_view_exists("{$CONFIG->pluginname}/icon/" . substr($mime,0,strpos($mime,'/')) . "/default")) {
				echo elgg_view("{$CONFIG->pluginname}/icon/" . substr($mime,0,strpos($mime,'/')) . "/default");
			} else {
				echo "<img {$title} src=\"{$CONFIG->wwwroot}mod/{$CONFIG->pluginname}/graphics/icons/general.gif\" border=\"0\" />";
			}	 
		break;
	}

?>