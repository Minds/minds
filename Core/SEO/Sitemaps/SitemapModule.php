<?php


namespace Minds\Core\SEO\Sitemaps;


class SitemapModule
{
    protected $map;


    public function getRoutes()
    {
        return $this->map;
    }

    public function addOrUpdateRoute($route, $time_updated = null)
    {
        if (!isset($time_updated)) {
            $time_updated = time();
        }
        if ((isset($this->map[$route]) && $time_updated > $this->map[$route]) || !isset($this->map[$route])) {
            $this->map[$route] = $time_updated;
        }
    }

    public function collect($pages, $segments)
    {

    }
}