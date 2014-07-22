
<?php

require_once(dirname(dirname(__FILE__)) . '/engine/start.php');

global $CONFIG;

$db = new minds\core\data\call('plugin');
foreach($db->get('', 10000) as $key => $data){
        echo "$key \n";
	$db->removeRow($key);
}

foreach($CONFIG->plugins as $plugin_id){
	$data = array('active'=>1);
	if(isset($CONFIG->pluginSettings->$plugin_id))
		$data = array_merge($CONFIG->pluginSettings->$plugin_id, $data);
	$db->insert($plugin_id, $data);
}

