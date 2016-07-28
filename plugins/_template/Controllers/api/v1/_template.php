<?php
/**
 * {{plugin.name}}
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Plugin\{{plugin.name}}\Controllers\api\v1;

use Minds\Core;
use Minds\Entities;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;

class {{plugin.name}} implements Interfaces\Api
{

    public function get($pages)
    {
        return Factory::response([]);
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
