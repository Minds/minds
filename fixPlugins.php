<?php
require_once(dirname(__FILE__) . "/engine/start.php");

global $CONFIG;

elgg_set_ignore_access();
$plugins = array(
							'orientation',
						);
foreach($plugins as $plugin){
	$entity = get_entity($plugin, 'plugin');
	if($entity instanceof ElggPlugin){
		var_dump($entity);
		$entity->activate();
		$entity->setPriority('last');
	} else {
		var_dump($plugin);
	}
}
