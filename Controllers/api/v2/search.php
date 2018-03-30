<?php
/**
 * Minds Core Search API
 *
 * @version 2
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v2;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities;

class search implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        /** @var Core\Search\Search $search */
        $search = Di::_()->get('Search\Search');

        if (!isset($_GET['q']) || !$_GET['q']) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Query is required'
            ]);
        }

        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 12;
        $offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;

        $taxonomies = isset($_GET['taxonomies']) && $_GET['taxonomies'] ? $_GET['taxonomies'] : null;

        $container = null;
        if (isset($_GET['container']) && $_GET['container']) {
            $containerEntity = Entities\Factory::build($_GET['container']);

            if (Core\Security\ACL::_()->read($containerEntity)) {
                $container = $containerEntity->guid;
            }
        }

        $mature = isset($_GET['mature']) ? !!$_GET['mature'] : null;
        $paywall = isset($_GET['paywall']) ? !!$_GET['paywall'] : null;
        $license = isset($_GET['license']) ? $_GET['license'] : null;
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : 'latest';

        $options = [
            'text' => $_GET['q'],
            'taxonomies' => $taxonomies,
            'container' => $container,
            'mature' => $mature,
            'paywall' => $paywall,
            'license' => $license,
            'sort' => $sort,
        ];

        try {
            $guids = $search->query($options, $limit, $offset);
            $entities = [];
            $loadNext = '';

            if ($guids) {
                $entities = Di::_()->get('Entities')->get([ 'guids' => $guids ]);
            }

            if ($guids) {
                $loadNext = $limit + $offset; // zero-based
            }

            return Factory::response([
                'entities' => Factory::exportable($entities),
                'load-next' => $loadNext
            ]);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
