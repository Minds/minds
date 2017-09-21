<?php
/**
 * Minds Boost Api endpoint
 *
 * @version 1
 * @author Mark Harding
 *
 */

namespace Minds\Controllers\api\v1\boost;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Entities;
use Minds\Helpers\Counters;
use Minds\Interfaces;

class fetch implements Interfaces\Api, Interfaces\ApiIgnorePam
{

    /**
     * Return a list of boosts that a user needs to review
     * @param array $pages
     */
    public function get($pages)
    {
        $response = [];
        $user = Core\Session::getLoggedinUser();

        if ($user->disabled_boost && $user->plus) {
            return Factory::response([]);
        }

        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 2;
        $rating = isset($_GET['rating']) ? (int) $_GET['rating'] : 1;
        $platform = isset($_GET['platform']) ? $_GET['platform'] : 'other';
        $quality = 0;

        // options specific to newly created users (<=1 hour) and iOS users
        if (time() - $user->getTimeCreated() <= 3600) {
            $rating = 1; // they can only see safe content
            $quality = 75;
        }

        if ($platform === 'ios') {
            $rating = 1; // they can only see safe content
            $quality = 90;
        }

        switch ($pages[0]) {
            case 'content':
                $boosts = Core\Boost\Factory::build($pages[0])
                    ->getBoosts(
                        $limit,
                        true,
                        $rating,
                        $quality,
                        [ 'priority' => true ]
                    );
                foreach ($boosts as $entity) {
                    $response['boosts'][] = $entity->export();
                    Counters::increment($entity->guid, "impression");
                    Counters::increment($entity->owner_guid, "impression");
                }
                break;
            case 'newsfeed':
                $boosts = Core\Boost\Factory::build($pages[0])->getBoosts(
                    $limit,
                    false,
                    $rating,
                    $quality,
                    [ 'priority' => true ]
                );
                foreach ($boosts as $guid => $entity) {
                    $response['boosts'][] = array_merge($entity->export(), ['boosted' => true, 'boosted_guid' => (string)$guid]);
                }
                if (!$boosts) {
                    $cacher = Core\Data\cache\factory::build('apcu');
                    $offset = $cacher->get(Core\Session::getLoggedinUser()->guid . ":newsfeed-fallover-boost-offset") ?: "";
                    $guids = Core\Data\indexes::fetch('object:image:featured', ['offset' => $offset, 'limit' => 12]);
                    if (!$guids) {
                        break;
                    }
                    $entities = Core\Entities::get(['guids' => $guids]);
                    usort($entities, function ($a, $b) {
                        if ((int)$a->featured_id == (int)$b->featured_id) {
                            return 0;
                        }
                        return ((int)$a->featured_id < (int)$b->featured_id) ? 1 : -1;
                    });
                    foreach ($entities as $entity) {
                        $boost = new Entities\Activity();
                        $boost->guid = $entity->guid;
                        $boost->owner_guid = $entity->owner_guid;
                        $boost->{'thumbs:up:user_guids'} = $entity->{'thumbs:up:user_guids'};
                        $boost->{'thumbs:down:user_guids'} = $entity->{'thumbs:down:user_guids'};
                        $boost->setTitle($entity->title);
                        $boost->setFromEntity($entity);
                        switch ($entity->subtype) {
                            case "blog":
                                $boost->setBlurb(strip_tags($entity->description))
                                    ->setURL($entity->getURL())
                                    ->setThumbnail($entity->getIconUrl());
                                break;
                            case "image":
                                $boost->setCustom('batch', [[
                                    'src' => elgg_get_site_url() . 'fs/v1/thumbnail/' . $entity->guid,
                                    'href' => $entity->getUrl(),
                                    'mature' => $entity instanceof \Minds\Interfaces\Flaggable ? $entity->getFlag('mature') : false
                                ]]);
                                break;
                        }
                        $boost->boosted = true;
                        $response['boosts'][] = $boost->export();
                    }
                    if (count($response['boosts']) < 5) {
                        $cacher->set(Core\Session::getLoggedinUser()->guid . ":newsfeed-fallover-boost-offset", "");
                    } else {
                        $cacher->set(Core\Session::getLoggedinUser()->guid . ":newsfeed-fallover-boost-offset", end($entities)->guid);
                    }
                }
        }

        return Factory::response($response);
    }

    /**
     */
    public function post($pages)
    {
    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {
        $boost = Core\Boost\Factory::build($pages[0])->getBoostEntity($pages[1]);
        if (!$boost) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Boost not found'
            ]);
        }
        Counters::increment($boost->getEntity()->guid, "impression");
        Counters::increment($boost->getEntity()->owner_guid, "impression");
        Counters::increment((string)$boost->getId(), "boost_impressions", 1);
        Counters::increment(0, "boost_impressions", 1);

        return Factory::response([]);
    }

    /**
     */
    public function delete($pages)
    {
    }
}
