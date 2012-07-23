<?php
/**
 * Elgg Market Plugin
 * @package market
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author slyhne
 * @copyright slyhne 2010-2011
 * @link www.zurf.dk/elgg
 * @version 1.8
 */

elgg_load_js('lightbox');
elgg_load_css('lightbox');

elgg_push_breadcrumb(elgg_echo('market:title'), "market/category");
elgg_push_breadcrumb(elgg_echo('market:edit'));

// Get the post, if it exists
$market_guid = (int) get_input('guid');
if ($post = get_entity($market_guid)) {
			
	if ($post->canEdit()) {
		if ($post->marketcategory == 'auction') {
			$title = elgg_echo('market:edit:auction');
			$form_vars = array('name' => 'auctionForm', 'js' => 'onsubmit="acceptTerms();return false;"', 'enctype' => 'multipart/form-data');
			$content = elgg_view_form("market/editauction", $form_vars, array('entity' => $post));
		} else {
			$title = elgg_echo('market:edit');
			$form_vars = array('name' => 'marketForm', 'js' => 'onsubmit="acceptTerms();return false;"', 'enctype' => 'multipart/form-data');
			$content = elgg_view_form("market/edit", $form_vars, array('entity' => $post));
		}			
	}
			
} else {

	$title = elgg_echo('market:none:found');
	$content = elgg_view("market/error");
}

// Show market sidebar
$sidebar = elgg_view("market/sidebar");
		
$params = array(
		'content' => $content,
		'title' => $title,
		'sidebar' => $sidebar,
		);

$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);

