<?php
namespace Minds\Controllers\api\v1\thirdpartynetworks;
/**
 * Minds TPN General endpoint
 */

use Minds\Core;
use Minds\Interfaces;
use Minds\Api\Factory;

class status implements Interfaces\Api
{

    /**
     * Get request
     * @param array $pages
     */
    public function get($pages)
    {
        return Factory::response([
            'thirdpartynetworks' => Core\Di\Di::_()->get('ThirdPartyNetworks\Manager')->status()
        ]);
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
        return Factory::response(array());
    }

    /**
     * Delete request
     * @param array $pages
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
