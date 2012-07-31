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


echo elgg_view_page('', $body);

?>
