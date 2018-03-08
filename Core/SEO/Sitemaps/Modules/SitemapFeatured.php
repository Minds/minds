<?php


namespace Minds\Core\SEO\Sitemaps\Modules;


use Minds\Core\Data\indexes;
use Minds\Core\Entities;
use Minds\Core\SEO\Sitemaps\SitemapModule;

class SitemapFeatured extends SitemapModule
{

    public function collect($pages, $segments)
    {
        if (isset($pages[0])) {
            $param = $pages[0];
        } else {
            $param = $segments[0];
        }

        switch ($param) {
            case 'channels':
                $key = 'user:featured';
                break;
            case 'images':
                $key = 'object:image:featured';
                break;
            case 'videos':
                $key = 'object:video:featured';
                break;
            case 'blogs':
                $key = 'object:blog:featured';
                break;
            case 'groups':
                $key = 'group:featured';
                break;
        }

        $entities = $this->getEntities($key);
        foreach ($entities as $entity) {
            $route = '';
            switch ($param) {
                case 'images':
                case 'videos':
                    $route = 'media/' . $entity->guid;
                    break;
                case 'channels':
                    $route = $entity->username;
                    break;
                case 'blogs':
                    $route = 'blog/view/' . $entity->guid;
                    break;
                case 'groups':
                    $route = 'groups/profile' . $entity->guid;
                    break;
            }

            $this->addOrUpdateRoute($route, $entity->getTimeCreated());
        }
    }

    private function getEntities($key)
    {
        if (!isset($key)) {
            return [];
        }
        $guids = indexes::fetch($key, ['limit' => 50]);

        if (!$guids || count($guids) === 0) {
            return [];
        }
        return Entities::get(['guids' => $guids]);
    }
}