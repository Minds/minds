<?php
/**
 * Elgg exception (failsafe mode)
 * Displays a single exception
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] An exception
 */

$view = 'messages/exceptions/' . str_replace('\\','/', strtolower(get_class($vars['object'])));

if (elgg_view_exists($view)) {
    echo elgg_view($view, $vars);
} else { ?>
    <h1>Sorry.... we're doing some work</h1>
    <p>Please check back later</p>
<?php } ?>
