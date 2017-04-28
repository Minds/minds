<?php
/**
 * Minds Core Search Tag Cloud API
 *
 * @version 1
 * @author Mar Harding
 */
namespace Minds\Controllers\api\v1\search;

use Minds\Core;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Search;

class tagcloud implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    public function get($pages)
    {
        return Factory::response([
            'tags' => (new Search\Tagcloud())->get()
        ]);
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
