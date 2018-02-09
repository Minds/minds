<?php
/**
 * Sitemap
 */
namespace Minds\Controllers;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;

class sitemap extends core\page implements Interfaces\page
{
    /**
     * Get requests
     */
    public function get($pages)
    {
        header('Content-type: application/xml');
        echo <<< XML
  <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
      <url>
          <loc>https://www.minds.com/</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.minds.com/newsfeed</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.minds.com/minds</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.minds.com/plus</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.minds.com/discovery/trending/channels</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.minds.com/discovery/trending/channels</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.minds.com/blog/trending</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
      <url>
          <loc>https://www.minds.com/wire</loc>
          <lastmod>2018-02-09T02:32:45+00:00</lastmod>
      </url>
  </urlset>        

XML;
    }

    public function post($pages)
    {
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
