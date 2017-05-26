<?php
/**
 * SEO manager operations for Groups
 */
namespace Minds\Plugin\Groups\Core;

use Minds\Core\Di\Di;
use Minds\Core\SEO\Manager;
use Minds\Entities\Factory as EntitiesFactory;

class SEO
{
    /**
     * Initialize SEO
     */
    public static function setup()
    {
        Manager::add('/groups/profile', function ($slugs = []) {
            $guid = $slugs[0];
            $group = EntitiesFactory::build($guid);

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
    }
}
