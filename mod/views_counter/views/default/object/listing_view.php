<?php
	/**
	 * @file views/default/object/listing_view.php
	 * @brief Displays the views demo entity while listing the demo entities
	 * 
	 * @var unknown_type
	 */
 
$icon = elgg_view(
			'graphics/icon', array(
			'entity' => $vars['entity'],
			'size' => 'small',
		)
	);


	$title = $vars['entity']->title;
	if (!$title) {
		$title = $vars['entity']->name;
	}
	if (!$title) {
		$title = get_class($vars['entity']);
	}

	$controls = "";
	if ($vars['entity']->canEdit()) {
		$delete = elgg_view('output/confirmlink', array(
			'href' => "{$vars['url']}action/entities/delete?guid={$vars['entity']->guid}", 
			'text' => elgg_echo('delete')
		));
		$controls .= " ($delete)";
	}
	
	$controls .= elgg_view('views_counter',$vars);

	$info = "<div><p><b><a href=\"" . $vars['entity']->getUrl() . "\">" . $title . "</a></b> $controls </p></div>";

	if (get_input('search_viewtype') == "gallery") {
		$icon = "";
	}

	$owner = $vars['entity']->getOwnerEntity();
	$ownertxt = elgg_echo('unknown');
	if ($owner) {
		$ownertxt = "<a href=\"" . $owner->getURL() . "\">" . $owner->name ."</a>";
	}

	$info .= "<div>".sprintf(elgg_echo("entity:default:strapline"),
		elgg_view_friendly_time($vars['entity']->time_created),
		$ownertxt
	);

	$info .= "</div>";

	$info = "<span title=\"" . elgg_echo('entity:default:missingsupport:popup') . "\">$info</span>";
	$icon = "<span title=\"" . elgg_echo('entity:default:missingsupport:popup') . "\">$icon</span>";

	echo elgg_view_listing($icon, $info);
?>