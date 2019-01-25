<?php

namespace Minds\Controllers\api\v2;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities\Factory as EntitiesFactory;
use Minds\Interfaces;

class feeds implements Interfaces\Api
{
    /**
     * Gets a list of suggested hashtags, including the ones the user has opted in
     * @param array $pages
     * @throws \Exception
     */
    public function get($pages)
    {
        Factory::isLoggedIn();

        $filter = $pages[0] ?? null;

        if (!$filter) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid filter'
            ]);
        }

        $algorithm = $pages[1] ?? null;

        if (!$algorithm) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid algorithm'
            ]);
        }

        $type = '';
        switch ($pages[2]) {
            case 'activities':
                $type = 'activity';
                break;
            case 'channels':
                $type = 'user';
                break;
            case 'images':
                $type = 'object:image';
                break;
            case 'videos':
                $type = 'object:video';
                break;
            case 'groups':
                $type = 'group';
                break;
            case 'blogs':
                $type = 'object:blog';
                break;
        }

        $period = $_GET['period'] ?? '12h';

        if ($algorithm === 'hot') {
            $period = '12h';
        } elseif ($algorithm === 'latest') {
            $period = null;
        }

        $offset = 0;

        if (isset($_GET['offset'])) {
            $offset = intval($_GET['offset']);
        }

        $limit = 12;

        if (isset($_GET['limit'])) {
            $limit = intval($_GET['limit']);
        }

        $hashtag = null;
        if (isset($_GET['hashtag'])) {
            $hashtag = $_GET['hashtag'];
        }

        $container_guid = $_GET['container_guid'] ?? null;

        if ($container_guid) {
            $container = EntitiesFactory::build($container_guid);

            if (!$container || !Core\Security\ACL::_()->read($container)) {
                return Factory::response([
                    'status' => 'error',
                    'message' => 'Forbidden'
                ]);
            }
        }

        /** @var Core\Feeds\Top\Manager $repo */
        $repo = Di::_()->get('Feeds\Top\Manager');

        $opts = [
            'cache_key' => Core\Session::getLoggedInUserGuid(),
            'container_guid' => $container_guid,
            'limit' => $limit,
            'offset' => $offset,
            'type' => $type,
            'algorithm' => $algorithm,
            'period' => $period,
        ];

        if ($hashtag) {
            $opts['hashtags'] = [$hashtag];
        } elseif (isset($_GET['hashtags']) && $_GET['hashtags'] && !$all) {
            $opts['hashtags'] = explode(',', $_GET['hashtags']);
        }

        $result = $repo->getList($opts);

        // Remove all unlisted content if it appears
        $result = array_values(array_filter($result, function ($entity) {
            return $entity->getAccessId() != 0;
        }));

        return Factory::response([
            'status' => 'success',
            'entities' => Factory::exportable($result),
            'load-next' => $limit + $offset,
        ]);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }
}
