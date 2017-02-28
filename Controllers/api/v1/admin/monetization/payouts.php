<?php
/**
 * Minds Payouts Queue API
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\admin\monetization;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class payouts implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * @param array $pages
     */
    public function get($pages)
    {
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 50;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : '';

        switch ($pages[0]) {
            case 'queue':
                $admin = Core\Di\Di::_()->get('Monetization\Admin');

                $list = $admin->getQueue($limit, $offset);
                $response = [
                    'payouts' => $list
                ];

                if ($list) {
                    $response['load-next'] = (string) end($list)['guid'];
                }

                return Factory::response($response);
                break;
        }
        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([]);
        }

        $guid = $pages[0];

        try {
            $payouts = Core\Di\Di::_()->get('Monetization\Payouts');
            return Factory::response([
                'done' => (bool) $payouts->payout($guid)
            ]);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }
}
