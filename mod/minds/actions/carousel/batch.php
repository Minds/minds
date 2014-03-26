<?php

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
	'subtype' => 'carousel_item'
));

foreach($items as $k=>$item){
	
	if(get_input("delete:$item->guid")){
			$item->delete();
			unset($items[$k]);
			continue;
	}
	
	$item->title = str_replace("$item->guid:", '', get_input("$item->guid:title"));
	$item->order = str_replace("$item->guid:", '', get_input("$item->guid:order"));
	
	//Upload and compress
	//if(isset($file["$item->guid:title"]['tmp_name'])){
		$files = array();
		$sizes = array(
			'default' => array(
	            'w' => 2000,
	            //'h' => 400,
	            'square' => false,
	            'upscale' => true
			));
	    foreach ($sizes as $name => $size_info) {
		   
		    global $CONFIG;
		    $theme_dir = $CONFIG->dataroot . 'carousel/';
			
			$dimensions = getimagesize($_FILES["$item->guid:background"]['tmp_name']);
			$h = $dimensions[1]; 
		
		  //  $resized = get_resized_image_from_uploaded_file("$item->guid:background", $size_info['w'], $size_info['h'], $size_info['square'], $size_info['upscale'], 'png');
		  	$x1 = 0;
			$x2 = 2000;
			$y1 = $h/3;
			$y2 = ($h/3)+400;
			$resized = get_resized_image_from_existing_file($_FILES["$item->guid:background"]['tmp_name'], $size_info['w'], $size_info['h'], $size_info['square'], $x1, $y1, $x2, $y2, $size_info['upscale'], 'jpeg', 60);
			if ($resized) {
				@mkdir($theme_dir);
	                
				file_put_contents($theme_dir . $item->guid.'.jpg', $resized);
	                
			//	elgg_set_plugin_setting('logo_override', 'true', 'minds_themeconfig');
				//elgg_set_plugin_setting('logo_override_ts', time(), 'minds_themeconfig');
			}
	
			if (isset($_FILES["$item->guid:background"]) && ($_FILES["$item->guid:background"]['error'] != UPLOAD_ERR_NO_FILE) && $_FILES["$item->guid:title"]['error'] != 0) {
			//	register_error(minds_themeconfig_codeToMessage($_FILES['logo']['error'])); // Debug uploads
			}
		    
			$item->background  = true;
	    }
		$item->save();
	//}
}

forward(REFERRER);