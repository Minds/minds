<?php
/**
 * Button area for showing the add widgets panel
 */
$user = elgg_get_page_owner_entity();

//this needs to be loaded before the widget is added. 
elgg_load_js('elgg.wall');
?>
<div class="elgg-widget-add-control">
<?php
if($user instanceof ElggUser){

	if($user->canEdit())							
	echo elgg_view('output/url', array(
		'href' => '#widgets-add-panel',
		'text' => elgg_echo('widgets:add'),
		'class' => 'elgg-button elgg-button-action channel',
		'rel' => 'toggle',
		'is_trusted' => true,
	));
}
?>
</div>
