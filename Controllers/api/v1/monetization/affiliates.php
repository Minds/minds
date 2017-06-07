<?php
/**
 * Minds Monetization Affiliates
 *
 * @version 1
 * @author Mark Harding
 */
namespace Minds\Controllers\api\v1\monetization;

use Minds\Components\Controller;
use Minds\Core;
use Minds\Core\Config;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Payments\Merchant;

class affiliates extends Controller implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        return Factory::response([]);
    }


    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP PUT method
     * @param  array $pages
     * @return mixed|null
     */
    public function put($pages)
    {
        $programs = Core\Di\Di::_()->get('Programs\Manager');
        $programs->setUser(Core\Session::getLoggedinUser())
          ->applyAndAccept('affiliate');
        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP DELETE method
     * @param  array $pages
     * @return mixed|null
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
