<?php

// Get tab and column
$tab = elgg_extract('tab', $vars, null);
$column = elgg_extract('column', $vars, null);

if (!$tab || !$column) {
	return;
}

// Get the settings of the current user
$user = elgg_get_logged_in_user_entity();
$site_name = elgg_get_site_entity()->name;

if ($column == 'new') {
	//foreach ($user_river_options->$tab as $key => $item) {
	//	$n[] = preg_replace('/[^0-9]+/', '', $key);
	//}
	$column_name = 'column'; //temporary name
	$new = true;
} else{
	$column = get_entity($column);
	$column_name = $column->name;
}

?>

<?php echo elgg_view('input/hidden', array('name' => 'column_name', 'value' => $column_name)); ?>
<?php echo elgg_view('input/hidden', array('name' => 'column_guid', 'value' => $column->guid)); ?>
<?php echo elgg_view('input/hidden', array('name' => 'tab_guid', 'value' => $tab)); ?>

<div id='deck-column-settings' class='pas'>
	<?php
		global $deck_networks;
		
		$selected = $new ? 'twitter' : $column->getAccount()->network;
		
		foreach($deck_networks as $network){
			$tabs[] = array('title' => elgg_echo('deck_river:network'.$network['name']), 'link_class' => $network['name'], 'url' => "#", 'selected' => $selected == $network['name'] ? true : false);
		}
		
		
		$params = array(
			'type' => 'vertical',
			'class' => 'networks float',
			'tabs' => $tabs
		);
		
		echo elgg_view('navigation/tabs', $params);

		foreach($deck_networks as $network){
				echo elgg_view('deck_river/networks/'. $network['name'] . '/column_settings', array('selected'=>$selected, 'column'=>$column, 'tab'=>$tab));
		}
	?>
	<div class="elgg-foot ptm">
	<?php
		echo elgg_view('input/submit', array(
			'name' => 'elgg',
			'value' => $new ?  elgg_echo('Add') : elgg_echo('Update'),
			'class' => 'elgg-button-submit'
		));
		
	?>
	</div>

</div>
