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
	 * Elgg view - category full view
	 * 
	 * @package Elgg SocialCommerce
	 * @author Cubet Technologies
	 * @copyright Cubet Technologies 2009-2010
	 * @link http://elgghub.com
	 */ 
	$category = elgg_extract('entity', $vars, FALSE);
	
	if(!elgg_is_logged_in()){
		forward("{$CONFIG->pluginname}/" . $_SESSION['user']->username);
	}
	
	$owner = $category->getOwnerEntity();
	$owner_icon = elgg_view_entity_icon($owner, 'tiny');
	$container = $category->getContainerEntity();

	$owner_link = elgg_view('output/url', array(
		'href' => "bookmarks/owner/$owner->username",
		'text' => $owner->name,
	));
	
	$author_text = elgg_echo('byline', array($owner_link));

	$tags = elgg_view('output/tags', array('tags' => $category->tags));
	$date = elgg_view_friendly_time($category->time_created);
	
	$metadata = elgg_view_menu('entity', array(
		'entity' => $category,
		'handler' => 'socialcommerce/category',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
	
	$subtitle = "$author_text $date $categories";
	
	// do not show the metadata and controls in widget view
	if (elgg_in_context('widgets')) {
		$metadata = '';
	}
	
	$params = array(
		'entity' => $category,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);
	
	$description = elgg_view('output/longtext', array('value' => $category->description, 'class' => 'pbl'));

	echo elgg_view_title($category->title);
	echo elgg_view_image_block($owner_icon, $list_body);
?>
<div class="bookmark elgg-content mts">
	<?php echo $description;?>
</div>