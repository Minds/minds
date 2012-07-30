<?php
/**
 * Form component for editing a single image
 *
 * @uses $vars['entity']
 *
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$image = $vars['entity'];

echo '<div class="elgg-image-block">';

echo '<div class="elgg-image">';
echo elgg_view_entity_icon($image, 'small', array('href' => false));
echo '</div>';

echo '<div class="elgg-body"><fieldset class="mlm">';
echo '<div><label>' . elgg_echo('album:title') . '</label>';
echo elgg_view('input/text', array('name' => 'title[]', 'value' => $title,));
echo '</div>';

echo '<div><label>' . elgg_echo('caption') . '</label>';
echo elgg_view('input/longtext', array('name' => 'caption[]'));
echo '</div>';

echo '<div><label>' . elgg_echo("tags") . '</label>';
echo elgg_view('input/tags', array('name' => 'tags[]'));
echo '</div>';

echo elgg_view('input/hidden', array('name' => 'guid[]', 'value' => $image->getGUID()));
echo '<fieldset></div>';

echo '</div>';
