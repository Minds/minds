<?php
/**
 * Anypage
 */

elgg_register_event_handler('init', 'system', 'anypage_init');

/**
 * Anypage init
 */
function anypage_init() {
	add_subtype('object', 'anypage', 'AnyPage');
	
	elgg_register_plugin_hook_handler('entities_class_loader', 'all', function($hook, $type, $return, $row){
		if($row->type == 'object' && $row->subtype == 'anypage')
			return new \AnyPage($row);
	});
	
	//minds\core\views::cache('page/elements/global_sidebar_footer');

	elgg_register_admin_menu_item('configure', 'anypage', 'appearance');
	// fix for selecting the right section in admin area
	elgg_register_plugin_hook_handler('prepare', 'menu:page', 'anypage_init_fix_admin_menu');

	$actions = dirname(__FILE__) . '/actions/anypage';

	elgg_register_action('anypage/save', "$actions/save.php", 'admin');
	elgg_register_action('anypage/delete', "$actions/delete.php", 'admin');

	elgg_extend_view('js/elgg', 'anypage/js');
	elgg_extend_view('css/admin', 'anypage/admin_css');
	elgg_extend_view('css/elgg', 'anypage/css');

	elgg_register_plugin_hook_handler('route', 'all', 'anypage_router');
	elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'anypage_walled_garden_public_pages');
	
	//setup the footer
	elgg_register_plugin_hook_handler('register', 'menu:footer', 'anypage_setup_footer_menu');
}

/**
 * Setup the links to site pages
 */
function anypage_setup_footer_menu() {
	$show = true;
	
	if(get_input('ajax') || elgg_get_viewtype() == 'json')
		$show = false;

//	$show = false;
	global $ANYPAGE_CACHE;
	if($show && !$ANYPAGE_CACHE){
		$ANYPAGE_CACHE = elgg_get_entities(array('type'=>'object', 'subtype'=>'anypage', 'limit'=>0)); 
	}

	if(!is_array($ANYPAGE_CACHE))
		$show = false;
	
	if($show){
		foreach ($ANYPAGE_CACHE as $page) {
			elgg_register_menu_item('footer', array(
				'name' => $page->title,
				'href' => $page->getURL(),
				'text' => $page->title,
				'priority' => 150
			));
		}	
	}
}

/**
 * Select the right menu entry for admin section
 *
 * @param type $hook
 * @param type $type
 * @param type $value
 * @param type $params
 * @return null
 */
function anypage_init_fix_admin_menu($hook, $type, $value, $params) {
	if (!(elgg_in_context('admin') && elgg_in_context('anypage'))) {
		return null;
	}

	if (isset($value['configure'])) {
		foreach ($value['configure'] as $item) {
			if ($item->getName() == 'appearance') {
				foreach($item->getChildren() as $child) {
					if ($child->getName() == 'appearance:anypage') {
						$item->setSelected();
						$child->setSelected();
						break;
					}
				}
				break;
			}
		}
	}
}

/**
 * Route to the correct page if defined. Allows a fallthrough to the 404 error page otherwise.
 *
 * @param $hook
 * @param $type
 * @param $value
 * @param $params
 */
function anypage_router($hook, $type, $value, $params) {
	$handler = elgg_extract('handler', $value);
	$pages = elgg_extract('segments', $value, array());

	global $CONFIG;
	if(isset($CONFIG->pagehandler[$handler]) && is_callable($CONFIG->pagehandler[$handler])){
		return;
	}

	array_unshift($pages, $handler);
	$path = AnyPage::normalizePath(implode('/', $pages));

	$page = AnyPage::getAnyPageEntityFromPath($path);
	if (!$page) {
		return;
	}

	if ($page->requiresLogin()) {
		gatekeeper();
	}

	if ($page->usesView()) {
		// route to view
		echo elgg_view($page->getView());
		exit;
	} else {
		elgg_set_context('anypage');
		//$filter = elgg_view('anypage/filter', array('selected'=>$page->title));
		// display entity
		$content = elgg_view_entity($page);
		$body = elgg_view_layout('one_column', array(
			'header'=> "<h1>$page->title</h1>",
			'content' => $content, 
			'hide_ads'=>true,
			'sidebar'=>elgg_view('anypage/menu'),
			'class'=>'pages',
		));
		echo elgg_view_page($page->title, $body, 'default', array('class'=>'sidebar-active'));
		exit;
	}
}

/**
 * Prepare form variables for page edit form.
 *
 * @param mixed $page
 * @return array
 */
function anypage_prepare_form_vars($page = null) {
	$values = array(
		'title' => '',
		'page_path' => '',
		'description' => '',
		'use_view' => false,
		'visible_through_walled_garden' => false,
		'requires_login' => false,
		'guid' => null,
		'entity' => $page,
	);

	if ($page) {
		foreach (array_keys($values) as $field) {
			if (isset($page->$field)) {
				$values[$field] = $page->$field;
			}
		}
	}

	if (elgg_is_sticky_form('anypage')) {
		$sticky_values = elgg_get_sticky_values('anypage');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('anypage');

	return $values;
}

/**
 * Registers pages visible through walled garden with public pages
 *
 * @param type $hook
 * @param type $type
 * @param type $value
 * @param type $params
 * @return type
 */
function anypage_walled_garden_public_pages($hook, $type, $value, $params) {
	$paths_tmp = AnyPage::getPathsVisibleThroughWalledGarden();
	$paths_tmp = array_map('preg_quote', $paths_tmp);
	// the return value expect no leading slash. blarg

	$paths = array();
	foreach ($paths_tmp as $path) {
		$paths[] = ltrim($path, '/');
	}

	$value = array_merge($value, $paths);
	return $value;
}
