<?php
/**
 * This should really be updated to the latest restful style
 */
 
if(get_input('fat'))
	elgg_set_plugin_setting('style','fat', 'minds');

if(get_input('thin'))
	elgg_set_plugin_setting('style','thin', 'minds');

if(get_input('admin')){
	admin_gatekeeper();
	if(get_input('add')){
		$item = new ElggFile();
		$item->subtype = 'carousel_item';
		$item->title = '';
		$item->owner_guid = elgg_get_logged_in_user_guid();
		$item->access_id = ACCESS_PUBLIC;
		$item->save();
	}

	$items = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'carousel_item', 
		'limit' => 100
	));
} else {
	if(get_input('add')){
		$item = new minds\entities\carousel();
		$item->title = '';
		$item->owner_guid = elgg_get_logged_in_user_guid();
		$item->access_id = ACCESS_PUBLIC;
		$item->save();
	}

	$items = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'carousel',
		'owner_guid'=>get_input('owner_guid', elgg_get_logged_in_user_guid()), 
		'limit' => 100
	));
}

foreach($items as $k=>$item){
	
	if(get_input("delete:$item->guid")){
			$item->delete();
			unset($items[$k]);
			continue;
	}
	
	$item->title = str_replace("$item->guid:", '', get_input("$item->guid:title"));
	$item->shadow = str_replace("$item->guid:", '', get_input("$item->guid:shadow"));
	$item->order = str_replace("$item->guid:", '', get_input("$item->guid:order"));
	$item->color = str_replace("$item->guid:", '', get_input("$item->guid:color"));
	$item->href = str_replace("$item->guid:", '', get_input("$item->guid:href"));
	
	//Upload and compress
	//if(isset($file["$item->guid:title"]['tmp_name'])){
		$files = array();
		$sizes = array(
			'thin' => array(
	            'w' => 2000,
	            'h' => 800,
	            'square' => false,
	            'upscale' => true
			),
			'fat' => array(
	            'w' => 2000,
	           // 'h' => 800,
	            'square' => false,
	            'upscale' => true
			)
		);
	    foreach ($sizes as $name => $size_info) {
		   
		    global $CONFIG;
		    $theme_dir = $CONFIG->dataroot . 'carousel/';
			
			$dimensions = getimagesize($_FILES["$item->guid:background"]['tmp_name']);
			$h = $dimensions[1]; 
		
		  	$x1 = 0;
			$x2 = $dimensions[0];
			if($h <= 800 || $size_info['h'] != 800){
				$y1 = 0;
				$y2 = $h;
			} else {
				$y1 = $h/3;
				$y2 = $h-($h/3);
			}
			$resized = get_resized_image_from_existing_file($_FILES["$item->guid:background"]['tmp_name'], $size_info['w'], $size_info['h'], $size_info['square'], $x1, $y1, $x2, $y2, $size_info['upscale'], 'jpeg', 80);
		
			if ($resized) {
				@mkdir($theme_dir);
	                
				file_put_contents($theme_dir . $item->guid . $name, $resized);
	                
			//	elgg_set_plugin_setting('logo_override', 'true', 'minds_themeconfig');
				//elgg_set_plugin_setting('logo_override_ts', time(), 'minds_themeconfig');
			}
	
			if (isset($_FILES["$item->guid:background"]) && ($_FILES["$item->guid:background"]['error'] != UPLOAD_ERR_NO_FILE) && $_FILES["$item->guid:title"]['error'] != 0) {
			//	register_error(minds_themeconfig_codeToMessage($_FILES['logo']['error'])); // Debug uploads
			}
		   	$item->last_updated = time(); 
			$item->background  = true;
	    }
		$item->save();
	//}
}

forward(REFERRER);
