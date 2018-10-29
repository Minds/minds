<?php
/**
 * Minds Core Search API
 *
 * @version 2
 * @author Emiliano Balbuena
 */

namespace Minds\Controllers\api\v2\search\suggest;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Entities;

class tags implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        /** @var Core\Search\Hashtags\Manager $search */
        $manager = Di::_()->get('Search\Hashtags\Manager');

        if (!isset($_GET['q']) || !$_GET['q']) {
            return Factory::response([
                'entities' => []
            ]);
        }

        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 12;

        $manager->setLimit($limit);

        return Factory::response([
            'tags' => $manager->suggest($_GET['q'])
        ]);

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
