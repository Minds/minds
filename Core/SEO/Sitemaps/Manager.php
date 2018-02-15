<?php

namespace Minds\Core\SEO\Sitemaps;

use Minds\Core\Config;
use Minds\Core\Di\Di;

class Manager
{
    /** @var Config */
    protected $config;

    protected $routes = [];

    public function __construct($dynamicMaps = null)
    {
        $this->config = Di::_()->get('Config');
    }

    public function addModules($routes) {
        $this->routes = array_merge($this->routes, $routes);
    }

    public function getSitemap($uri)
    {
        $routes = $this->route($uri);
        $sitemap = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

        foreach ($routes as $route => $ts) {
            $date = date(DATE_ATOM, $ts);
            $fullRoute = $this->config->site_url . $route;
            $sitemap .= "<url>
              <loc>{$fullRoute}</loc>
              <lastmod>{$date}</lastmod>
            </url>";
        }
        $sitemap .= '</urlset>';
        return $sitemap;
    }

    protected function route($uri)
    {
        $route = rtrim($uri, '/');
        $segments = explode('/', $route);
        $loop = count($segments);
        while ($loop >= 0) {
            $offset = $loop -1;
            if ($loop < count($segments)) {
                $slug_length = strlen($segments[$offset+1].'/');
                $route_length = strlen($route);
                $route = substr($route, 0, $route_length-$slug_length);
            }

            if (isset($this->routes[$route])) {
                /** @var SitemapModule $module */
                $module = new $this->routes[$route]();

                $pages = array_splice($segments, $loop) ?: [];

                $module->collect($pages, $segments);

                return $module->getRoutes();
            }
            --$loop;
        }
        return [];
    }
}