<?php
/**
 * Create scraper form
 */

$scraper = get_entity(get_input('guid'),'object');


$name_label = elgg_echo('blog:minds:scraper:name');
$name_input = elgg_view('input/text', array(
	'name' => 'title',
	'value' => $scraper->title,
));

$license_label = elgg_echo('minds:license:label');
$license_input = elgg_view('input/licenses', array('name'=>'license', 'value'=>$scraper->license));


$url_label = elgg_echo('blog:minds:scraper:url');
$url_input = elgg_view('input/text', array(
	'name' => 'url',
	'value' => $scraper->feed_url
));

// hidden inputs
$guid_input = elgg_view('input/hidden', array('name' => 'guid', 'value' => $scraper->guid));
$submit_button = elgg_view('input/submit',array('value'=>'Save'));

echo <<<___HTML
<div>
	<label>$name_label</label>
	$name_input
</div>

<div>
	<label for="blog_license">$license_label</label>
	$license_input
</div>

<div>
	<label>$url_label</label>
	$url_input
</div>

<div class="elgg-foot">

	$guid_input

	$submit_button
</div>

___HTML;
