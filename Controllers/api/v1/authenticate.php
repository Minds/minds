<?php
/**
 * Minds Subscriptions
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class authenticate implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * NOT AVAILABLE
     */
    public function get($pages)
    {
        return Factory::response(array('status'=>'error', 'message'=>'GET is not supported for this endpoint'));
    }

    /**
     * Registers a user
     * @param array $pages
     *
     * @SWG\Post(
     *     summary="Create a new channel",
     *     path="/v1/register",
     *     @SWG\Response(name="200", description="Array")
     * )
     */
    public function post($pages)
    {
        if (!Core\Security\XSRF::validateRequest()) {
            return false;
        }

        $user = new Entities\User(strtolower($_POST['username']));
        if ($user->isEnabled() &&
          $user->username &&
          Core\Security\Password::check($user, $_POST['password'])) {
            //is twofactor authentication setup?
            try {
                if (login($user) && Core\Session::isLoggedIn()) {
                    $response['status'] = 'success';
                    $response['user'] = $user->export();
                }
            } catch (\Exception $e) {
                header('HTTP/1.1 ' + $e->getCode(), true, $e->getCode());
                $response['status'] = "error";
                $response['code'] = $e->getCode();
                $response['message'] = $e->getMessage();
            }
        } else {
            header('HTTP/1.1 401 Unauthorized', true, 401);
            $response['status'] = 'failed';
        }

        return Factory::response($response);
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
        logout();

        return Factory::response(array());
    }
}
