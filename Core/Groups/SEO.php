<?php
/**
 * SEO manager operations for Groups
 */
namespace Minds\Core\Groups;

use Minds\Core\Di\Di;
use Minds\Core\SEO\Manager;
use Minds\Entities;

class SEO
{
    /**
     * Initialize SEO
     */
    public function setup()
    {
        Manager::add('/groups/profile', function ($slugs = []) {
            $guid = $slugs[0];
            $group = Entities\Factory::build($guid);

            if (!$group) {
                header("HTTP/1.0 404 Not Found");
                return [
                    'robots' => 'noindex'
                ];
            }

            if (!$group->getName()) {
                header("HTTP/1.0 404 Not Found");
                return [
                    'robots' => 'noindex'
                ];
            }

            return $meta = [
                'title' => $group->getName(),
                'description' => strip_tags($group->getBriefDescription()),
                'og:title' => $group->getName(),
                'og:description' => strip_tags($group->getBriefDescription()),
                'og:url' => Di::_()->get('Config')->site_url . 'groups/profile/' . $group->guid,
                'og:image' => Di::_()->get('Config')->cdn_url . 'fs/v1/banners/' . $group->guid,
                'og:image:width' => 2000,
                'og:image:height' => 1000
            ];
        });

        Manager::add('/groups/featured', function ($slugs = []) {
            return $meta = [
                'title' => 'Featured Groups',
                'description' => 'List of featured groups',
                'og:title' => 'Featured Groups',
                'og:description' => 'List of featured groups',
                'og:url' => Di::_()->get('Config')->site_url . 'groups/featured',
                'og:image' => Di::_()->get('Config')->site_url . 'assets/share/master.jpg',
                'og:image:width' => 1024,
                'og:image:height' => 681
            ];
        });
        Manager::add('/groups/top', function ($slugs = []) {
            return $meta = [
                'title' => 'Top Groups',
                'description' => 'List of top groups',
                'og:title' => 'Top Groups',
                'og:description' => 'List of top groups',
                'og:url' => Di::_()->get('Config')->site_url . 'groups/top',
                'og:image' => Di::_()->get('Config')->site_url . 'assets/share/master.jpg',
                'og:image:width' => 1024,
                'og:image:height' => 681
            ];
            Manager::add('/groups/my', function ($slugs = []) {
                return $meta = [
                    'title' => 'Your Groups',
                    'description' => 'List of your groups',
                    'og:title' => 'Your Groups',
                    'og:description' => 'List of your groups',
                    'og:url' => Di::_()->get('Config')->site_url . 'groups/my',
                    'og:image' => Di::_()->get('Config')->site_url . 'assets/share/master.jpg',
                    'og:image:width' => 1024,
                    'og:image:height' => 681
                ];
            });
        });
    }
}
