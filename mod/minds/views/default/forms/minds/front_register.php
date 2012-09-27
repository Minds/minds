<?php
/**
 * Elgg file upload/save form
 *
 * @package ElggFile
 */

echo elgg_view('input/text', array(	'name'=> 'n', 'placeholder'=>elgg_echo('name')));

echo elgg_view('input/text', array(	'name'=> 'e', 'placeholder'=>elgg_echo('email')));
 
echo elgg_view('input/submit', array('value' => elgg_echo('register:early')));

?>
</div>
