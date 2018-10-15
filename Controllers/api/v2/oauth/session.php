<?php
/**
 * Minds OAuth Token endpoint
 */
namespace Minds\Controllers\api\v2\oauth;

use Minds\Api\Factory;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Notification\PostSubscriptions\Manager;
use Minds\Core\Session as Sess;
use Minds\Interfaces;
use Minds\Core\Di\Di;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\SapiEmitter;

class session implements Interfaces\Api
{

    public function get($pages = [])
    {
        $user = Sess::getLoggedInUser();

        if (!$user) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You are not logged in',
            ]);
        }

        return Factory::response([
            'user' => $user->export(),
            // any other settings?
        ]);
    }

    public function post($pages = [])
    {
    }

    public function put($pages = [])
    {
    }

    public function delete($pages = [])
    {
    }

}
