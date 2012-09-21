elgg.provide('elgg.uiVideoInline');

elgg.uiVideoInline.init = function() {
	//Archive inlines
	$('body').on('click', '.uiVideoInline.archive', function(e){ 
													$(this).load(elgg.config.wwwroot + 'archive/inline/' + $(this).attr('video_id'));
													});
	//Social embeds inline											
	$('body').on('click', '.uiVideoInline.links', function(e){ 
													$(this).load(elgg.config.wwwroot + 'archive/inline/0_hgbr3muj');
													});
}

elgg.register_hook_handler('init', 'system', elgg.uiVideoInline.init);