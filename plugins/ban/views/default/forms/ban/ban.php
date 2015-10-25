<?php
/**
 * Ban user form
 */

$user = $vars['user'];

echo '<h2 class="mbs">' . $user->name . '</h2>';
echo elgg_view_entity_icon($user);

echo '<div>';
echo '<label>' . elgg_echo('ban:reason') . '</label>';
echo elgg_view('input/text', array('name' => 'reason'));
echo '</div>';

echo '<div>';
echo '<label>' . elgg_echo('ban:length') . '</label>';
echo elgg_view('input/text', array('name' => 'length'));
echo '</div>';

echo '<div>';
$options = array(elgg_echo('ban:notify') => 'yes');
echo elgg_view('input/checkboxes', array('name' => 'notify', 'options' => $options));
echo '</div>';

echo '<div class="elgg-foot">';
echo elgg_view('input/hidden', array('name' => 'guid', 'value' => $vars['user']->guid));

$referrer = urlencode($_SERVER['HTTP_REFERER']);
echo elgg_view('input/hidden', array('name' => 'referrer', 'value' => $referrer));

echo elgg_view('input/submit', array('value' => elgg_echo('ban')));
echo '</div>';
