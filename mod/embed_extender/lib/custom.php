<?php
	//Embed Extender Custom Library

	//Custom patterns - You can add your own regular expressions allowing new content providers
	function return_custom_patterns(){
		// allow other plugins to supply custom patterns without editing this plugin
		return elgg_trigger_plugin_hook('embed_extender', 'custom_patterns', array(), array());
	}

	//Generic embed functions - Don�t touch here!
	function custom_videoembed_create_embed_object($url, $guid, $videowidth=0, $input) {
		
		if (!$input) {
			$input = '<p><b>' . elgg_echo('embedvideo:unrecognized') . '</b></p>';
		}
		
		if (!isset($url)) {
			return '<p><b>' . elgg_echo('embedvideo:novideo') . '</b></p>';
		}

		$params = array('url' => $url, 'guid' => $guid, 'videowidth' => $videowidth);
		return elgg_trigger_plugin_hook('embed_extender', 'custom_embed', $params, $input);
	}


    //
    // provides custom pattern for yahoo video
    // called on hook 'embed_extender', 'custom_patterns'
    function embed_extender_yahoo_pattern($hook, $type, $returnvalue, $params){
      $returnvalue[] = '/(http:\/\/)?(video\.yahoo\.com\/\?vid=([0-9]*))/';
  
      return $returnvalue;
    }


    //
    // provides custom embed for yahoo video
    // called on hook 'embed_extender', 'custom_embed'
    function embed_extender_yahoo_embed($hook, $type, $returnvalue, $params){
      $url = $params['url'];
      $guid = $params['guid'];
      $videowidth = $params['videowidth'];
  
      // only return if the url matches what we're doing
      if (strpos($url, 'video.yahoo.com') != false) {
        if (!preg_match('/(http:\/\/)?(video\.yahoo\.com\/\?vid=([0-9]*))/', $url, $matches)) {
    		return $returnvalue;
	    }	
		
	    $videourl = $matches[3];
	    
	    if (!$videourl) {
	      //malformed url
	      return '<p><b>' . sprintf(elgg_echo('embedvideo:parseerror'), 'yahoo') . '</b></p>';
	    }
		
	    videoembed_calc_size($videowidth, $videoheight, 425/320, 24);

	    $embed_object = videoembed_add_css($guid, $videowidth, $videoheight);
	
	    $embed_object .= "<embed src=\"http://d.yimg.com/nl/cbe/paas/player.swf\" wmode=\"opaque\" flashvars=\"vid={$videourl}\" type=\"application/x-shockwave-flash\" width=\"$videowidth\" height=\"$videoheight\"></embed>";
	
    	// returns our object code
	    return $embed_object;
      }
    }