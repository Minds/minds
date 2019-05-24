<?php

namespace Minds\Core;

use Minds\Core\I18n\I18n;
use Minds\Helpers;
use Minds\Core\Di\Di;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Minds Core Router.
 */
class Router
{
    // these are core pages, other pages are registered by plugins
    public static $routes = array(
      '/archive/thumbnail' => 'Minds\\Controllers\\fs\\v1\\thumbnail',
      '/api/v1/archive/thumbnails' => 'Minds\\Controllers\\api\\v1\\media\\thumbnails',

      '/oauth2/token' => 'Minds\\Controllers\\oauth2\\token',
      '/oauth2/implicit' => 'Minds\\Controllers\\oauth2\\implicit',
      '/icon' => 'Minds\\Controllers\\icon',
      '//icon' => 'Minds\\Controllers\\icon',
      '/api' => 'Minds\\Controllers\\api\\api',
      '/fs' => 'Minds\\Controllers\\fs\\fs',
      '/thumbProxy' => 'Minds\\Controllers\\thumbProxy',
      '/wall' => 'Minds\\Controllers\\Legacy\\wall',
      '/not-supported' => "Minds\Controllers\\notSupported",
        //  "/app" => "minds\\pages\\app",
      '/emails/unsubscribe' => 'Minds\\Controllers\\emails\\unsubscribe',
      '/sitemap' => 'Minds\\Controllers\\sitemap',
      '/apple-app-site-association' => '\\Minds\\Controllers\\deeplinks',
      '/sitemaps' => '\\Minds\\Controllers\\sitemaps',
      '/checkout' => '\\Minds\\Controllers\\checkout',
    );

    /**
     * Route the pages
     * (fallback to elgg page handler if we fail).
     *
     * @param string $uri
     * @param string $method
     *
     * @return null|mixed
     */
    public function route($uri = null, $method = null)
    {
        if ((!$uri) && (isset($_SERVER['REDIRECT_ORIG_URI']))) {
            $uri = strtok($_SERVER['REDIRECT_ORIG_URI'], '?');
        }

        if (!$uri) {
            $uri = strtok($_SERVER['REQUEST_URI'], '?');
        }

        $this->detectContentType();

        header('X-Frame-Options: DENY');

        $route = rtrim($uri, '/');
        $segments = explode('/', $route);
        $method = $method ? $method : strtolower($_SERVER['REQUEST_METHOD']);

        if ($method == 'post') {
            $this->postDataFix();
        }

        $request = ServerRequestFactory::fromGlobals();
        $response = new JsonResponse([]);

        if ($request->getMethod() === 'OPTIONS') {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Mx-ReqToken,X-Requested-With,X-No-Cache');

            return null;
        }

        // Sessions
        // TODO: Support middleware
        $session = Di::_()->get('Sessions\Manager');
        $session->withRouterRequest($request);

        // OAuth Middleware
        // TODO: allow interface to bypass
        // TODO: Support middleware
        if (!Session::isLoggedIn()) { // Middleware will resolve this
            Session::withRouterRequest($request, $response);
        }

        // XSRF Cookie - may be able to remove now with OAuth flow
        Security\XSRF::setCookie();

        new SEO\Defaults(Di::_()->get('Config'));

        if (Session::isLoggedin()) {
            Helpers\Analytics::increment('active');
        }

        if (isset($_GET['__e_ct_guid']) && is_numeric($_GET['__e_ct_guid'])) {
            Helpers\Analytics::increment('active', $_GET['__e_ct_guid']);
            Helpers\Campaigns\EmailRewards::reward($_GET['campaign'], $_GET['__e_ct_guid']);
        }

        Di::_()->get('Email\RouterHooks')
            ->withRouterRequest($request);

        if (isset($_GET['referrer'])) {
            Helpers\Campaigns\Referrals::register($_GET['referrer']);
        }
        $loop = count($segments);
        while ($loop >= 0) {
            $offset = $loop - 1;
            if ($loop < count($segments)) {
                $slug_length = strlen($segments[$offset + 1].'/');
                $route_length = strlen($route);
                $route = substr($route, 0, $route_length - $slug_length);
            }

            if (isset(self::$routes[$route])) {
                $handler = new self::$routes[$route]();
                $pages = array_splice($segments, $loop) ?: array();
                if (method_exists($handler, $method)) {
                    // Set the request
                    if (method_exists($handler, 'setRequest')) {
                        $handler->setRequest($request);
                    }
                    // Set the response
                    if (method_exists($handler, 'setResponse')) {
                        $handler->setResponse($response);
                    }

                    return $handler->$method($pages);
                } else {
                    exit;
                }
            }
            --$loop;
        }

        if (!$this->legacyRoute($uri)) {
            (new I18n())->serveIndex();
        }

        return null;
    }

    /**
     * Legacy Router fallback.
     *
     * @param string $uri
     *
     * @return bool
     */
    public function legacyRoute($uri)
    {
        $path = explode('/', substr($uri, 1));
        $handler = array_shift($path);
        $page = implode('/', $path);

        new page(false); //just to load init etc

        return false;
    }

    /**
     * Detects request content type and apply the corresponding polyfills.
     */
    public function detectContentType()
    {
        if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'application/json') {
            //\elgg_set_viewtype('json');
            if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
                $this->postDataFix();
            }
        }
    }

    /**
     * Populates $_POST and $_REQUEST with request's JSON payload.
     */
    public function postDataFix()
    {
        $postdata = file_get_contents('php://input');
        $request = json_decode($postdata, true);
        if ($request) {
            foreach ($request as $k => $v) {
                $_POST[$k] = $v;
                $_REQUEST[$k] = $v;
            }
        }
    }

    /**
     * Register routes.
     *
     * @param array $routes - an array of routes to handlers
     *
     * @return array - the array of all your routes
     */
    public static function registerRoutes($routes = array())
    {
        return self::$routes = array_merge(self::$routes, $routes);
    }
}
