<?php

function hj_framework_entity_url_forwarder($entity) {
	return 'hj/';
}

function hj_framework_segment_url_forwarder($entity) {
	if (elgg_instanceof($entity, 'object', 'hjsegment')) {
		$container = $entity->getContainerEntity();
		return $container->getURL();
	}
	return 'hj/';
}

/**
 * Forward hjAnnotation to its container handler
 */
function hj_framework_annotation_url_forwarder($entity) {
	$container = $entity->getContainerEntity();
	if (elgg_instanceof($container)) {
		return $container->getURL();
	} else {
		return '';
	}
}

function hj_framework_page_handlers($page) {
	$plugin = 'hypeFramework';
	$shortcuts = hj_framework_path_shortcuts($plugin);
	$pages = dirname(dirname(dirname(__FILE__))) . '/pages/';

	if (!isset($page[0])) {
		forward();
	}
	
	switch ($page[0]) {
		case 'file' :
			if (!isset($page[1]))
				forward();


			switch ($page[1]) {
				case 'download':
					set_input('e', $page[2]);
					include $pages . 'file/download.php';
					break;

				default :
					forward();
					break;
			}

		case 'print' :
			include $pages . 'print/print.php';
			break;

		case 'pdf' :
			include $pages . 'print/pdf.php';
			break;

		case 'icon':
			set_input('guid', $page[1]);
			set_input('size', $page[2]);
			include "{$pages}icon/icon.php";
			return true;
			break;

		case 'sync':
			switch ($page[1]) {
				default :
					include "{$pages}sync/sync.php";
					return true;
					break;

				case 'priority' :
					include "{$pages}sync/sync_priority.php";
					return true;
					break;
			}
	}
	return true;
}