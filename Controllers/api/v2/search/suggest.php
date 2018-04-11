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

class suggest implements Interfaces\Api, Interfaces\ApiIgnorePam
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
                'entities' => []
            ]);
        }

        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 12;
        $hydrate = isset($_GET['hydrate']) && $_GET['hydrate'];

        // TODO: get strict taxonomy from pages[0] when multiple suggests are implemented

        try {
            $entities = $search->suggest('user', $_GET['q'], $limit);
            $entities = array_values(array_filter($entities, function ($entity) {
                return isset($entity['guid']);
            }));

            if ($entities && $hydrate) {
                $guids = [];
                
                foreach ($entities as $entity) {
                    $guids[] = $entity['guid'];
                }
                
                if ($guids) {
                    $entities = Factory::exportable(Di::_()->get('Entities')->get([ 'guids' => $guids ]));
                }
            }

            return Factory::response([
                'entities' => $entities
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
