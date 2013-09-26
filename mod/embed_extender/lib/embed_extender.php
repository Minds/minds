<?php
function embed_extender_rewrite($hook, $entity_type, $returnvalue, $params){
	$view = $params['view'];
	$context = elgg_get_context();

	return embed_extender_parser(' ' . $returnvalue . ' ', $view, $context);
}

function embed_extender_parser($input, $view, $context)
{
	$allowed_contexts = array('blog', 'messageboard', 'widgets', 'pages', 'bookmarks', 'file', 'event_calendar', 'photos', 'polls');
	if (($view == 'annotation/generic_comment' || $view == 'annotation/default') && !in_array($context, $allowed_contexts)){
		return $input;
	}
	
	
	if ($context == 'widgets' || $context == 'profile'){
		$width = 240; //Size for widgets and messageboard
	}
	else{
		$width = elgg_get_viewtype() == 'mobile' ? 200 : 515;			
	}
	//if this is a comment then we need to change the size so it fits.	
	if($view == 'object/hjannotation'){
		$width = 410;
	}
	$patterns = array('#(((http://)?)|(^./))(((www.)?)|(^./))youtube\.com/watch[?]v=([^\[\]()<.,\s\n\t\r]+)#i'
						,'#(((http://)?)|(^./))(((www.)?)|(^./))youtu\.be/([^\[\]()<.,\s\n\t\r]+)#i'
						,'/(http:\/\/)(www\.)?(vimeo\.com\/groups)(.*)(\/videos\/)([0-9]*)/'
						,'/(http:\/\/)(www\.)?(metacafe\.com\/watch\/)([0-9a-zA-Z_-]*)(\/[0-9a-zA-Z_-]*)(\/)/'
						,'/(http:\/\/)(www\.)?(vimeo.com\/)([0-9]+)/'
						,'/(http:\/\/)(www\.)?(mehmac\.local\/minds\/archive\/show\/)([0-9]+)/'
						,'/(http:\/\/)(www\.)?(minds\.com\/archive\/show\/)([0-9]+)/'
						,'/(http:\/\/)(www\.)?(minds\.io\/archive\/show\/)([0-9]+)/');
	

	//Parses only hyperlinks
	$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
	
	//Replace video providers with embebed content
	if(preg_match_all("/$regexp/siU", $input, $matches, PREG_SET_ORDER)){
		foreach($matches as $match){
			if(empty($match[3])){ continue; }
			foreach ($patterns as $pattern){
				if (preg_match($pattern, $match[2]) > 0){
					$input = str_replace($match[0], videoembed_create_embed_object($match[2], uniqid('embed_'), $width, $match[0]), $input);
				}				
			}
			
			if($custom_provider == 'yes'){
				foreach ($customPatterns as $pattern){
					if (preg_match($pattern, $match[2]) > 0){
						$input = str_replace($match[0], custom_videoembed_create_embed_object($match[2], uniqid('embed_'), $width, $match[0]), $input);
					}				
				}
			}
		}
	}
	
	return $input;
}
