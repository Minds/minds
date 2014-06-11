<?php
/**
 * A minds security plugin
 * 
 * - Prevents spam
 * - Enabled twofactor authentication
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
		
		
		/*$twofactor = new lib\twofactor();
		
		$user = new\ElggUser('mark');
		$secret = $user->twofactor_secret ?: $twofactor->createSecret();
		
		$user->twofactor_secret = $secret;
		$user->save();
		
		echo elgg_view('output/img', array('src'=>$twofactor->getQRCodeGoogleUrl('mark@minds.com', $secret)));
		
		//$oneCode = $twofactor->getCode($secret);
		//echo "Checking Code '$oneCode' and Secret '$secret':\n";
		$oneCode = $_GET['code'];
		echo $oneCode;
		
		$checkResult = $twofactor->verifyCode($secret, $oneCode, 2);    // 2 = 2*30sec clock tolerance
		if ($checkResult) {
		    echo 'OK';
		} else {
		    echo 'FAILED';
		}
		//echo $twofactor->getCode($secret);
		
		exit;*/
		\elgg_register_event_handler('create', 'object', array($this, 'createHook'));
		\elgg_register_event_handler('update', 'object', array($this, 'createHook'));
		
		
		\elgg_register_event_handler('pagesetup', 'system', array($this,'twofactorPagesetup'));
		
		$routes = core\router::registerRoutes($this->registerRoutes());
	}
	
	/**
	 * Handler the pages
	 * 
	 * @param array $pages - the page slugs
	 * @return bool
	 */
	public function registerRoutes(){
		$path = "minds\\plugin\\guard";
		return array(
			'/settings/twofactor' => "$path\\pages\\twofactor"
		);
	}
	
	protected function prohbitedDomains(){
		return array( 
						//shorts
					//	't.co', 'goo.gl', 'ow.ly', 'bitly.com', 'bit.ly','tinyurl.com','bit.do','go2.do',
					//	'adf.ly', 'adcrun.ch', 'zpag.es','ity.im', 'q.gs', 'lnk.co', 'is.gd',  
						//full
						'movieblog.tumblr.com', 'moviehdstream.wordpress.com', 'moviehq.tumblr.com', 'moviehq.webs.com',
						'moviehq.wordpress.com', 'movieo.wordpress.com', 'movieonline.tumblr.com', 'movieonline.webs.com',
						'movieonline.wordpress.com', 'movieonlinehd.tumblr.com', 'movieonlinehd.webs.com', 'movieonlinehd.wordpress.com',
						'movies.tumblr.com', 'moviesf.tumblr.com', 'moviesgodetia.com', 'movieslinks4u', 'moviesmount.com',
						'moviesmonster.biz', 'moviesondesktop', 'moviesonlinefree.biz', 'moviestream.wordpress.com',
						'movieontop.com', 'afllivestreaming.com.au', 'londonolympiccorner', 'nrllivestreaming.com.au',
						'24x7livestreamtvchannels.com', 'www.edogo.us', 'all4health.in', 'watches4a.co.uk', 'es.jennyjoseph.com',
						'allsportslive24x7.blogspot.com', 'boxing-tv-2014-live-stream.blogspot.com', 'amarblogdalima.blogspot.com'
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
			\register_error('Sorry, your post contains a reference to a domain name linked to spam. You can not use short urls (eg. bit.ly). Please remove it and try again');
			if(PHP_SAPI != 'cli')
				forward(REFERRER);
			return false;
		}

			return true;
	}

	/**
	 * Twofactor authentication login hook
	 */
	public function twofactorLoginHook(){
		
	}
	
	/**
	 * twofactor pagesetup (adds the menus etc)
	 */
	public function twofactorPagesetup(){
		if (elgg_get_context() == "settings" && elgg_get_logged_in_user_guid()) {
			$params = array(
				'name' => 'twofactor',
				'text' => elgg_echo('guard:twofactor'),
				'href' => "settings/twofactor",
			);
			elgg_register_menu_item('page', $params);
		}
	}
}
