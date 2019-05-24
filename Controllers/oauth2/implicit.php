<?php

namespace Minds\Controllers\oauth2;

use Minds\Core;
use Minds\Interfaces;
use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Core\OAuth\Entities\UserEntity;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\SapiEmitter;

class Implicit extends core\page implements Interfaces\page
{
    public function get($pages)
    {
        $request = ServerRequestFactory::fromGlobals();
        $response = new HtmlResponse('');
        $user = Session::getLoggedinUser();
        if (!$_GET['checkout_key'] || $_GET['checkout_key'] != $_COOKIE['checkout_key'] || $user === null) {
            \forward('/');
        }

        $server = Di::_()->get('OAuth\Server\Authorization');
        try {
            $result = $server->validateAuthorizationRequest($request);

            $entity = new UserEntity();
            $entity->setIdentifier($user->getGuid());
            $result->setUser($entity);
            $result->setAuthorizationApproved(true);
            //return a redirect with a jwt token
            $response = $server->completeAuthorizationRequest($result, $response);
        } catch (OAuthServerException $exception) {
            $response = $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            $body = [
                'status' => 'error',
                'error' => $exception->getMessage(),
                'message' => $exception->getMessage(),
            ];
            $response = new HtmlResponse($exception->getMessage());
        }

        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }

    public function post($pages)
    {
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }
}
