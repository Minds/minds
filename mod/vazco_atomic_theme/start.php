<?php //ł ?><?php
/*******************************************************************************
 * vazco_atomic_theme
 *
 * @author Michal Zacher, Elggdev
 * @copyright Elggdev
 * @licence per-site commercial licence: http://elggdev.com/pg/license
 ******************************************************************************/

 
	function vazco_atomic_theme_init(){
		global $CONFIG;
		elgg_extend_view('css/elgg','vazco_atomic_theme/css');

		$pageHandler = 'vazco_atomic_theme';
		elgg_register_page_handler($pageHandler,'vazco_atomic_theme_page_handler');

		elgg_register_event_handler('pagesetup','system','vazco_atomic_theme_pagesetup');

		return true;
	}



	function vazco_atomic_theme_page_handler($page){
		global $CONFIG;
		$plugin_name = 'vazco_atomic_theme';
		$pageHandler = 'vazco_atomic_theme';
		
		/*
		switch ($page[0]){
		}
		*/
		return true;
	}


	elgg_register_event_handler('init', 'system', 'vazco_atomic_theme_init');
?>