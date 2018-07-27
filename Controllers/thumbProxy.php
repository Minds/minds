<?php
/**
 * Minds Thumbnail Proxy
 */

namespace Minds\Controllers;

use Minds\Controllers;
use Minds\Core;
use Minds\Interfaces;

class thumbProxy extends core\page implements Interfaces\page
{
    /**
     * Get requests
     */
    public function get($pages)
    {
        return (new Controllers\api\v2\media\proxy())->get($pages);
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
