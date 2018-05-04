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
        /** @var Core\Security\LoginAttempts $attempts */
        $attempts = Core\Di\Di::_()->get('Security\LoginAttempts');

        if (!$user->username) {
            header('HTTP/1.1 401 Unauthorized', true, 401);
            return Factory::response(['status' => 'failed']);
        }

        $attempts->setUser($user);

        if ($attempts->checkFailures()) {
            return Factory::response([
                'status' => 'error',
                'message' => 'LoginException::AttemptsExceeded'
            ]);
        }

        if (!$user->isEnabled() && !$user->isBanned()) {
            $user->enable();
        }

        try {
            if (!Core\Security\Password::check($user, $_POST['password'])) {
                $attempts->logFailure();
                header('HTTP/1.1 401 Unauthorized', true, 401);
                return Factory::response(['status' => 'failed']);
            }
        } catch (Core\Security\Exceptions\PasswordRequiresHashUpgradeException $e) {
            $user->password = Core\Security\Password::generate($user, $_POST['password']);
            $user->override_password = true;
            $user->save();
        }

        
        if ($user->last_login < 1514764800) {
            $user->boost_rating = 1;
            $user->save();
        }

        try {
            if (login($user) && Core\Session::isLoggedIn()) {
                $attempts->resetFailuresCount(); // Reset any previous failed login attempts
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
        $guid = Core\Session::getLoggedinUser()->guid;

        logout();

        if (isset($pages[0]) && $pages[0] === 'all') {
            (new Core\Data\Sessions())->destroyAll($guid);
        }

        return Factory::response([]);
    }
}
