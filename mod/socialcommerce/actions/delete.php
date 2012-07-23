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
	 * Elgg product - delete action
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	 
	global $CONFIG;
	$guid = (int) get_input('guid');
	if ($stores = get_entity($guid)) {

		if ($stores->canEdit()) {
			$stores->status = 0;
			$context = elgg_get_context();
			elgg_set_context('delete_product');
			$delete = $stores->save();
			elgg_set_context($context);
			//------- Delete images from data -----------//
			/*$image_prefix = "{$CONFIG->pluginname}/".$stores->guid;
			
			$delstores = new ElggFile();
			$delstores->owner_guid = $stores->owner_guid;
			$delstores->setFilename($image_prefix . ".jpg");
			$delstores->delete();
			
			$delstores = new ElggFile();
			$delstores->owner_guid = $stores->owner_guid;
			$delstores->setFilename($image_prefix . "tiny.jpg");
			$delstores->delete();
			
			$delstores = new ElggFile();
			$delstores->owner_guid = $stores->owner_guid;
			$delstores->setFilename($image_prefix . "small.jpg");
			$delstores->delete();
			
			$delstores = new ElggFile();
			$delstores->owner_guid = $stores->owner_guid;
			$delstores->setFilename($image_prefix . "medium.jpg");
			$delstores->delete();
			
			$delstores = new ElggFile();
			$delstores->owner_guid = $stores->owner_guid;
			$delstores->setFilename($image_prefix . "large.jpg");
			$delstores->delete();
			
			if($stores->filename){
				$delstores = new ElggFile();
				$delstores->owner_guid = $stores->owner_guid;
				$delstores->setFilename($stores->filename);
				$delstores->delete();	
			}
			
			$container = get_entity($stores->container_guid);
			
			$thumbnail = $stores->thumbnail;
			$smallthumb = $stores->smallthumb;
			$largethumb = $stores->largethumb;
			if ($thumbnail) {

				$delstores = new ElggFile();
				$delstores->owner_guid = $stores->owner_guid;
				$delstores->setFilename($thumbnail);
				$delstores->delete();

			}
			if ($smallthumb) {

				$delstores = new ElggFile();
				$delstores->owner_guid = $stores->owner_guid;
				$delstores->setFilename($smallthumb);
				$delstores->delete();

			}
			if ($largethumb) {

				$delstores = new ElggFile();
				$delstores->owner_guid = $stores->owner_guid;
				$delstores->setFilename($largethumb);
				$delstores->delete();

			}*/
			
			if (!$delete) {
				register_error(elgg_echo("stores:deletefailed"));
			} else {
				system_message(elgg_echo("stores:deleted"));
			}

		} else {
			
			$container = $_SESSION['user'];
			register_error(elgg_echo("stores:deletefailed"));
			
		}

	} else {
		
		register_error(elgg_echo("stores:deletefailed"));
		
	}
	
	forward("{$CONFIG->pluginname}/" . $_SESSION['user']->username);
exit();
?>
