<?php
/**
 * AngularJS uploader first pgae which load the angularJS.
 */

// Include libraries from kaltura_upload original module.
elgg_set_context('upload');
elgg_load_library('archive:kaltura');
elgg_load_library('archive:kaltura:editor');

// Loading js's
elgg_load_js('angular.min.js');
elgg_load_js('jquery.ui.widget.js');
elgg_load_js('jquery.fileupload.js');
elgg_load_js('jquery.iframe-transport.js');

// Load directives
elgg_load_js('kaltura-thumbnail.js');

// Load controllers
elgg_load_js('UploadController.js');

// Load services
elgg_load_js('KalturaService.js');
elgg_load_js('ElggService.js');

elgg_load_js('app.js');
// include css
elgg_load_css('appstyle.css');

$user_guid = elgg_get_logged_in_user_guid();

$angularRoot = elgg_get_site_url() . 'mod/archive/angular/app';
$templatesPath = $angularRoot . '/partials';

$angularSettings = array(
    'templates_path' => $templatesPath
);

//create album
elgg_register_menu_item('title', array('name'=>'upload', 'text'=>elgg_echo('minds:archive:album:create'), 'href'=>'archive/upload/album/create','class'=>'elgg-button elgg-button-action'));

$content = elgg_view('archive/angularJS_upload');
$body = elgg_view_layout("gallery", array(	
					'content' => $content,
   					'sidebar' => false,
   		 			'title' => false,
   		 			'header' => false,
					'filter'=>'',
				));

// Display page
echo elgg_view_page(elgg_echo('archive:upload'),$body);
