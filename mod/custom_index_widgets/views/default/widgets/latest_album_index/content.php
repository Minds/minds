<div class="contentWrapper"> 
<?php

	$object_type ='album';
	
	$num_items = $vars['entity']->num_items;
	if (!isset($num_items))
	    $num_items = 10;
	
	$widget_group = $vars["entity"]->widget_group;
	if (!isset($widget_group))
	    $widget_group = 0;
	
	$site_categories = $vars['config']->site->categories;
	$widget_categorie = $vars['entity']->widget_categorie;
	$widget_context_mode = $vars['entity']->widget_context_mode;
	if (!isset($widget_context_mode))
	    $widget_context_mode = 'search';
	elgg_set_context($widget_context_mode);

	$owner_albums = elgg_get_entities("object", $object_type, $widget_group, "", $num_items, 0, false);
	
	echo '<div id="tidypics_album_widget_container">';
	
	if ($owner_albums) { 
		foreach($owner_albums as $album) {
	
			if($album->cover)
				$album_cover = '<img src="'.$vars['url'].'mod/tidypics/thumbnail.php?file_guid='.$album->cover.'&size=small"  class="tidypics_album_cover"  alt="' . $album->title . '"/>';
			else
				$album_cover = '<img src="'.$vars['url'].'mod/tidypics/graphics/empty_album.png" class="tidypics_album_cover" alt="' . $album->title . '">';
?>
		<div class="tidypics_album_widget_single_item">
			<div class="tidypics_album_widget_title"><a href="<?php echo $album->getURL();?>"><?php echo $album->title;?></a></div>
			<div class="tidypics_album_widget_timestamp"> <?php echo elgg_echo("album:created:on") . ' ' . friendly_time($album->time_created);?></div>
<?php
			//get the number of comments
			$numcomments = elgg_count_comments($album);
			if ($numcomments)
				echo "<a href=\"{$album->getURL()}\">" . sprintf(elgg_echo("comments")) . " (" . $numcomments . ")</a><br>";
?>
			<a href="<?php echo $album->getURL();?>"><?php echo $album_cover;?></a>
		</div>
<?php
		} //end of foreach loop

		// bottom link to all group/user albums
		/*if (is_null($owner->username) || empty($owner->username)) {
			echo '<p class="profile_info_edit_buttons"><a href="' . $vars['url'] . 'pg/photos/world">' . elgg_echo('album:all') . '</a></p>';
		} else {
			echo '<p class="tidypics_download"><a href="' . $vars['url'] . 'pg/photos/owned/' . $owner->username . '">' . elgg_echo('album:more') . '</a></p>';
		}*/

	}

	/*if (can_write_to_container(0, $owner->guid)) {
		echo '<p class="tidypics_download"><a href=' . $CONFIG->wwwroot .'pg/photos/new/' . $owner->username . '>' . elgg_echo("album:create") . '</a></p>';
	}*/


	//close album_widget_container div
	echo "</div>";
?>
</div>