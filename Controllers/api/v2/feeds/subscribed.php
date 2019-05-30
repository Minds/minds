<?php

namespace Minds\Controllers\api\v2\feeds;

use Minds\Api\Exportable;
use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Entities\User;
use Minds\Interfaces;

class subscribed implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param array $pages
     * @return mixed|null
     * @throws \Exception
     */
    public function get($pages)
    {
        Factory::isLoggedIn();

        /** @var User $currentUser */
        $currentUser = Core\Session::getLoggedinUser();

        $type = '';
        switch ($pages[0]) {
            case 'activities':
                $type = 'activity';
                break;
            case 'images':
                $type = 'object:image';
                break;
            case 'videos':
                $type = 'object:video';
                break;
            case 'blogs':
                $type = 'object:blog';
                break;
        }

        //

        $hardLimit = 5000;
        $offset = 0;

        if (isset($_GET['offset'])) {
            $offset = intval($_GET['offset']);
        }

        $limit = 12;

        if (isset($_GET['limit'])) {
            $limit = abs(intval($_GET['limit']));
        }

        if (($offset + $limit) > $hardLimit) {
            $limit = $hardLimit - $offset;
        }

        if ($limit <= 0) {
            return Factory::response([
                'status' => 'success',
                'entities' => [],
                'load-next' => $hardLimit,
                'overflow' => true,
            ]);
        }

        //

        $sync = (bool) ($_GET['sync'] ?? false);

        $fromTimestamp = $_GET['from_timestamp'] ?? 0;

        $asActivities = (bool) ($_GET['as_activities'] ?? true);

        $query = null;

        if (isset($_GET['query'])) {
            $query = $_GET['query'];
        }

        $custom_type = isset($_GET['custom_type']) && $_GET['custom_type'] ? [$_GET['custom_type']] : null;

        /** @var Core\Feeds\Top\Manager $manager */
        $manager = Di::_()->get('Feeds\Top\Manager');

        /** @var Core\Feeds\Top\Entities $entities */
        $entities = new Core\Feeds\Top\Entities();
        $entities->setActor($currentUser);

        $opts = [
            'cache_key' => $currentUser->guid,
            'subscriptions' => $currentUser->guid,
            'access_id' => 2,
            'custom_type' => $custom_type,
            'limit' => $limit,
            'type' => $type,
            'algorithm' => 'latest',
            'period' => '1y',
            'sync' => $sync,
            'from_timestamp' => $fromTimestamp,
            'query' => $query ?? null,
            'nsfw' => null,
            'single_owner_threshold' => 0,
        ];

        try {
            $result = $manager->getList($opts);

            if (!$sync) {
                // Remove all unlisted content, if ES document is not in sync, it'll
                // also remove pending activities
                $result = $result->filter([$entities, 'filter']);

                if ($asActivities) {
                    // Cast to ephemeral Activity entities, if another type
                    $result = $result->map([$entities, 'cast']);
                }
            }

            return Factory::response([
                'status' => 'success',
                'entities' => Exportable::_($result),
                'load-next' => $result->getPagingToken(),
            ]);
        } catch (\Exception $e) {
            error_log($e);
            return Factory::response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Equivalent to HTTP POST method
     * @param array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
