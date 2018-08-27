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
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Helpers\Counters;
use Minds\Interfaces;

class fetch implements Interfaces\Api
{

    /**
     * Return a list of boosts that a user needs to review
     * @param array $pages
     */
    public function get($pages)
    {
        $response = [];
        $user = Core\Session::getLoggedinUser();

        if (!$user) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must be loggedin to view boosts',
            ]);
        }

        if ($user->disabled_boost && $user->isPlus()) {
            return Factory::response([]);
        }

        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 2;
        $rating = isset($_GET['rating']) ? (int) $_GET['rating'] : $user->getBoostRating();
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

        /** @var Core\Boost\Network\Iterator $iterator */
        $iterator = Core\Di\Di::_()->get('Boost\Network\Iterator');
        $iterator->setLimit($limit)
            ->setRating($rating)
            ->setQuality($quality)
            ->setOffset($_GET['offset'])
            ->setType($pages[0])
            ->setPriority(true);

        if (isset($_GET['rating']) && $pages[0] == 'newsfeed') {
            $cacher = Core\Data\cache\factory::build('apcu');
            $offset =  $cacher->get(Core\Session::getLoggedinUser()->guid . ':boost-offset:newsfeed');
            $iterator->setOffset($offset);
        }

        switch ($pages[0]) {
            case 'content':
                $iterator->setIncrement(true);

                foreach ($iterator as $entity) {
                    $response['boosts'][] = $entity->export();
                    Counters::increment($entity->guid, "impression");
                    Counters::increment($entity->owner_guid, "impression");
                }
                $response['load-next'] = $iterator->getOffset();
                
                if (!$response['boosts']) {
                    $result = Di::_()->get('Trending\Repository')->getList([
                        'type' => 'images',
                        'rating' => isset($rating) ? (int) $rating : 1,
                        'limit' => $limit,
                    ]);

                    if ($result && isset($result['guids'])) {
                        $entities = Core\Entities::get([ 'guids' => $result['guids'] ]);
                        $response['boosts'] = Factory::exportable($entities);
                    }
                }
                break;
            case 'newsfeed':
                foreach ($iterator as $guid => $entity) {
                    $response['boosts'][] = array_merge($entity->export(), ['boosted' => true, 'boosted_guid' => (string)$guid]);
                }
                $response['load-next'] = $iterator->getOffset();
                if (isset($_GET['rating']) && $pages[0] == 'newsfeed') {
                    $cacher->set(Core\Session::getLoggedinUser()->guid . ':boost-offset:newsfeed', $iterator->getOffset(), (3600 / 2));
                }
                if (!$iterator->list) {
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
                        $boost->time_created = $entity->time_created;
                        $boost->time_updated = $entity->time_updated;
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
                    if (!$response['boosts'] || count($response['boosts']) < 5) {
                        $cacher->destory(Core\Session::getLoggedinUser()->guid . ":newsfeed-fallover-boost-offset");
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
        $expire = Core\Di\Di::_()->get('Boost\Network\Expire');
        $metrics = Core\Di\Di::_()->get('Boost\Network\Metrics');

        $boost = Core\Boost\Factory::build($pages[0])->getBoostEntity($pages[1]);
        if (!$boost) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Boost not found'
            ]);
        }

        $count = $metrics->incrementViews($boost);

        if ($count > $boost->getImpressions()) {
            $expire->setBoost($boost);
            $expire->expire();
        }

        Counters::increment($boost->getEntity()->guid, "impression");
        Counters::increment($boost->getEntity()->owner_guid, "impression");

        return Factory::response([]);
    }

    /**
     */
    public function delete($pages)
    {
    }
}
