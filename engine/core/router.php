<?php
/**
 * The minds router. 
 */
namespace minds\core;

class router{
	
	// these are core pages, other pages are registered by plugins
	static $routes = array(
		"/action" => "minds\\pages\\actions",
		"/services" => "minds\\pages\\services",
		"/cache" => "minds\\pages\\cache",
		"/contact" => "minds\\pages\\contact",
		"/newsfeed" => "minds\\pages\\newsfeed\\newsfeed",
		"/subscriptions" => "minds\\pages\\subscriptions\\index",
		"/assets" => "minds\\pages\\assets",
		"/api" => "minds\\pages\\api\\api"
	);
	
	/**
	 * Route the pages
	 * (fallback to elgg page handler if we fail)
	 * 
	 */
	public function route($uri = null, $method = null){
	
		if ((!$uri) && (isset($_SERVER['REDIRECT_ORIG_URI'])))
		    $uri = strtok($_SERVER['REDIRECT_ORIG_URI'],'?');
		
		if(!$uri)
			$uri = strtok($_SERVER["REQUEST_URI"],'?');
			
		$this->detectContentType();
		
		$route = rtrim($uri, '/');
		$segments = explode('/', $route);
		$method = $method ? $method : strtolower($_SERVER['REQUEST_METHOD']);
        
        if($method == 'post' && !$_POST)
            $this->postDataFix();
		
		//@todo handler the homepage better
		if(count($segments) == 1 && $segments[0] == ""){
	    	//we load the homepage controller
			$handler = new \minds\pages\index();
			return $handler->$method(array());
	    }
	
		$loop = count($segments);
		while($loop >= 0){
			
			$offset = $loop -1;	
			if($loop < count($segments)){
				$slug_length = strlen($segments[$offset+1].'/');
				$route_length = strlen($route);
				$route = substr($route, 0, $route_length-$slug_length);
			}
			
			if(isset(self::$routes[$route])){
				$handler = new self::$routes[$route]();
				$pages = array_splice($segments, $loop) ?: array();
				return $handler->$method($pages);
			} 
			--$loop;
		}
		
		if($uri){
			$path = explode('/', substr($uri,1));
			
			$handler = array_shift($path);
			$page = implode('/',$path);
		} 

		return $this->legacyRoute($handler, $page);
	
	}
	
	/**
	 * Legacy fallback...
	 */
	public function legacyRoute($handler, $page){
	
		new page(false); //just to load init etc
	
		if (!\page_handler($handler, $page)) {
			//try a profile then
			if(!\page_handler('channel', "$handler/$page")){
				//forward('', '404');
				header("HTTP/1.0 404 Not Found");
				$buttons = \elgg_view('output/url', array('onclick'=>'window.history.back()', 'text'=>'Go back...', 'class'=>'elgg-button elgg-button-action'));
				$header = <<<HTML
<div class="elgg-head clearfix">
	<h2>404</h2>
	<h3>Ooooopppsss.... we couldn't find the page you where looking for! </h3>
	<div class="front-page-buttons">
		$buttons
	</div>
</div>
HTML;
				$body = \elgg_view_layout( "one_column", array(
							'content' => null, 
							'header'=>$header
						));
				echo \elgg_view_page('404', $body);
			}
		}
	}
	
	/**
	 * Detect the content type and apply the viewtype
	 */
	public function detectContentType(){
		if(isset($_SERVER["CONTENT_TYPE"]) && $_SERVER["CONTENT_TYPE"] == 'application/json'){
			\elgg_set_viewtype('json');
            if(strtolower($_SERVER['REQUEST_METHOD']) == 'post'){
                   $this->postDataFix();
            }
		}
	}
    
    /**
     * PHP sucks when it comes to json post data... this is a hack
     * 
     */
    public function postDataFix(){
        $postdata = file_get_contents("php://input");   
        $request = json_decode($postdata, true);
        foreach($request as $k => $v){
            $_POST[$k] = $v;
            $_REQUEST[$k] = $v;
        }
    }
	
	/**
	 * Register routes...
	 * 
	 * @param array $routes - an array of routes to handlers
	 * @return array - the array of all your routes
	 */
	static public function registerRoutes($routes = array()){
		return self::$routes = array_merge(self::$routes, $routes);
	}
}
