<?php
/**
 * Minds Settings
 *
 * @author emi
 */

namespace Minds\Controllers\api\v1\minds;

use Minds;
use Minds\Core;
use Minds\Interfaces;
use Minds\Api\Factory;

class config implements Interfaces\Api, Interfaces\ApiIgnorePam
{

    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        $minds = [
            "cdn_url" => Minds\Core\Config::_()->get('cdn_url') ?: Minds\Core\Config::_()->cdn_url,
            "cinemr_url" => Minds\Core\Config::_()->get('cinemr_url') ?: Minds\Core\Config::_()->cinemr_url,
            "site_url" => Minds\Core\Config::_()->get('site_url') ?: Minds\Core\Config::_()->site_url,
            "socket_server" => Minds\Core\Config::_()->get('sockets-server-uri') ?: 'ha-socket-io-us-east-1.minds.com:3030',
            "thirdpartynetworks" => Minds\Core\Di\Di::_()->get('ThirdPartyNetworks\Manager')->availableNetworks(),
            "categories" => Minds\Core\Config::_()->get('categories') ?: [],
            "stripe_key" => Minds\Core\Config::_()->get('payments')['stripe']['public_key'],
            "recaptchaKey" => Minds\Core\Config::_()->get('google')['recaptcha']['site_key'],
            "max_video_length" => (Core\Session::getLoggedInUser() && Core\Session::getLoggedInUser()->isPlus())
                ? Minds\Core\Config::_()->get('max_video_length_plus')
                : Minds\Core\Config::_()->get('max_video_length'),
            "features" => (object) (Minds\Core\Config::_()->get('features') ?: []),
            "blockchain" => (object) Minds\Core\Di\Di::_()->get('Blockchain\Manager')->getPublicSettings(),
            "plus" => Minds\Core\Config::_()->get('plus'),
            "report_reasons" => Minds\Core\Config::_()->get('report_reasons'),
            "last_tos_update" => (Minds\Core\Config::_()->get('last_tos_update') ?: time()),
        ];

        return Factory::response($minds);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
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
