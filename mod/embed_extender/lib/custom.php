<?php
	//Embed Extender Custom Library

	//Custom patterns - You can add your own regular expressions allowing new content providers
	function return_custom_patterns(){
		$customPatterns = array('/(http:\/\/)?(video\.yahoo\.com\/\?vid=([0-9]*))/');
		
		return $customPatterns;
	}

	//Generic embed functions - Don´t touch here!
	function custom_videoembed_create_embed_object($url, $guid, $videowidth=0) {

		if (!isset($url)) {
			return '<p><b>' . elgg_echo('embedvideo:novideo') . '</b></p>';
		}

		if (strpos($url, 'yahoo.com') != false) {
			return custom_videoembed_yahoo_handler($url, $guid, $videowidth);
		} else {
			return '<p><b>' . elgg_echo('embedvideo:unrecognized') . '</b></p>';
		}
	}
	
	//Generic embed functions - You can add your embed code here
	function custom_videoembed_add_object($type, $url, $guid, $width, $height) {
		$videodiv = "<div id=\"embedvideo{$guid}\" class=\"custom_videoembed_video\">";

		
		switch ($type) {
			case 'yahoo':
				$videodiv .= "<embed src=\"http://d.yimg.com/nl/cbe/paas/player.swf\" wmode=\"opaque\" flashvars=\"vid={$url}\" type=\"application/x-shockwave-flash\" width=\"$width\" height=\"$height\"></embed>";
				break;
			//You can add new embed code here
		}

		$videodiv .= "</div>";

		return $videodiv;
	}

	//
	//Yahoo videos - URL parser
	//
	function custom_videoembed_yahoo_handler($url, $guid, $videowidth) {
		// this extracts the core part of the url needed for embeding
		$videourl = custom_videoembed_yahoo_parse_url($url);
		
		if (!isset($videourl)) {
			return '<p><b>' . sprintf(elgg_echo('embedvideo:parseerror'), 'yahoo') . '</b></p>';
		}
		
		videoembed_calc_size($videowidth, $videoheight, 425/320, 24);

		$embed_object = videoembed_add_css($guid, $videowidth, $videoheight);

		$embed_object .= custom_videoembed_add_object('yahoo', $videourl, $guid, $videowidth, $videoheight);

		return $embed_object;
	}

	function custom_videoembed_yahoo_parse_url($url) {
		if (!preg_match('/(http:\/\/)?(video\.yahoo\.com\/\?vid=([0-9]*))/', $url, $matches)) {
			return;
		}	
		return $matches[3];
	}	
	//
	//
	//

?>