<?php
/**
 * Suggested Friends
 * 
 * Adapted from:
 * people_from_the_neighborhood
 *
 * @author emdagon
 * @link http://community.elgg.org/pg/profile/emdagon
 * @copyright (c) Condiminds 2011
 * @link http://www.condiminds.com/
 * @license GNU General Public License (GPL) version 2
 */


$widget = $vars['entity'];

$friends = $widget->look_in_friends == 'no' ? 0 : 3;
$groups = $widget->look_in_groups == 'no' ? 0 : 3;
$num_display = $widget->num_display != null ? $widget->num_display : 3;

$people = suggested_friends_get_people(elgg_get_logged_in_user_guid(), $friends, $groups);

// limit our number of people
while(count($people) > $num_display){
  array_pop($people);
}

echo elgg_view('suggested_friends/people', array('people' => $people)); ?>
<div class="clearfloat"></div>
<div class="widget_more_wrapper"><a href="<?php echo elgg_get_site_url(); ?>suggested_friends"><?php echo elgg_echo('suggested_friends:see:more'); ?></a></div>
