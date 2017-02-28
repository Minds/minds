<?php
/**
 * Minds Programs Queue API
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\admin;

use Minds\Core;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class programs implements Interfaces\Api, Interfaces\ApiAdminPam
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
                $admin = Core\Di\Di::_()->get('Programs\Admin');

                $list = $admin->getQueue($limit, $offset);
                $response = [
                    'applications' => $list
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
        return Factory::response([]);
    }

    /**
     * @param array $pages
     */
    public function put($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([]);
        }

        $guid = $pages[0];

        try {
            $programs = Core\Di\Di::_()->get('Programs\Manager');
            return Factory::response([
                'done' => (bool) $programs->accept($guid)
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
    public function delete($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([]);
        }

        $guid = $pages[0];

        try {
            $programs = Core\Di\Di::_()->get('Programs\Manager');
            return Factory::response([
                'done' => (bool) $programs->reject($guid)
            ]);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
