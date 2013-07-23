<?php
/**
 * AngularJS uploader first pgae which load the angularJS.
 */

// Include libraries from kaltura_upload original module.
elgg_set_context('upload');
elgg_load_library('archive:kaltura');
elgg_load_library('archive:kaltura:editor');

// Loading js's
// Load libraries
//    elgg_load_js('kaltura.js');
//    elgg_load_js(array('angular' => $angularSettings), 'setting');
elgg_load_js('angular.min.js');
elgg_load_js('bootstrap.min.js');
elgg_load_js('jquery.ui.widget.js');
elgg_load_js('jquery.fileupload.js');
elgg_load_js('jquery.iframe-transport.js');
//    elgg_load_js('http://player.kaltura.com/mwEmbedLoader.php', 'external');

// Load directives
elgg_load_js('kaltura-embed.js');
elgg_load_js('kaltura-upload.js');
elgg_load_js('kaltura-thumbnail.js');

// Load controllers
elgg_load_js('UploadController.js');
elgg_load_js('GalleryController.js');

// Load services
elgg_load_js('NodeService.js');
elgg_load_js('KalturaService.js');
elgg_load_js('ElggService.js');

elgg_load_js('app.js');
// include css
elgg_load_css('appstyle.css');
elgg_load_css('bootstrap.min.css');



$user_guid = elgg_get_logged_in_user_guid();
$container_guid = $user_guid;
if($page_owner = elgg_get_page_owner_entity()) {
    if($page_owner instanceof ElggGroup) {
        $container_guid = $page_owner->getGUID();
    }
}
$kmodel = KalturaModel::getInstance();

$ks = $kmodel->getClientSideSession();
$serviceUrl = elgg_get_plugin_setting('kaltura_server_url', 'archive');

$angularRoot = elgg_get_site_url() . 'mod/archive/angular/app';
$templatesPath = $angularRoot . '/partials';

$angularSettings = array(
    'templates_path' => $templatesPath
);

$content = elgg_view('archive/angularJS_upload');
$body = elgg_view_layout("one_column", array(
    'content' => $content,
    'sidebar' => false,
    'filter_override' => elgg_view('page/layouts/content/archive_filter', $vars),
    'title' => elgg_echo('angularUpload')
));

// Display page
echo elgg_view_page(elgg_echo('angularUpload'),$body);
