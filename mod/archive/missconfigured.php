<?php
/**
* Kaltura video client
* @package ElggKalturaVideo
* @license http://www.gnu.org/licenses/gpl.html GNU Public License version 3
* @author Ivan Vergés <ivan@microstudi.net>
* @copyright Ivan Vergés 2010
* @link http://microstudi.net/elgg/
**/

elgg_load_library('archive:kaltura');

$body = elgg_view_title(elgg_echo("kalturavideo:error:notconfigured"));

$body .= '<div class="contentWrapper">'.kaltura_get_error_page("","",false)."</div>";

//global $autofeed;
//$autofeed = false;
// Display main admin menu
echo elgg_view_page($title, elgg_view_layout("two_column_left_sidebar", array('content' => $body)));

?>
