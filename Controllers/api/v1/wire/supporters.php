<?php
/**
 * Minds Wire Supporters
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1\wire;

use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Wire;
use Minds\Entities;
use Minds\Entities\User;

class supporters implements Interfaces\Api
{
    /**
     * GET
     */
    public function get($pages)
    {
        $response = [];
        $receiver_guid = isset($pages[0]) ? $pages[0] : Core\Session::getLoggedInUser()->guid;

        $repo = Di::_()->get('Wire\Repository');
        $wires = $repo->getWiresByReceiver($receiver_guid);

        $response['wires'] = Factory::exportable($wires);

        return Factory::response($response);
    }

    /**
     * POST
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * PUT
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * DELETE
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
