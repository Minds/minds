<?php
/**
 * List comments with optional add form
 *
 * @uses $vars['entity']        ElggEntity
 * @uses $vars['show_add_form'] Display add form or not
 * @uses $vars['id']            Optional id for the div
 * @uses $vars['class']         Optional additional class for the div
 */




echo '<h3>' . elgg_echo('comments') . '</h3>';
echo elgg_view('hj/comments/bar', $vars);
echo elgg_view('hj/comments/input', $vars);
