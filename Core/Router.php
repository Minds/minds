<?php
/**
 * The minds router.
 */
namespace Minds\Core;

use Minds\Helpers;

class Router{

	// these are core pages, other pages are registered by plugins
	static $routes = array(
		"/icon" => "Minds\\Controllers\\icon",
		"/api" => "Minds\\Controllers\\api\\api",
		"/fs" => "Minds\\Controllers\\fs\\fs",
		'/thumbProxy' => "Minds\\Controllers\\thumbProxy",
    //  "/app" => "minds\\pages\\app",
    "/emails/unsubscribe" => "Minds\\Controllers\\emails\\unsubscribe"
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

		if(Session::isLoggedin())
			Helpers\Analytics::increment("active");

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
        if(method_exists($handler, $method))
            return $handler->$method($pages);
        else
            exit;
			}
			--$loop;
		}

		if(!$this->legacyRoute($uri))
			include(dirname(dirname(dirname((__FILE__)))) . '/front/public/index.php');

	}

	/**
	 * Legacy fallback...
	 */
	public function legacyRoute($uri){

		$path = explode('/', substr($uri,1));
		$handler = array_shift($path);
		$page = implode('/',$path);

		new page(false); //just to load init etc

		if (!\page_handler($handler, $page)) {
			return false;
		}

		return true;

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
        if($request){
		foreach($request as $k => $v){
		    $_POST[$k] = $v;
		    $_REQUEST[$k] = $v;
		}
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
