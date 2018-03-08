<?php


namespace Minds\Controllers;

use Minds;
use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\SEO\Sitemaps\Modules\SitemapFeatured;
use Minds\Core\SEO\Sitemaps\Modules\SitemapRouter;
use Minds\Core\SEO\Sitemaps\Modules\SitemapTrending;
use Minds\Interfaces;

class sitemaps implements Interfaces\Api
{
    public function get($pages)
    {
        $sitemap = new Core\SEO\Sitemaps\Manager();
        $sitemap->addModules([
            'master' => SitemapRouter::class,
            'discovery/trending' => SitemapTrending::class,
            'blogs/trending' => SitemapTrending::class,
            'groups/trending' => SitemapTrending::class,

            'discovery/featured' => SitemapFeatured::class,
            'blogs/featured' => SitemapFeatured::class,
            'groups/featured' => SitemapFeatured::class,
        ]);

        header('Content-type: application/xml');
        echo $sitemap->getSitemap(implode('/',$pages));
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }

}