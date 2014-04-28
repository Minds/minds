<?php

$english = array(
		// hack for core bug
			'untitled' => "untitled",

		// Menu items and titles
			'image' => "Image",
			'images' => "Images",
			'caption' => "Caption",
			'photos' => "Images",
			'album' => "Album",
			'albums' => "Albums",
			'tidypics:disabled' => 'Disabled',
			'tidypics:enabled' => 'Enabled',
			'admin:pluginsettings:photos' => 'Tidypics',

			'photos:add' => "Create album",
			'images:upload' => "Upload photos",

			'album:slideshow' => "Slideshow",
			'album:yours' => "Your photo albums",
			'album:yours:friends' => "Your friends' photo albums",
			'album:user' => "%s's photo albums",
			'album:friends' => "Friends' photo albums",
			'album:all' => "All site photo albums",
			'photos:group' => "Group photos",
			'item:object:image' => "Photos",
			'item:object:album' => "Albums",
			'tidypics:uploading:images' => "Please wait. Uploading images.",
			'tidypics:enablephotos' => 'Enable group photo albums',
			'tidypics:editprops' => 'Edit Image Properties',
			'tidypics:mostcommented' => 'Most commented images',
			'tidypics:mostcommentedthismonth' => 'Most commented this month',
			'tidypics:mostcommentedtoday' => 'Most commented today',
			'tidypics:mostviewed' => 'Most viewed images',
			'tidypics:mostvieweddashboard' => 'Most viewed dashboard',
			'tidypics:mostviewedthisyear' => 'Most viewed this year',
			'tidypics:mostviewedthismonth' => 'Most viewed this month',
			'tidypics:mostviewedlastmonth' => 'Most viewed last month',
			'tidypics:mostviewedtoday' => 'Most viewed today',
			'tidypics:recentlyviewed' => 'Recently viewed images',
			'tidypics:recentlycommented' => 'Recently commented images',
			'tidypics:mostrecent' => 'Most recent images',
			'tidypics:yourmostviewed' => 'Your most viewed images',
			'tidypics:yourmostrecent' => 'Your most recent images',
			'tidypics:friendmostviewed' => "%s's most viewed images",
			'tidypics:friendmostrecent' => "%s's most recent images",
			'tidypics:highestrated' => "Highest rated images",
			'tidypics:views' => "%s views",
			'tidypics:viewsbyowner' => "by %s users (not including you)",
			'tidypics:viewsbyothers' => "(%s by you)",
			'tidypics:administration' => 'Tidypics Administration',
			'tidypics:stats' => 'Stats',
			'tidypics:nophotosingroup' => 'This groups does not have any photos yet',
			'tidypics:upgrade' => 'Upgrade',
			'tidypics:sort' => 'Sorting the %s album',
			'tidypics:none' => 'No photo albums',
			
		//upload
			'tidypics:upload:error' => 'Error:',
			'tidypics:upload:maxfilesize' => 'File is too big',
			'tidypics:upload:minfilesize' => 'File is too small',
			'tidypics:upload:acceptfiletypes' => 'Filetype not allowed',
			'tidypics:upload:maxnumberoffiles' => 'Max number of files exceeded',
	
		//settings
			'tidypics:settings' => 'Settings',
			'tidypics:settings:main' => 'Primary settings',
			'tidypics:settings:image_lib' => "Image Library",
			'tidypics:settings:thumbnail' => "Thumbnail Creation",
			'tidypics:settings:help' => "Help",
			'tidypics:settings:download_link' => "Show download link",
			'tidypics:settings:tagging' => "Enable photo tagging",
			'tidypics:settings:photo_ratings' => "Enable photo ratings (requires rate plugin of Miguel Montes or compatible)",
			'tidypics:settings:exif' => "Display EXIF data",
			'tidypics:settings:view_count' => "Display view count",
			'tidypics:settings:grp_perm_override' => "Allow group members full access to group albums",
			'tidypics:settings:maxfilesize' => "Maximum image size in megabytes (MB):",
			'tidypics:settings:quota' => "User Quota (MB) - 0 equals no quota",
			'tidypics:settings:watermark' => "Enter text to appear in the watermark",
			'tidypics:settings:im_path' => "Enter the path to your ImageMagick commands",
			'tidypics:settings:img_river_view' => "How many entries in activity river for each batch of uploaded images",
			'tidypics:settings:album_river_view' => "Show the album cover or a set of photos for new album",
			'tidypics:settings:largesize' => "Primary image size",
			'tidypics:settings:smallsize' => "Album view image size",
			'tidypics:settings:tinysize' => "Thumbnail image size",
			'tidypics:settings:sizes:instructs' => 'You may need to change the CSS if you change the default sizes',
			'tidypics:settings:im_id' => "Image ID",
			'tidypics:settings:heading:img_lib' => "Image Library Settings",
			'tidypics:settings:heading:main' => "Major Settings",
			'tidypics:settings:heading:river' => "Activity Integration Options",
			'tidypics:settings:heading:sizes' => "Thumbnail Size",
			'tidypics:settings:heading:groups' => "Group Settings",
			'tidypics:option:all' => 'All',
			'tidypics:option:none' => 'None',
			'tidypics:option:cover' => 'Cover',
			'tidypics:option:set' => 'Set',

		// server analysis
			'tidypics:server_info' => 'Server Information',
			'tidypics:server_info:gd_desc' => 'Elgg requires the GD extension to be loaded',
			'tidypics:server_info:exec_desc' => 'Required for ImageMagick command line',
			'tidypics:server_info:memory_limit_desc' => 'Change memory_limit to increase',
			'tidypics:server_info:peak_usage_desc' => 'This is approximately the minimum per page',
			'tidypics:server_info:upload_max_filesize_desc' => 'Max size of an uploaded image',
			'tidypics:server_info:post_max_size_desc' => 'Max post size = sum of images + html form',
			'tidypics:server_info:max_input_time_desc' => 'Time script waits for upload to finish',
			'tidypics:server_info:max_execution_time_desc' => 'Max time a script will run',
			
			'tidypics:server_info:php_version' => 'PHP Version',
			'tidypics:server_info:memory_limit' => 'Memory Available to PHP',
			'tidypics:server_info:peak_usage' => 'Memory Used to Load This Page',
			'tidypics:server_info:upload_max_filesize' => 'Max File Upload Size',
			'tidypics:server_info:post_max_size' => 'Max Post Size',
			'tidypics:server_info:max_input_time' => 'Max Input Time',
			'tidypics:server_info:max_execution_time' => 'Max Execution Time',
			'tidypics:server_info:use_only_cookies' => 'Cookie only sessions',

			'tidypics:server_config' => 'Server Configuration',
			'tidypics:server_configuration_doc' => 'Server configuration documentation',

		// ImageMagick test
			'tidypics:lib_tools:testing' =>
	'Tidypics needs to know the location of the ImageMagick executables if you have selected it
	as the image library. Your hosting service should be able to provide this to you. You can test
	if the location is correct below. If successful, it should display the version of ImageMagick installed
	on your server.',

	// thumbnail tool
			'tidypics:thumbnail_tool' => 'Thumbnail Creation',
			'tidypics:thumbnail_tool_blurb' => 
	'This page allows you to create thumbnails for images when the thumbnail creation failed during upload.
	You may experience problems with thumbnail creation if your image library is not configured properly or
	if there is not enough memory for the GD library to load and resize an image. If your users have
	experienced problems with thumbnail creation and you have corrected your configuration, you can try to redo the
	thumbnails. Find the unique identifier of the photo (it is the number near the end of the url when viewing
	a photo) and enter it below.',
			'tidypics:thumbnail_tool:unknown_image' => 'Unable to get original image',
			'tidypics:thumbnail_tool:invalid_image_info' => 'Error retrieving information about the image',
			'tidypics:thumbnail_tool:create_failed' => 'Failed to create thumbnails',
			'tidypics:thumbnail_tool:created' => 'Created thumbnails.',

		//actions
			'album:create' => "Create new album",
			'album:add' => "Add Photo Album",
			'album:addpix' => "Add photos to album",
			'album:edit' => "Edit album",
			'album:delete' => "Delete album",
			'album:sort' => "Sort",
			'image:edit' => "Edit image",
			'image:delete' => "Delete image",
			'image:download' => "Download image",

		//forms
			'album:title' => "Title",
			'album:desc' => "Description",
			'album:tags' => "Tags",
			'album:cover' => "Make this image the album cover?",
			'album:cover_link' => 'Make cover',
			'tidypics:title:quota' => 'Quota',
			'tidypics:quota' => "Quota usage:",
			'tidypics:uploader:choose' => "Choose photos",
			'tidypics:uploader:upload' => "Upload photos",
			'tidypics:uploader:describe' => "Describe photos",
			'tidypics:uploader:filedesc' => 'Image files (jpeg, png, gif)',
			'tidypics:uploader:help' => 'Tip: use <code>Ctrl</code> or <code>Shift</code> keys to select more than one file. You also can drag&drop photos from desktop.',
			'tidypics:sort:instruct' => 'Sort the album photos by dragging and dropping the images. Then click the save button.',
			'tidypics:sort:no_images' => 'No images found to sort. Upload images using the link above.',

		// albums
			'album:num' => '%s photos',

		//views
			'image:total' => "Images in album:",
			'image:by' => "Image added by",
			'album:by' => "Album created by",
			'album:created:on' => "Created",
			'image:none' => "No images have been added yet.",
			'image:back' => "Previous",
			'image:next' => "Next",
			'image:index' => "%u of %u",

		// tagging
			'tidypics:taginstruct' => 'Select the area that you want to tag or %s',
			'tidypics:finish_tagging' => 'Stop tagging',
			'tidypics:tagthisphoto' => 'Tag this photo',
			'tidypics:actiontag' => 'Tag',
			'tidypics:actioncancel' => 'Cancel',
			'tidypics:inthisphoto' => 'In this photo',
			'tidypics:usertag' => "Photos tagged with user %s",
			'tidypics:phototagging:success' => 'Photo tag was successfully added',
			'tidypics:phototagging:error' => 'Unexpected error occurred during tagging',

			'tidypics:phototagging:delete:success' => 'Photo tag was removed.',
			'tidypics:phototagging:delete:error' => 'Unexpceted error occurred when removing photo tag.',
			'tidypics:phototagging:delete:confirm' => 'Remove this tag?',



			'tidypics:tag:subject' => "You have been tagged in a photo",
			'tidypics:tag:body' => "You have been tagged in the photo %s by %s.			
			
The photo can be viewed here: %s",


		//rss
			'tidypics:posted' => 'posted a photo:',

		//widgets
			'tidypics:widget:albums' => "Photo Albums",
			'tidypics:widget:album_descr' => "Showcase your photo albums",
			'tidypics:widget:num_albums' => "Number of albums to display",
			'tidypics:widget:latest' => "Latest Photos",
			'tidypics:widget:latest_descr' => "Display your latest photos",
			'tidypics:widget:num_latest' => "Number of images to display",
			'album:more' => "View all albums",

		//  river
			'river:create:object:image' => "%s uploaded the photo %s",
			'image:river:created' => "%s added a photo to the album %s",
			'image:river:created:multiple' => "%s added %u photos to the album %s",
			'image:river:item' => "a photo",
			'image:river:annotate' => "a comment on the photo",
			'image:river:tagged' => "%s tagged %s in the photo %s",
			'image:river:tagged:unknown' => "%s tagged %s in a photo",
			'river:create:object:album' => "%s created a new photo album %s",
			'album:river:group' => "in the group",
			'album:river:item' => "an album",
			'album:river:annotate' => "a comment on the photo album",
			'river:comment:object:image' => '%s commented on the photo %s',
			'river:comment:object:album' => '%s commented on the album %s',
			'river:remind:object:tidypics_batch' => '%s reMinded %s\'s images',
			'river:remind:object:image' => '%s reMinded %s\'s image: %s',
			'river:remind:object:album' => '%s reMinded %s\'s album: %s',

		// notifications
			'tidypics:newalbum_subject' => 'New photo album',
			'tidypics:newalbum' => '%s created a new photo album',
			'tidypics:updatealbum' => "%s uploaded new photos to the album %s",

		//  Status messages
			'tidypics:upl_success' => "Your images uploaded successfully.",
			'image:saved' => "Your image was successfully saved.",
			'images:saved' => "All images were successfully saved.",
			'image:deleted' => "Your image was successfully deleted.",
			'image:delete:confirm' => "Are you sure you want to delete this image?",
			'images:edited' => "Your images were successfully updated.",
			'album:edited' => "Your album was successfully updated.",
			'album:saved' => "Your album was successfully saved.",
			'album:deleted' => "Your album was successfully deleted.",
			'album:delete:confirm' => "Are you sure you want to delete this album?",
			'album:created' => "Your new album has been created.",
			'album:save_cover_image' => 'Cover image saved.',
			'tidypics:settings:save:ok' => 'Successfully saved the Tidypics plugin settings',
			'tidypics:album:sorted' => 'The album %s is sorted',
			'tidypics:album:could_not_sort' => 'Could not sort the album %s. Make sure there are images in the album and try again.',
			'tidypics:upgrade:success' => 'Upgrade of Tidypics a success',

		//Error messages
			'tidypics:baduploadform' => "There was an error with the upload form",
			'tidypics:partialuploadfailure' => "There were errors uploading some of the images (%s of %s images).",
			'tidypics:completeuploadfailure' => "Upload of images failed.",
			'tidypics:exceedpostlimit' => "Too many large images - try to upload fewer or smaller images.",
			'tidypics:noimages' => "No images were selected.",
			'tidypics:image_mem' => "Image is too large - too many bytes",
			'tidypics:image_pixels' => "Image has too many pixels",
			'tidypics:unk_error' => "Unknown upload error",
			'tidypics:save_error' => "Unknown error saving the image on server",
			'tidypics:not_image' => "This is not a recognized image type",
			'tidypics:deletefailed' => "Sorry. Deletion failed.",
			'tidypics:deleted' => "Successful deletion.",
			'tidypics:nosettings' => "Admin of this site has not set photo album settings.",
			'tidypics:exceed_quota' => "You have exceeded the quota set by the administrator",
			'tidypics:cannot_upload_exceeds_quota' => 'Image not uploaded. File size exceeds available quota.',

			'album:none' => "No albums have been created yet.",
			'album:uploadfailed' => "Sorry; we could not save your album.",
			'album:deletefailed' => "Your album could not be deleted.",
			'album:blank' => "Please give this album a title.",
			'album:invalid_album' => 'Invalid album',
			'album:cannot_save_cover_image' => 'Cannot save cover image',

			'image:downloadfailed' => "Sorry; this image is not available.",
			'images:notedited' => "Not all images were successfully updated",
			'image:blank' => 'Please give this image a title.',
			'image:error' => 'Could not save image.',

			'tidypics:upgrade:failed' => "The upgrade of Tidypics failed", 
);

add_translation("en", $english);
