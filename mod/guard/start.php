<?php
/**
 * Minds guard plugin. Prevents spam and detects bots. 
 */
namespace minds\plugin\guard;

use minds\core;

\elgg_register_event_handler('init', 'system', function(){
	new start();
});



class start extends \ElggPlugin{
	
	public function __construct(){
		parent::__construct('guard');	

		$this->init();
	}
	
	public function init(){
		\elgg_register_event_handler('create', 'object', array($this, 'createHook'));
		\elgg_register_event_handler('update', 'object', array($this, 'createHook'));
	}
	
	protected function prohbitedDomains(){
		return array( 
						//shorts
						't.co', 'goo.gl', 'ow.ly', 'bitly.com', 'bit.ly',
						//full
						'movieblog.tumblr.com', 'moviehdstream.wordpress.com', 'moviehq.tumblr.com', 'moviehq.webs.com',
						'moviehq.wordpress.com', 'movieo.wordpress.com', 'movieonline.tumblr.com', 'movieonline.webs.com',
						'movieonline.wordpress.com', 'movieonlinehd.tumblr.com', 'movieonlinehd.webs.com', 'movieonlinehd.wordpress.com',
						'movies.tumblr.com', 'moviesf.tumblr.com', 'moviesgodetia.com', 'movieslinks4u', 'moviesmount.com',
						'moviesmonster.biz', 'moviesondesktop', 'moviesonlinefree.biz', 'moviestream.wordpress.com'
					);
	}
	
	protected function strposa($haystack, $needles, $offset = 0){
		if(!is_array($needles)) 
			$needles = array($needles);
	    foreach($needles as $query) {
	        if(strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
	    }
	    return false;
	}
	
	public function createHook($hook, $type, $params, $return){
		$object = $params;
		if($this->strposa($object->description, $this->prohbitedDomains())){
			\register_error('Sorry, your post contains a reference to a domain name linked to spam. Please remove it and try again');
			forward(REFERRER);
			return false;
		}
	}
}