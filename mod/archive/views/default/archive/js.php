elgg.provide('archive');

archive.init = function(){
	console.log('testing');
		$('video,audio').mediaelementplayer({
			// enables Flash and Silverlight to resize to content size
			enableAutosize: false,
			// the order of controls you want on the control bar (and other plugins below)
			features: ['playpause','progress','current','duration','tracks','volume','fullscreen'],
			alwaysShowControls: true
	});
}

elgg.register_hook_handler('init', 'system', archive.init);
