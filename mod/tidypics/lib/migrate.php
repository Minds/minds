<?php
/**
 * Tidypics file plugin migration
 *
 * Supports moving photos from the files plugin to Tidypics. All of a users
 * photos end up in a single album.
 *
 * Not supported
 */

// need access to ElggDiskFilestore::make_file_matrix(), which is protected.
// this is a PITA.
class tempFilestore extends ElggDiskFilestore {
	public function make_file_matrix($filename) {
		return parent::make_file_matrix($filename);
	}

}
$filestore = new tempFilestore();



/**
 * Migrates all pics from files to tidypics.
 *
 */
function tidypics_migrate_pics() {
	$limit = 100;
	$r = true;

	// migrate
	// @todo: should this return false since there was no error?
	if (!$users = tidypics_get_user_guids_with_pics_in_files(0, $limit)) {
		return $r;
	}

	//echo "Grabbed " . count($users) . " users\n";
	while (is_array($users) AND count($users) > 0) {
		foreach ($users as $user_guid) {
			// reset the query cache.
			$DB_QUERY_CACHE = array();
			if (!$user = get_entity($user_guid)) {
				continue;
			}

			$r = tidypics_migrate_user_pics($user);
		}

		//echo "Trying to grab $limit more users...\n";
		$offset = $offset + $limit;
		$users = tidypics_get_user_guids_with_pics_in_files($offset, $limit);
	}

	return $r;
}


/**
 * Migrates all pictures owned by a user regardless of
 * if they're group or user files.
 * 
 * @param ElggUser $user User to migrate.
 * @return bool on success
 */
function tidypics_migrate_user_pics(ElggUser $user) {
	global $CONFIG, $filestore;

	$user_guid = $user->getGUID();

	// update all entity subtypes in a single go at the end.
	$updated_guids = array();

	if (!$pics = tidypics_get_user_pics_from_files($user_guid) OR count($pics) < 1) {
		return false;
	}

	//echo "{$user->name} ({$user->getGUID()}) has " . count($pics) . " pics.\n";
	
	// get an album to migrate into if it already exists.
	// will create later on if it doesn't.
	$user_album_entities = get_entities_from_metadata('migrated_from_files', true, 'object', 'album', $user->getGUID(), 1);
	$user_album_guid = isset($album_entities[0]) ? $album_entities[0]->getGUID() : false;

	// a list of albums to randomly select a cover for on newly created albums.
	$new_album_guids = array();

	foreach ($pics as $pic) {
		// check that it's not already in tidy pics
		if (false !== strpos($pic->filename, 'image/')) {
			//echo "{$pic->filename} ({$pic->getGUID()}) looks like it's already in tidy pics. Ignoring.\n";
			continue;
		}
	
		// blank some vars
		$group_pic = $group_album_guid = $group_guid = false;
		
		// see if we're doing a group file migration.
		if ($pic->container_guid != $user->getGUID() 
			AND $group = get_entity($pic->container_guid)
			AND $group instanceof ElggGroup
		) {
			//echo "{$pic->getGUID()} is in a group!\n";
			$group_pic = true;
			$group_guid = $group->getGUID();
			
			// yes, this is how you get entities by container_guid.
			// yes, it's wrong, wrong, wrong for this function to work this way.
			$group_album_entities = get_entities('object', 'album', $group_guid);
			
			// get_entities_from_metadata doesn't support container_guid (or owner_guid meaning container_guid)
			// do it the hard way.
			if (is_array($group_album_entities)) {
				foreach ($group_album_entities as $group_album) {
					if ($group_album->migrated_from_files == true) {
						$group_album_guid = $group_album->getGUID();
						break;
					}
				}
			}
			$album_guid = $group_album_guid;
			$group_album_guids[] = $group_album_guid;
		} else {
			$album_guid = $user_album_guid;
		}
		
		//echo "album_guid is $album_guid and group_pic is: $group_pic\n";
		
		// create an album if we need to.
		if (!$album_guid) {
			//echo "Creating new album...\n";
			$album = new ElggObject();
			$album->subtype = 'album';
			$album->new_album = TP_NEW_ALBUM;
			
			if ($group_pic) {
				$album->container_guid = $group_guid;
				$album->owner_guid = $group->owner_guid;
				$album->access_id = $group->group_acl;
				$album->title = $group->name;
			} else {
				$album->container_guid = $user_guid;
				$album->owner_guid = $user->getGUID();
				$album->access_id = ACCESS_DEFAULT;
				$album->title = $user->name;
			}

			if (!$album->save()) {
				//echo "Couldn't migrate pics for {$user->name} ($user_guid)!\n";
				return false;
			}
			$album->migrated_from_files = true;
			$album_guid = $album->getGUID();
			$new_album_guids[] = $album_guid;
			
			// save the album guid as the users
			if (!$group_pic) {
				$user_album_guid = $album_guid;
			}
		}
		
		if (!tidypics_migrate_pic_from_files($pic, $album_guid)) {
			//echo "{$pic->filename} ({$pic->getGUID()}) Couldn't be migrated. Ignoring.\n";
			continue;
		}
	}

	// randomly pic an image to be the cover for the user gallery
	//$album->cover = $pic_guids[array_rand($pic_guids)];
	foreach ($new_album_guids as $guid) {
		tidypics_set_random_cover_pic($guid);
	}
	
	return true;
}


/**
 * Randomly pics an image from an album to be the cover.
 * @return bool on success
 */
function tidypics_set_random_cover_pic($album_guid) {
	global $CONFIG;
	
	if ($album = get_entity($album_guid) AND $album instanceof TidypicsAlbum) {
		$q = "SELECT guid FROM {$CONFIG->dbprefix}entities WHERE container_guid = $album_guid ORDER BY RAND() limit 1";
		$pic = get_data($q);
		
		return $album->cover = $pic[0]->guid;
	}
	
	return false;
}

/**
 * Migrates a single pic from the file repo.
 * @return bool on succes.
 */
function tidypics_migrate_pic_from_files($pic, $album_guid) {
	global $CONFIG, $filestore;

	// get the subtype id.
	$image_subtype_id = get_subtype_id('object', 'image');

	// hold which metadata on the files need to be changes
	// also holds the images we need to move
	$file_md_fields = array('filename', 'thumbnail', 'smallthumb', 'largethumb');

	if (!$user = get_entity($pic->owner_guid)) {
		return false;
	}

	// figure out where to move the files.
	$matrix = $filestore->make_file_matrix($user->username);
	$user_fs_path = $CONFIG->dataroot . $matrix;
	$album_fs_path = $CONFIG->dataroot . $matrix . "image/$album_guid/";
	if (!is_dir($album_fs_path)) {
		if (!mkdir($album_fs_path, 0700, true)) {
			return false;
		}
	}

	// change all the 'file/'s to 'image/'s in certain metadata
	// these are also the files we need to move.
	foreach ($file_md_fields as $md_name) {
		// $pic->$md_name = str_replace('file/', 'image/', $pic->$md_name);
		$old_file = $pic->$md_name;
		$new_file = str_replace('file/', "image/$album_guid", $old_file);

		if (!($old_fp = fopen($user_fs_path . $old_file, 'r') 
		AND $new_fp = fopen($user_fs_path . $new_file, 'w'))) {
			//echo "Could not move {$user_fs_path}{$old_file} to {$user_fs_path}{$new_file}\n";
			continue;
		}

		while (!feof($old_fp)) {
			if (!fputs($new_fp, fread($old_fp, 8192))) {
				//echo "Could not move {$user_fs_path}{$old_file} to {$user_fs_path}{$new_file} (Error writing.)\n";
				break;
			}
		}

		$pic->$md_name = $new_file;
	}
	// update container.
	// this doesn't work...?
	//$pic->container_guid = $album_guid;

	// delete old one.
	unlink($user_fs_path . $old_file);

	$q = "UPDATE {$CONFIG->dbprefix}entities SET subtype = $image_subtype_id, container_guid = $album_guid WHERE guid = {$pic->getGUID()}";
	//echo "Finished moving {$user_fs_path}{$old_file} to {$user_fs_path}{$new_file}\n";

	return update_data($q);
}


/**
 * Grabs all user IDs with images in the files repo.
 * return mixed. False on fail, array of GUIDs on success.
 */
function tidypics_get_user_guids_with_pics_in_files($offset, $limit) {
	global $CONFIG;
	
	//$simpletype_ms_id = add_metastring('simple_type');
	//$image_ms_id = add_metastring('image');
	
	$q = "SELECT DISTINCT e.owner_guid 
		FROM 
			{$CONFIG->dbprefix}entities as e, 
			{$CONFIG->dbprefix}entity_subtypes as st
			
		WHERE st.subtype = 'file'
		AND e.subtype = st.id
		LIMIT $offset, $limit";

	if (!$data = get_data($q)) {
		return false;
	}
	
	// return an array of IDs
	$r = array();
	foreach ($data as $row) {
		$r[] = $row->owner_guid;
	}

	return $r;
}

/**
 * Gets a list of images for a single user.
 * @return array of GUIDs, false on fail.
 */
function tidypics_get_user_pics_from_files($user_guid) {
	if (!$user = get_entity($user_guid) AND $user instanceof ElggUser) {
		return false;
	}

	// @todo Might have to cycle this through with standard while + foreach.
	return get_entities_from_metadata('simpletype', 'image', 'object', 'file', $user_guid, 5000);
}
