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
													$(this).parent().find('span').remove();
													});
	$('body').on('click', '.videoembed_video span', function(e,oembed){
		$(this).parent().find('img').trigger('click');
	});
}

elgg.register_hook_handler('init', 'system', elgg.embed.init);