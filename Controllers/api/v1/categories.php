<?php
/**
 * Minds Categories API
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

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
          'categories' => Core\Config\Config::_()->get('categories')
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
