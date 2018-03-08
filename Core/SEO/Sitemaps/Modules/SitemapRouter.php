<?php

namespace Minds\Core\SEO\Sitemaps\Modules;

use Minds\Core\SEO\Manager;
use Minds\Core\SEO\Sitemaps\SitemapModule;

class SitemapRouter extends SitemapModule
{
    public function collect($pages, $segments)
    {
        $staticRoutes = array_keys(Manager::$routes);
        foreach ($staticRoutes as $route) {
            if ($route === '/') {
                continue;
            }
            $route = substr($route, 1); //remove the first '/'
            $this->addOrUpdateRoute($route, time());
        }

        //another sitemaps related routes:
        $routes = [
            'sitemaps/discovery/trending/images',
            'sitemaps/discovery/trending/videos',
            'sitemaps/blogs/trending',
            'sitemaps/groups/trending',

            'sitemaps/discovery/featured/images',
            'sitemaps/discovery/featured/videos',
            'sitemaps/blogs/featured',
            'sitemaps/groups/featured',
        ];
        foreach ($routes as $route) {
            $this->addOrUpdateRoute($route);
        }
    }
}