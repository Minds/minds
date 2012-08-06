<?php
$album = $vars['entity'];
$help = elgg_echo('tidypics:uploader:help');

$input = elgg_view('input/file', array(
	'name' => 'images[]',
	'multiple' => 'multiple',
	'class' => 'hidden-js',
));

$button = elgg_view('output/url', array(
	'text' => elgg_echo('tidypics:uploader:choose') . $input,
	'class' => 'elgg-button elgg-button-action fileinput-button',
));

$reset = elgg_view('input/reset', array(
	'value' => elgg_echo('cancel'),
	'class' => 'hidden',
));

$foot = elgg_view('input/hidden', array('name' => 'guid', 'value' => $album->getGUID()));
$upload_button = elgg_view('input/submit', array('value' => elgg_echo("tidypics:uploader:upload")));

echo <<<HTML
<div>
	$max
</div>
<div class="fileinput-container">
	$button
	$reset
	$upload_button
	<p class="elgg-text-help">$help</p>
</div>
<div class="mtm"><!-- The table listing the files available for upload/download -->
        <table role="presentation" class="elgg-table-alt clearfloat mtm">
			<tbody class="files"></tbody>
		</table>
</div>
<div class='elgg-foot'>
	$foot 
	$upload_button
</div>
HTML;

?>

<noscript><style type="text/css">hidden-nojs {display: hidden}</style></noscript>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
	<tr class="template-upload fade">
		{% if (file.error) { %}
			<td class="error"><span class="elgg-message elgg-state-error">{%=locale.fileupload.error%} {%=locale.fileupload.errors[file.error] || file.error%}</span></td>
		{% } else { %}
			<td class="preview"><span class="fade"></span></td>
		{% } %}
		<td class="name"><span>{%=file.name%}</span></td>
		<td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
		
	</tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl" />
</script>
