<?php
namespace Minds\Controllers\api\v1\thirdpartynetworks;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\ThirdPartyNetworks;
use Minds\Interfaces;
use Minds\Api\Factory;

/**
 * Minds Twitter endpoint
 */
class twitter implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    /**
     * Get request
     * @param array $pages
     */
    public function get($pages)
    {
        $response = [];

        $twitter = ThirdPartyNetworks\Factory::build('twitter');

        switch($pages[0]) {
            case 'link':
                forward($twitter->buildAuthorizeUrl());
                break;
            case 'login-callback':
                $twitter->getApiCredentials();
                $twitter->authorized($_REQUEST);

                $json = json_encode([ 'service' => 'twitter', 'done' => true ]);

                echo "<script>window.opener.onSuccessCallback($json); window.close();</script>";
                exit;
                break;
        }

        return Factory::response($response);
    }

    /**
     * Post request
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Put request
     * @param array $pages
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * Delete request
     * @param array $pages
     */
    public function delete($pages)
    {
        Factory::isLoggedIn();

        $twitter = ThirdPartyNetworks\Factory::build('twitter');
        $user = Core\Session::getLoggedInUser();

        $twitter->dropApiCredentials();
        return Factory::response([]);
    }
}
