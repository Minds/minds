<?php
/**
 * Elgg Mobile
 * A Mobile Client For Elgg
 *
 * @package Elgg
 * @subpackage Core
 * @author Mark Harding
 * @link http://maestrozone.com
 *
 */
	/**
	 * Start the Elgg engine
	 */
		define('externalpage',true);
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
		//Load the front page
	global $CONFIG;
	set_input('view', 'mobile');
	$content = elgg_view_layout('two_column_left_sidebar', elgg_view("account/forms/login"));
	page_draw(null, $content);
?>
