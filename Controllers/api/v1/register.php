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
use Minds\Helpers;

class register implements Interfaces\Api, Interfaces\ApiIgnorePam
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
        if (!isset($_POST['username']) || !isset($_POST['password']) || !isset($_POST['username']) || !isset($_POST['email'])) {
            return Factory::response(['status'=>'error']);
        }

        if (!$_POST['username'] || !$_POST['password'] || !$_POST['username'] || !$_POST['email']) {
            return Factory::response(['status'=>'error', 'message' => "Please fill out all the fields"]);
        }

        try {
            $captcha = Core\Di\Di::_()->get('Security\ReCaptcha');
            $captcha->setAnswer($_POST['captcha']);
            if (isset($_POST['captcha']) && !$captcha->validate()) {
                throw new \Exception('Captcha failed');
            }

            $user = register_user($_POST['username'], $_POST['password'], $_POST['username'], $_POST['email'], false);
            $guid = $user->guid;

            $params = [
                'user' => $user,
                'password' => $_POST['password'],
                'friend_guid' => "",
                'invitecode' => "",
                'referrer' => isset($_COOKIE['referrer']) ? $_COOKIE['referrer'] : ''
            ];

            elgg_trigger_plugin_hook('register', 'user', $params, true);
            Core\Events\Dispatcher::trigger('register', 'user', $params);
            Core\Events\Dispatcher::trigger('register/complete', 'user', $params);

            login($params['user']);
            $response = array(
              'guid' => $guid,
              'user' => $params['user']->export()
            );
        } catch (\Exception $e) {
            $response = array('status'=>'error', 'message'=>$e->getMessage());
        }
        return Factory::response($response);
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
