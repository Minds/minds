<?php
/**
 * Edit properties on a batch of images
 *
 * @uses $vars['batch'] ElggObject
 * 
 * @author Cash Costello
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */

$batch = $vars['batch'];
$album = $batch->getContainerEntity();

$images = elgg_get_entities_from_relationship(array(
	'type' => 'object',
	'subtype' => 'image',
	'relationship' => 'belongs_to_batch',
	'relationship_guid' => $batch->getGUID(),
	'inverse_relationship' => true,
	'limit' => 0
));

echo '<ul>';
foreach ($images as $image) {
	echo '<li>';
	echo elgg_view('forms/photos/batch/edit/image', array('entity' => $image));
	echo '</li>';
}
echo '</ul>';

echo '<div class="elgg-foot">';
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
echo '</div>';
