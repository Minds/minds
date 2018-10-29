<?php
/**
 * Minds OAuth 2 pollyfil
 */

namespace Minds\Controllers\oauth2;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class token extends core\page implements Interfaces\page
{

    public function get($pages)
    {

    }

    public function post($pages)
    {
        header("HTTP/1.1 401 Unauthorized");
        return Factory::response([
            'status' => 'error',
            'message' => 'Please upgrade your app',
        ]);
    }

    public function put($pages)
    {
    }

    public function delete($pages)
    {
    }

}
