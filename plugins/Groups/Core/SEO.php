<?php
/**
 * SEO manager operations for Groups
 */
namespace Minds\Plugin\Groups\Core;

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

            if (!$group->getName()) {
                return [];
            }

            return $meta = [
                'title' => $group->getName(),
                'description' => $group->getBriefDescription()
            ];
        });
    }
}
