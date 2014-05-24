<?php
/**
 * Minds Wordpress plugin
 * 
 * Current limitations. With singlesignon, its currently only possible to have ONE connected wordpress site. 
 */
namespace minds\plugin\minds_wordpress;

use minds\core;

class minds_wordpress extends \ElggPlugin{
	
	public function __construct(){
		parent::__construct('minds_wordpress');
		$this->init();
	}
	
	/**
	 * Initilialise the plugin
	 */
	public function init(){
		$routes = core\router::registerRoutes($this->registerRoutes());
		
		//sync comment with wordpress
		\elgg_register_event_handler('comment:create', 'comment', array($this, 'commentHook'));
		\elgg_register_event_handler('loggedin', 'user', array($this, 'loggedinHook'));
		\elgg_register_event_handler('loggedout', 'user', array($this, 'loggedoutHook'));
		
		\elgg_extend_view('login/extend', 'minds_wordpress/login');
		
		if(\get_input('wp_auth') && \elgg_is_logged_in()){
			$this->auth();
		}
	}
	
	/**
	 * Handler the pages
	 * 
	 * @param array $pages - the page slugs
	 * @return bool
	 */
	public function registerRoutes(){
		$path = "minds\\plugin\\minds_wordpress";
		return array(
			'/wp' => "$path\\pages\\wp"
		);
	}
	
	/**
	 * Return a list of connected wordpress sites (set in plugin settings)
	 */
	public function getWordpressUrl(){
		return $this->getSetting('url');
	}
	
	/**
	 * Blind oauth2 proccess
	 */
	public function auth(){
		if($this->getSetting('client_id') && $this->getWordpressUrl()){
		//forward the code to the wp site.
		$forward = elgg_get_site_url() . "action/oauth2/authorize?client_id=".$this->getSetting('client_id')."&response_type=code&redirect_uri=".urlencode($this->getWordpressUrl().'?minds_auth=true&forward='.get_input('forward', true));
		\forward(\elgg_add_action_tokens_to_url($forward));
		} else {
			return false;
		}
	}
	
	/**
	 * Login event hook
	 */
	public function loggedinHook($event, $object_type, $user){
		if(\get_input('wp_auth')){
			$this->auth();
		}
		
		return true;
	}
	
	/**
	 * Loggedout hook
	 */
	public function loggedoutHook($event, $object_type, $user){
		$forward = $this->getWordpressUrl() . '?minds_deAuth=true&forward='.\get_input('forward', 'true');
		\forward($forward);
		return true;
	}
	
	/**
	 * Comments event listener
	 */
	public function commentHook($event, $object_type, $data) {
        
        global $CONFIG;
        
        // Ping our comment at our linked server permalink, the server then uses the API to verify the comment before posting
        if (($data['_source']['pid']) && ($post = \get_entity($data['_source']['pid'], 'object'))) {
                    
            // Ok we've got a post, does it have a remote permalink?
            if ($permalink = $post->ex_permalink) {
                
                // Save the target as a lookup, we'll verify this at the other end (this makes things simpler, since wordpress doesn't have a current_page equiv)
                $data['target'] = $permalink;
                
                // Now, let's save some author information
                $user = \elgg_get_logged_in_user_entity();
                $data['author'] = array(
                    'name' => $user->name,
                    'email' => $user->email,
                    'minds_author_icon' => $user->getIcon('small'),
                    'profile' => $user->getUrl()
                );
                
                // Ping data to the minds wordpress plugin
                $query = http_build_query($data);

                if (strpos($permalink, '?') === false) // Tell the wordpress blog that we're pinging with a comment action
                    $permalink .= '?minds-connect=comment-on';
                else
                    $permalink .= '&minds-connect=comment-on';
                
                
                
                $ch = curl_init();
                
                curl_setopt($ch,CURLOPT_URL, $permalink);
                curl_setopt($ch,CURLOPT_POST, 1);
                curl_setopt($ch,CURLOPT_POSTFIELDS, $query);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_USERAGENT, "Minds Site at " . \elgg_get_site_url());
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

                //execute post
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $result = curl_exec($ch);
                if ($CONFIG->debug)
                    error_log("Result from pinging $permalink: $http_status");
                
                curl_close($ch);
            }
            
        }
        
    }
}
	
