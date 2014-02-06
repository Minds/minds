<?php
/**
 * Youtube embed form
 */

?>
	<label><?php echo elgg_echo('Youtube Video Url'); ?></label><br />
	<?php
	echo elgg_view('input/text', array(
		'name' => 'url',
		'placeholder' => 'Paste or enter the url to a Youtube video here eg. http://www.youtube.com/watch?feature=player_embedded&v=9bZkp7q19f0',
		'class' => 'elgg-autofocus',
	));

echo '<div class="elgg-foot"><br/>';
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('Insert')));
echo '</div>';

