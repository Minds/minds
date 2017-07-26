<?php
/**
 * Minds Categories API
 *
 * @version 1
 * @author Mark Harding
 */

namespace Minds\Controllers\api\v1;

use Minds\Api\Factory;
use Minds\Core\Config;
use Minds\Interfaces;

class categories implements Interfaces\Api
{
    /**
     * Returns the categories
     * @param array $pages
     *
     */
    public function get($pages)
    {
        $response = [
            'categories' => Config::_()->get('categories')
        ];
        return Factory::response($response);
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
