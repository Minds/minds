<?php

namespace Minds\Controllers;

use Minds;
use Minds\Api\Factory;
use Minds\Interfaces;
use Minds\Core\Di\Di;
use Minds\Common\Cookie;
use Minds\Core;

class checkout implements Interfaces\Api
{
    public function get($pages)
    {
        $checkoutKey = ['checkout_key' => base64_encode(openssl_random_pseudo_bytes(8)), 'usd' => $_GET['usd'] ?? 25];
        $cookie = new Cookie();
        $cookie
            ->setName('checkout_key')
            ->setValue($checkoutKey['checkout_key'])
            ->setExpire(time() + 300)
            ->setPath('/')
            ->setHttpOnly(true)
            ->create();
        Core\page::forward(Di::_()->get('Config')->get('checkout_url').'authorize?'.http_build_query($checkoutKey));
    }

    public function post($pages)
    {
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
