<?php
/**
 * Validate password for logged in user
 *
 * @version 1
 * @author Nicolas Ronchi
 */
namespace Minds\Controllers\api\v2\settings;

use Minds\Entities;
use Minds\Interfaces;

use Minds\Core\Di\Di;
use Minds\Api\Factory;
use Minds\Core;

class password implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response(['status'=>'error', 'message'=>'GET is not supported for this endpoint']);
    }

    public function post($pages)
    {
        switch ($pages[0]) {
            case "validate":
                $validator = Di::_()->get('Security\Password');

                if ($validator->check(Core\Session::getLoggedinUser(), $_POST['password'])) {
                    return Factory::response(['status' => 'success']);
                } else {
                    header('HTTP/1.1 401 Unauthorized', true, 401);
                    return Factory::response(['status' => 'failed']);
                }
                break;
        }

        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response(array());
    }

    public function delete($pages)
    {
        return Factory::response(array());
    }
}
