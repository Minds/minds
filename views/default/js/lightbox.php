<?php
/**
 * Elgg lightbox
 *
 * Usage
 * Call elgg_load_js('lightbox') and elgg_load_css('lightbox') then
 * apply the class elgg-lightbox to links.
 *
 * Advanced Usage
 * Elgg is distributed with the Fancybox jQuery library. Please go to
 * http://fancybox.net for more information on the options of this lightbox.
 *
 * Overriding
 * In a plugin, override this view and override the registration for the
 * lightbox JavaScript and CSS (@see elgg_views_boot()).
 *
 * @todo add support for passing options: $('#myplugin-lightbox').elgg.ui.lightbox(options);
 */

$js_path = elgg_get_config('path');
$js_path = "{$js_path}vendors/jquery/fancybox/source/jquery.fancybox.pack.js";
include $js_path;

if (0) { ?><script><?php }
?>

/**
 * Lightbox initialization
 */
elgg.ui.lightbox_init = function() {
//	$(document).on('click','.elgg-lightbox', function(e){
//		e.preventDefault();
//		$.fancybox({type:'ajax',href: $(this).attr('href')});
//	});
	$(".elgg-lightbox").fancybox({type:'ajax'});
}

elgg.register_hook_handler('init', 'system', elgg.ui.lightbox_init);

