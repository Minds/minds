<?php


namespace Minds\Controllers\api\v2\analytics;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Helpers\Counters;
use Minds\Interfaces;

class pageview implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        if (!isset($_POST['url'])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must provide a url'
            ]);
        }

        $url = $_POST['url'];

        $event = new Core\Analytics\Metrics\Event();
        $event
            ->setType('action')
            ->setProduct('platform')
            ->setAction('pageview')
            ->setRouteUri($url)
            ->setUserAgent($_SERVER['HTTP_USER_AGENT'])
            ->setCookieId($_COOKIE['minds']);

        if (isset($_POST['referrer']) && $_POST['referrer']) {
            $event->setReferrerUri((string) $_POST['referrer']);
        }

        if ($ip = $_SERVER['HTTP_X_FORWARDED_FOR']) {
            $event->setIpAddr($ip);
        }

        if (Core\Session::isLoggedIn()) {
            $event->setUserGuid(Core\Session::getLoggedInUser()->guid);
        }
        $event->push();

        return Factory::response([]);
    }

    public function put($pages)
    {
        return Factory::response([]);
    }

    public function delete($pages)
    {
        return Factory::response([]);
    }

}
