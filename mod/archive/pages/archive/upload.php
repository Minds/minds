<?php
/**
 * AngularJS uploader first pgae which load the angularJS.
 */

elgg_set_context('upload');

if(!elgg_is_logged_in()){
	forward('/register');
}

// Loading js's
elgg_load_js('angular.min.js');
elgg_load_js('angular-route.min.js');
elgg_load_js('jquery.ui.widget.js');
elgg_load_js('jquery.fileupload.js');
elgg_load_js('jquery.iframe-transport.js');
elgg_load_js('UploadController.js');
elgg_load_js('KalturaService.js');
elgg_load_js('ElggService.js');
elgg_load_js('app.js');

// include css
elgg_load_css('appstyle.css');
elgg_load_js('elgg.lightbox');
elgg_load_css('elgg.lightbox');

$user_guid = elgg_get_logged_in_user_guid();
//create album
elgg_register_menu_item('title', array(
	'name'=>'upload',
	'text'=>elgg_echo('minds:archive:album:create'), 
	'href'=>'archive/upload/album/create',
	'class'=>'elgg-button elgg-button-action'
));

$content = elgg_view('archive/upload');
$body = elgg_view_layout("one_column", array(	
	'content' => $content,
	'sidebar' => false,
	'title' => false,
	'header' => '',
	'filter'=>'',
));

// Display page
echo elgg_view_page(elgg_echo('archive:upload'),$body);
