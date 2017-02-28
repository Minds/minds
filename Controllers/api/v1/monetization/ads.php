<?php
/**
 * Minds Monetization Ads
 *
 * @version 1
 * @author Emiliano Balbuena
 */
namespace Minds\Controllers\api\v1\monetization;

use Minds\Components\Controller;
use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class ads extends Controller implements Interfaces\Api
{
    /**
     * Equivalent to HTTP GET method
     * @param  array $pages
     * @return mixed|null
     */
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([]);
        }

        $currentUser = Core\Sandbox::user(Core\Session::getLoggedInUser());

        if (!isset($pages[1]) || !$pages[1]) {
            $user = $currentUser;
        } else {
            $user = new Entities\User($pages[1]);

            if (!$user || !$user->guid) {
                return Factory::response([ 'status' => 'error' ]);
            }
        }

        if ($user->guid != $currentUser->guid && !Core\Session::isAdmin()) {
            return Factory::response([ 'status' => 'error' ]);
        }

        $ads = Core\Di\Di::_()->get('Monetization\Ads');
        $ads->setUser($user);

        $payouts = Core\Di\Di::_()->get('Monetization\Payouts');
        $payouts->setUser($user);

        $merchants = Core\Di\Di::_()->get('Monetization\Merchants');
        $merchants->setUser(Core\Sandbox::user($user, 'merchant'));

        $programs = Core\Di\Di::_()->get('Programs\Manager');
        $programs->setUser(Core\Sandbox::user($user, 'merchant'));

        $isMerchant = (bool) $merchants->getId();
        $canBecomeMerchant = !$merchants->isBanned();

        switch ($pages[0]) {
            case 'status':
                $isParticipant = $programs->isParticipant('ads');

                return Factory::response([
                    'isMerchant' => $isMerchant,
                    'canBecomeMerchant' => $canBecomeMerchant,
                    'enabled' => $isParticipant,
                    'applied' => !$isParticipant && $programs->isApplicant('ads')
                ]);
                break;

            case 'settings':
                return Factory::response([
                    'settings' => $programs->getSettings('ads')
                ]);
                break;

            case 'overview':
                return Factory::response([
                    'isMerchant' => $isMerchant,
                    'canBecomeMerchant' => $canBecomeMerchant,
                    'overview' => $isMerchant ? $ads->getOverview() : false,
                    'payouts' => $isMerchant ? $payouts->getOverview() : false,
                ]);
                break;

            case 'list':
                $offset = isset($_GET['offset']) ? $_GET['offset'] : '';
                $list = $ads->getPayoutsList($offset, 50);

                return Factory::response([
                    'payouts' => $list,
                    'load-next' => $ads->getLastOffset(),
                ]);
                break;
        }

        return Factory::response([]);
    }

    /**
     * Equivalent to HTTP POST method
     * @param  array $pages
     * @return mixed|null
     */
    public function post($pages)
    {
        $currentUser = Core\Sandbox::user(Core\Session::getLoggedInUser());

        $payouts = Core\Di\Di::_()->get('Monetization\Payouts');
        $payouts->setUser($currentUser);

        $merchants = Core\Di\Di::_()->get('Monetization\Merchants');
        $merchants->setUser(Core\Sandbox::user($currentUser, 'merchant'));

        $programs = Core\Di\Di::_()->get('Programs\Manager');
        $programs->setUser(Core\Sandbox::user($currentUser, 'merchant'));

        switch ($pages[0]) {
            case 'settings':
                try {
                    return Factory::response([
                        'applied' => (bool) $programs->setSettings('ads', $_POST)
                    ]);
                } catch (\Exception $e) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
                break;

            case 'apply':
                try {
                    return Factory::response([
                        'applied' => (bool) $programs->apply('ads', $_POST)
                    ]);
                } catch (\Exception $e) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
                break;

            case 'payout':
                try {
                    return Factory::response([
                        'done' => (bool) $payouts->requestPayout()
                    ]);
                } catch (\Exception $e) {
                    return Factory::response([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
                break;
        }
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
