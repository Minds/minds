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
elgg_push_breadcrumb(elgg_echo('market:add'));

if (elgg_get_plugin_setting('market_adminonly', 'market') == 'yes') {
	admin_gatekeeper();
}
		
// How many classifieds can a user have
$marketmax = elgg_get_plugin_setting('market_max', 'market');
if(!$marketmax){
	$marketmax == 0;
}
$marketactive = count_user_objects(elgg_get_logged_in_user_guid(), 'market');

$title = elgg_echo('market:add:title');

// Show form, or error if users has used his quota
if ($marketmax == 0 || elgg_is_admin_logged_in()) { 
	$form_vars = array('name' => 'marketForm', 'js' => 'onsubmit="acceptTerms();return false;"', 'enctype' => 'multipart/form-data');
	$content = elgg_view_form("market/add", $form_vars);
} elseif ($marketmax > $marketactive) { 
	$form_vars = array('name' => 'marketForm', 'js' => 'onsubmit="acceptTerms();return false;"', 'enctype' => 'multipart/form-data');
	$content = elgg_view_form("market/add", $form_vars);
} else {
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
