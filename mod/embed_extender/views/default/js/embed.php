elgg.provide('elgg.embed');

elgg.embed.init = function() {

	$('body').on('click', '.oembed', function(e, oembed){ 
													//$(this).load(elgg.config.wwwroot + 'archive/inline/' + $(this).attr('video_id'));
													$(this).oembed(	$(this).attr('oembed_url'), 
																		{	maxWidth: $(this).attr('width'),
																			autoplay: true,
																			vimeo: { autoplay: true},
																			youtube: { autoplay: true}
																		}
																);
													});
}

elgg.register_hook_handler('init', 'system', elgg.embed.init);