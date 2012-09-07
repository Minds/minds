<?php
/**
 * Elgg show events RSS view
 * 
 * @package event_calendar
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Kevin Jardine <kevin@radagast.biz>
 * @copyright Radagast Solutions 2009
 * @link http://radagast.biz/
 * 
 */
if ($vars['events']) {
	echo elgg_view_entity_list($vars['events'], $vars['count'], $vars['offset'], $vars['limit'], false, false);
}
?>