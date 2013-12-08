<?php

// Get callbacks

echo elgg_view_form('deck_river/column_settings', array('class' => 'deck-river-form-column-settings'), array(
	'tab' => get_input('tab', false),
	'column' => get_input('column', false),
));
