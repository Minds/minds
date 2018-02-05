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
use Minds\Exceptions\TwoFactorRequired;

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

        if (!$user->username) {
            header('HTTP/1.1 401 Unauthorized', true, 401);
            return Factory::response(['status' => 'failed']);
        }

        if (!$user->isEnabled() && !$user->isBanned()) {
            $user->enable();
        }

        try {
            if (!Core\Security\Password::check($user, $_POST['password'])) {
                header('HTTP/1.1 401 Unauthorized', true, 401);
                return Factory::response(['status' => 'failed']);
            }
        } catch (Core\Security\Exceptions\PasswordRequiresHashUpgradeException $e) {
            $user->password = Core\Security\Password::generate($user, $_POST['password']);
            $user->override_password = true;
            $user->save();
        }

        try {
            if (login($user) && Core\Session::isLoggedIn()) {
                $response['status'] = 'success';
                $response['user'] = $user->export();
            }
        } catch (TwoFactorRequired $e) {
            header('HTTP/1.1 ' + $e->getCode(), true, $e->getCode());
            $response['status'] = "error";
            $response['code'] = $e->getCode();
            $response['message'] = $e->getMessage();
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
