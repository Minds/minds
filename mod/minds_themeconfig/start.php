<?php

elgg_register_event_handler('init', 'system', 'themeconfig_init',99999);

function themeconfig_init() {
    global $CONFIG;

    elgg_register_admin_menu_item('configure', 'theme', 'appearance');
    elgg_register_admin_menu_item('configure', 'css', 'appearance');
    elgg_register_admin_menu_item('configure', 'fonts', 'appearance');
    elgg_register_admin_menu_item('configure', 'themesets', 'appearance');
    elgg_register_admin_menu_item('configure', 'footer', 'appearance');
    elgg_register_admin_menu_item('configure', 'ads', 'monitization');

    elgg_register_action('theme/edit', dirname(__FILE__) . '/actions/edit.php', 'admin');
    elgg_register_action('theme/fonts', dirname(__FILE__) . '/actions/fonts.php', 'admin');
    elgg_register_action('theme/advanced_css', dirname(__FILE__) . '/actions/advanced_css.php', 'admin');
    elgg_register_action('footer/edit', dirname(__FILE__) . '/actions/footer/edit.php', 'admin');
    elgg_register_action('themesets/edit', dirname(__FILE__) . '/actions/themesets/edit.php', 'admin');
    elgg_register_action('ads/edit', dirname(__FILE__) . '/actions/ads/edit.php', 'admin');

    elgg_extend_view('page/elements/footer', 'minds_themeconfig/footer');

    elgg_register_page_handler('themeicons', 'themeicons_page_handler');

    elgg_register_event_handler('pagesetup', 'system', function() {
        // Extend the css (only if it's not the admin page)
        if (elgg_get_context() != 'admin')
	    elgg_extend_view('page/elements/head', 'minds_themeconfig/css');
	
	// Add colour picker
	if (elgg_get_context() == 'admin')
	    elgg_extend_view('page/elements/head', 'minds_themeconfig/colourpicker');
	
	
	
	elgg_extend_view('page/elements/ads', 'minds_themeconfig/ads');
	elgg_unextend_view('page/elements/ads', 'minds/ads'); //remove the default ads
    }, 999);

    elgg_register_event_handler('pagesetup', 'system', 'minds_themeconfig_setup');
    
    // Configure font elements that we can set
    $CONFIG->theme_fonts = array(
	'header' => 'h2',
	'paragraph' => 'p',
    );
    
    $url = elgg_get_simplecache_url('css', 'minds_themeconfig');
    elgg_register_css('minds.themeconfig', $url);
    elgg_load_css('minds.themeconfig');
}

function themeicons_page_handler($pages) {

    global $CONFIG;

    switch ($pages[0]) {
        case 'background':
            $theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';

            $contents = file_get_contents($theme_dir . 'background');
            header("Content-type: " . elgg_get_plugin_setting('background_override_mime', 'minds_themeconfig'));
            //header('Expires: ' . date('r', strtotime("+6 months")), true);
            header("Last-Modified: " . date('r', elgg_get_plugin_setting('background_override_ts', 'minds_themeconfig')));
            header("Pragma: public");
            header("Cache-Control: public");
            header("Content-Length: " . strlen($contents));

            break;
        case 'logo_main' :
        case 'logo_topbar' :

            $theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';

            $contents = file_get_contents($theme_dir . $pages[0] . '.png');
            // header_remove();
            header("Content-Type: image/png");
            //header('Expires: ' . date('r', strtotime("+6 months")), true);
            header("Last-Modified: " . date('r', elgg_get_plugin_setting('logo_favicon_ts', 'minds_themeconfig')));
            header("Pragma: public");
            header("Cache-Control: public");
            header("Content-Length: " . strlen($contents));
            break;

        case 'logo_favicon' :
            $theme_dir = $CONFIG->dataroot . 'minds_themeconfig/';

            $contents = file_get_contents($theme_dir . $pages[0] . '.jpg');
            // header_remove();
            header("Content-Type: image/jpeg");
            //header('Expires: ' . date('r', strtotime("+6 months")), true);
            header("Last-Modified: " . date('r', elgg_get_plugin_setting('logo_override_ts', 'minds_themeconfig')));
            header("Pragma: public");
            header("Cache-Control: public");
            header("Content-Length: " . strlen($contents));
            break;
    }

    if ($contents) {

        $split_string = str_split($contents, 1024);
        foreach ($split_string as $chunk) {
            echo $chunk;
        }
        exit;
    }
}

function minds_themeconfig_codeToMessage($code) {
    switch ($code) {
        case UPLOAD_ERR_INI_SIZE:
            $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
            break;
        case UPLOAD_ERR_PARTIAL:
            $message = "The uploaded file was only partially uploaded";
            break;
        case UPLOAD_ERR_NO_FILE:
            $message = "No file was uploaded";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $message = "Missing a temporary folder";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $message = "Failed to write file to disk";
            break;
        case UPLOAD_ERR_EXTENSION:
            $message = "File upload stopped by extension";
            break;

        default:
            $message = "Unknown upload error";
            break;
    }
    return $message;
}

function minds_config_social_links() {
    return array('facebook' => array('url' => elgg_get_plugin_setting('facebook:url', 'minds_themeconfig'), 'icon' => '&#62221;'),
        'twitter' => array('url' => elgg_get_plugin_setting('twitter:url', 'minds_themeconfig'), 'icon' => '&#62218;'),
        'gplus' => array('url' => elgg_get_plugin_setting('gplus:url', 'minds_themeconfig'), 'icon' => '&#62224;'),
        'linkedin' => array('url' => elgg_get_plugin_setting('linkedin:url', 'minds_themeconfig'), 'icon' => '&#62233;'),
        'tumblr' => array('url' => elgg_get_plugin_setting('tumlr:url', 'minds_themeconfig'), 'icon' => '&#62230;'),
        'pinterest' => array('url' => elgg_get_plugin_setting('pinterest:url', 'minds_themeconfig'), 'icon' => '&#62227;'),
        'vimeo' => array('url' => elgg_get_plugin_setting('vimeo:url', 'minds_themeconfig'), 'icon' => '&#62215;'),
        'github' => array('url' => elgg_get_plugin_setting('github:url', 'minds_themeconfig'), 'icon' => '&#62208;')
    );
}

/**
 * Load the themesets
 * 
 * @return void
 */
function minds_themeconfig_setup() {
    $themeset = elgg_get_plugin_setting('themeset', 'minds_themeconfig') ? : 'minds-default';
    $themeset_dir = elgg_get_plugins_path() . "minds_themeconfig/themesets/$themeset/";
    $views = elgg_get_views("$themeset_dir/default", '');

    foreach ($views as $view) {
        $view = substr($view, 1);
        if (file_exists("$themeset_dir/default/$view.php")) {
            elgg_set_view_location($view, $themeset_dir);
        }
    }
}

/**
 * Return themesets
 * @return array
 */
function minds_themeconfig_get_themesets() {
    $dir = elgg_get_plugins_path() . "minds_themeconfig/themesets/";
    $themesets = array();
    if ($handle = opendir($dir)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != "..") {
                $themesets[] = $entry;
            }
        }
    }
    closedir($handle);
    return $themesets;
}

/**
 * Return themeset icon
 */
function minds_themeconfig_get_themeset_icon($themeset) {
    return elgg_get_site_url() . "mod/minds_themeconfig/themesets/$themeset/screenshot.png";
}
