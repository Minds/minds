<?php
/**
 * Minds Entity Report API
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\entities\report;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Interfaces;
use Minds\Api\Factory;

class appeal implements Interfaces\Api
{
    public function get($pages)
    {
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 12;
        $offset = isset($_GET['offset']) ? $_GET['offset'] : '';

        $filters = [
            'review' => 'actioned',
            'pending' => 'appealed',
            'approved' => 'appeal_approved',
            'rejected' => 'appeal_rejected'
        ];

        $filter = isset($pages[0]) ? $pages[0] : '';

        if (!isset($filters[$filter])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Invalid filter'
            ]);
        }

        $response = [];

        /** @var Core\Reports\Repository $repository */
        $repository = Di::_()->get('Reports\Repository');

        $reports = $repository->getAll([
            'owner' => Core\Session::getLoggedinUser(),
            'state' => $filters[$filter],
            'limit' => $limit,
            'offset' => $offset
        ]);

        if ($reports) {
            $response['data'] = Factory::exportable($reports['data']);
            $response['load-next'] = $reports['next'];
        }

        return Factory::response($response);
    }

    public function post($pages)
    {
        if (!is_numeric($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Missing appeal'
            ]);
        }

        if (!isset($_POST['note']) || !$_POST['note']) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must provide an appeal note'
            ]);
        }

        /** @var Core\Reports\Appeals $appeals */
        $appeals = Di::_()->get('Reports\Appeals');

        try {
            $done = $appeals
                ->appeal($pages[0], Core\Session::getLoggedinUser(), $_POST['note']);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        if (!$done) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Error saving appeal'
            ]);
        }

        return Factory::response([ 'done' => true ]);
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
