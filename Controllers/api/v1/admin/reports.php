<?php
/**
 * Minds Admin: User Reports
 *
 * @version 1
 * @author Emi Balbuena
 *
 */
namespace Minds\Controllers\api\v1\admin;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class reports implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * @param array $pages
     */
    public function get($pages)
    {
        $response = [];

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 12;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : "";
        $state = isset($pages[0]) ? $pages[0] : null;

        /** @var Core\Reports\Repository $repository */
        $repository = Di::_()->get('Reports\Repository');

        $reports = $repository->getAll([
            'state' => $state,
            'limit' => $limit,
            'offset' => $offset
        ]);

        if ($reports && $reports['data']) {
            $response['reports'] = Factory::exportable($reports['data']);
            $response['load-next'] = $reports['next'];
        }

        return Factory::response($response);
    }

    /**
     * @param array $pages
     */
    public function post($pages)
    {
        $response = [];

        if (count($pages) < 2) {
            return Factory::response($response);
        }

        /** @var Core\Reports\Actions $actions */
        $actions = Di::_()->get('Reports\Actions');

        $guid = $pages[0];
        $action = $pages[1];

        $reason = $_POST['reason'] ?: null;

        switch ($action) {
            case 'archive':
                $response['done'] = $actions->archive($guid, $reason);
                break;
            case 'explicit':
                $response['done'] = $actions->markAsExplicit($guid, $reason);
                break;
            case 'spam':
                $response['done'] = $actions->markAsSpam($guid, $reason);
                break;
            case 'spam':
                $response['done'] = (new Core\Reports())->spam($guid, $reason);
                break;
            case 'delete':
                $response['done'] = $actions->delete($guid, $reason);
                break;
        }

        return Factory::response($response);
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
