<?php

$categories = elgg_get_plugin_setting('categories', 'market');
echo "<p>Categories (separate by a comma)</p>";
echo elgg_view('input/text',array('value' => $categories, 'name' => 'params[categories]'));

