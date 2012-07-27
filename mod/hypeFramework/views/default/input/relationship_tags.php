<?php
if (isset($vars['class'])) {
	$vars['class'] = "hj-relationship-tags-autocomplete {$vars['class']}";
} else {
	$vars['class'] = "hj-relationship-tags-autocomplete";
}

$options = elgg_extract('options', $vars);

$types = elgg_extract('types', $options, null);
$subtypes = elgg_extract('subtypes', $options, null);
$relationship = elgg_extract('relationship_name', $options, 'tagged_in');
$metadata = elgg_extract('metadata', $options, null);
$wheres = elgg_extract('wheres', $options, null);
$joins = elgg_extract('joins', $options, null);
$selects = elgg_extract('selects', $options, null);

$entities = elgg_get_entities_from_metadata(array(
	'types' => $types,
	'subtypes' => $subtypes,
	'metadata_name_value_pairs' => $metadata,
	'where' => $wheres,
	'joins' => $joins,
	'selects' => $selects,
	'limit' => 0
		));

if (is_array($entities)) {
	foreach ($entities as $entity) {
		$result = array(
			'icon' => $entity->getIconURL('tiny'),
			'value' => $entity->title,
			'guid' => $entity->guid,
		);
		$results[] = $result;
	}
}

if ($results) {
	$results = json_encode($results);
	?>
	<script type="text/javascript">
	    elgg.provide('hj.framework.relationshiptags');
	    hj.framework.relationshiptags.sourceentities = <?php echo $results ?>;
	</script>
	<?php
	$guids = $vars['value'];
	$vars['value'] = "";
	$vars['data-options'] = $vars['options'];
	unset($vars['options']);

	elgg_load_js('hj.framework.relationshiptags');
	?>
	<input type="text" <?php echo elgg_format_attributes($vars); ?> />
	<input type="hidden" name="relationship_tags_name" value="<?php echo $relationship ?>" />
	<?php
	$guids = explode(',', $guids);

	echo '<ul id="relationship-tags" class="elgg-list">';
	if (is_array($guids)) {
		foreach ($guids as $guid) {
			$guid = trim($guid);
			$tag = get_entity($guid);
			if (elgg_instanceof($tag)) {
				$icon = $tag->getIconURL('tiny');
				$remove = elgg_echo('remove');
				$html = '<li class="relationship-tag hj-padding-ten clearfix">
                            <span class="hj-left hj-padding-ten"><img src="' . $icon . '" /></span>
                            <span class="hj-left hj-padding-ten">' . $tag->title . '</span>
                            <a class="hj-relationship-tag-remove hj-right" href="javascript:void(0)">' . $remove . '</a>
                            <input type="hidden" name="relationship_tag_guids[]" value="' . $tag->guid . '" />
                        </li>';
				echo $html;
			}
		}
	}
	echo '</ul>';
} else {
	echo elgg_echo("hj:framework:relationship_tags:no$relationship");
}