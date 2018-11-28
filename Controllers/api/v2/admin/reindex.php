<?php
/**
 * Reindex entities
 */

namespace Minds\Controllers\api\v2\admin;

use Minds\Api\Factory;
use Minds\Entities;
use Minds\Core\Di\Di;
use Minds\Interfaces;

class reindex implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        $guid = intval($_POST['guid']);

        if (!$guid) {
            return Factory::response(['status' => 'error', 'message' => 'You must send a GUID.']);
        }

        $entity = Entities\Factory::build($guid);

        if (!$entity) {
            return Factory::response(['status' => 'error', 'message' => 'Entity not found.']);
        }

        /** @var Core\Events\Dispatcher $dispatcher */
        $dispatcher = Di::_()->get('EventsDispatcher');

        $dispatcher->trigger('search:index', 'all', [
            'entity' => $entity,
            'immediate' => true
        ]);

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
