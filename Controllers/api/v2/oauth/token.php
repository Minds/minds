<?php
/**
 * Minds OAuth Token endpoint
 */
namespace Minds\Controllers\api\v2\oauth;

use Minds\Api\Factory;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Notification\PostSubscriptions\Manager;
use Minds\Core\Session;
use Minds\Interfaces;
use Minds\Core\Di\Di;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\SapiEmitter;

class token implements Interfaces\Api, Interfaces\ApiIgnorePam
{

    public function get($pages = [])
    {
        
    }

    public function post($pages = [])
    {
        // TODO: this will be refactored with new api structure
        $request = ServerRequestFactory::fromGlobals();
        $response = new JsonResponse([]);

        $server = Di::_()->get('OAuth\Server\Authorization');

        try {
            $result = $server->respondToAccessTokenRequest($request, $response);
            $body = json_decode($response->getBody(), true);
            $body['status'] = 'success';
            $response = new JsonResponse($body);
        } catch (OAuthServerException $exception) {
            $response = $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            $body = [
                'status' => 'error',
                'error' => $exception->getMessage(),
                'message' => $exception->getMessage(),
            ];
            $response = new JsonResponse($body);
        }
        
        $emitter = new SapiEmitter();
        $emitter->emit($response);  
    }

    public function put($pages = [])
    {
        
    }

    public function delete($pages = [])
    {
        $request = ServerRequestFactory::fromGlobals();
        $response = new JsonResponse([]);

        try {
            $server = Di::_()->get('OAuth\Server\Resource');
            $accessTokenRepository = Di::_()->get('OAuth\Repositories\AccessToken');
            $refreshTokenRepository = Di::_()->get('OAuth\Repositories\RefreshToken');
            
            $request = $server->validateAuthenticatedRequest($request);

            $tokenId = $request->getAttribute('oauth_access_token_id');
            $accessTokenRepository->revokeAccessToken($tokenId);
            $refreshTokenRepository->revokeRefreshToken($tokenId);
            $response = new JsonResponse([]);
        } catch (\Exception $e) {
            $body = [
                'status' => 'error',
                'message' => $exception->getMessage(),
            ];
            $response = new JsonResponse($body, 500); 
        }

        $emitter = new SapiEmitter();
        $emitter->emit($response);
    }

}
