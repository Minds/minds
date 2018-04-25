<?php

namespace Minds\Core\SEO\Sitemaps\Modules;

use Minds\Core\Di\Di;
use Minds\Core\Entities;
use Minds\Core\SEO\Sitemaps\SitemapModule;
use Minds\Core\Trending\Repository;

class SitemapTrending extends SitemapModule
{
    /** @var Repository */
    protected $trendingRepository;

    public function __construct()
    {
        $this->trendingRepository = Di::_()->get('Trending\Repository');
    }

    public function collect($pages, $segments)
    {
        if (isset($pages[0])) {
            $param = $pages[0];
        } else {
            $param = $segments[0];
        }
        switch ($param) {
            case 'channels':
                $key = 'channels';
                break;
            case 'images':
                $key = 'images';
                break;
            case 'videos':
                $key = 'videos';
                break;
            case 'blogs':
                $key = 'blogs';
                break;
            case 'groups':
                $key = 'groups';
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

            $this->addOrUpdateRoute($route, time());
        }
    }

    private function getEntities($key)
    {
        if (!isset($key)) {
            return [];
        }

        $result = $this->trendingRepository->getList(['type' => $key, 'limit' => 50]);
        if (!$result) {
            return [];
        }
        $guids = $result['guids'];
        return Entities::get(['guids' => $guids]);
    }
}
