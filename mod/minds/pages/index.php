<?php
/**
 * Minds theme
 *
 * @package Minds
 * @author Kramnorth (Mark Harding)
 *
 */
$params = array('content'=> elgg_view('minds/index'));
$body = elgg_view_layout('one_column', $params);

elgg_unregister_menu_item('footer', 'report_this');

echo elgg_view_page('', $body);

?>
