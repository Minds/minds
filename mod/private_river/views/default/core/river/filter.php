<?php
/**
 * Content filter for river
 * controls drop down 
 * @uses $vars[]
 */
if (elgg_is_admin_logged_in()) {
// create selection array for admin
$options = array();
$options['type=mine'] = elgg_echo('river:select', array(elgg_echo('all'))); 
$registered_entities = elgg_get_config('registered_entities');
//print_r ($vars);
if (!empty($registered_entities)) {
	foreach ($registered_entities as $type => $subtypes) {
		// subtype will always be an array.
		if (!count($subtypes)) {
			$label = elgg_echo('river:select', array(elgg_echo("item:$type")));
			$options["type=$type"] = $label;
		} else {
			foreach ($subtypes as $subtype) {
				$label = elgg_echo('river:select', array(elgg_echo("item:$type:$subtype")));
				$options["type=$type&subtype=$subtype"] = $label;
			}
		}
	}
}

$params = array(
	'id' => 'elgg-river-selector',
	'options_values' => $options,
);
$selector = $vars['selector'];
if ($selector) {
	$params['value'] = $selector;
}
echo elgg_view('input/dropdown', $params);

	} else {
// create selection array for non admin
$options = array();
$options['type=mine'] = elgg_echo('river:select', array(elgg_echo('mine'))); //changed type from all to mine for private_river
$registered_entities = elgg_get_config('registered_entities');
//print_r ($vars);
if (!empty($registered_entities)) {
	foreach ($registered_entities as $type => $subtypes) {
		// subtype will always be an array.
		if (!count($subtypes)) {
			$label = elgg_echo('river:select', array(elgg_echo("item:$type")));
			$options["type=$type"] = $label;
		} else {
			foreach ($subtypes as $subtype) {
				$label = elgg_echo('river:select', array(elgg_echo("item:$type:$subtype")));
				$options["type=$type&subtype=$subtype"] = $label;
			}
		}
	}
}

$params = array(
	'id' => 'elgg-river-selector',
	'options_values' => $options,
);
$selector = $vars['selector'];
if ($selector) {
	$params['value'] = $selector;
}
echo elgg_view('input/dropdown', $params);
}
?>
<?php //@todo JS 1.8: no ?>
<script type="text/javascript">
elgg.register_hook_handler('init', 'system', function() {
	$('#elgg-river-selector').change(function() {
		var url = window.location.href;
		if (window.location.search.length) {
			url = url.substring(0, url.indexOf('?'));
		}
		url += '?' + $(this).val();
		elgg.forward(url);
	});
});
</script>
