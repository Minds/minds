<?php
/**
 * Minds Core Search API
 *
 * @version 2
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v2\search;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities;

class top implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $topTaxonomies = [ 'user', 'group' ];

        // --

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

        $mature = isset($_GET['mature']) ? !!$_GET['mature'] : null;
        $paywall = isset($_GET['paywall']) ? !!$_GET['paywall'] : null;
        $license = isset($_GET['license']) ? $_GET['license'] : null;
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : 'top';
        $rating = isset($_GET['rating']) && $_GET['rating'] ? $_GET['rating'] : 1;

        $topLimits = isset($_GET['topLimits']) ? $_GET['topLimits'] : 6;

        $container = null;
        if (isset($_GET['container']) && $_GET['container']) {
            $containerEntity = Entities\Factory::build($_GET['container']);

            if (Core\Security\ACL::_()->read($containerEntity)) {
                $container = $containerEntity->guid;
            }
        }

        $response = [
            'entities' => []
        ];

        try {
            // Posts
            $postGuids = $search->query([
                'text' => $_GET['q'],
                'taxonomies' => 'activity',
                'mature' => $mature,
                'paywall' => $paywall,
                'license' => $license,
                'sort' => $sort,
                'container' => $container,
                'rating' => $rating,
            ], $limit, $offset);

            $posts = [];
            $loadNext = '';

            if ($postGuids) {
                $posts = Di::_()->get('Entities')->get([ 'guids' => $postGuids ]);
                $loadNext = $limit + $offset;
            }

            $response['entities']['activity'] = Factory::exportable($posts);
            $response['load-next'] = $loadNext;

            if ($container) {
                $topTaxonomies = [];
            }

            // Other Top
            foreach ($topTaxonomies as $topTaxonomy) {
                $entities = [];

                if (!is_array($topLimits)) {
                    $topLimit = $topLimits;
                } elseif (isset($topLimits[$topTaxonomy])) {
                    $topLimit = $topLimits[$topTaxonomy] ?: 0;
                } else {
                    $topLimit = 6;
                }

                if (!$offset && $topLimit) {
	                if ($topTaxonomy == 'user') {
                        //for channels we will use suggested search
                        $suggested = $search->suggest('user', $_GET['q'], $topLimit);
                        foreach ($suggested as $row) {
                            $guids[] = $row['guid'];
                        }
		            } else {
                    
                        $guids = $search->query([
                            'text' => $_GET['q'],
                            'taxonomies' => $topTaxonomy,
                            'mature' => $mature,
                            'paywall' => $paywall,
                            'license' => $license,
                            'sort' => $sort,
                            'rating' => $rating,
                        ], $topLimit);
	                }

                    if ($guids) {
                        $entities = Di::_()->get('Entities')->get([ 'guids' => $guids ]);
                    }
                }

                // Filter out by rating TODO: index should handle this
                foreach ($entities as $k => $entity) {
                    if ($entity->getRating() > $rating) {
                        unset($entities[$k]);
                    }
                }

                $response['entities'][$topTaxonomy] = Factory::exportable(array_values($entities));
            }

            return Factory::response($response);
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
