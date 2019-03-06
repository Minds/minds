<?php
/**
 * Api endpoint to create a report
 */
namespace Minds\Controllers\api\v2\moderation;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Entities;
use Minds\Entities\Activity;
use Minds\Interfaces;
use Minds\Core\Reports;

class report implements Interfaces\Api
{
    public function get($pages)
    {
        return Factory::response([]);
    }

    public function post($pages)
    {
        $user = Session::getLoggedInUser();

        if (!$user) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must be logged into make a report',
            ]);
        }

        $manager = Di::_()->get('Moderation\Reports\Manager');

        if (!isset($_POST['entity_guid'])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Entity guid must be supplied',
            ]);
        }

        if (!isset($_POST['reason_code'])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'A reason code must be provided',
            ]);
        }

        $report = new Reports\Report();
        $report->setEntityGuid($_POST['entity_guid'])
            ->setReporterGuid($user->getGuid())
            ->setReasonCode((int) $_POST['reason_code'])
            ->setSubReasonCode($_POST['sub_reason_code'] ?? null)
            ->setTimestamp(round(microtime(true) * 1000));

        if (!$manager->add($report)) {
            Factory::response([
                'status' => 'error',
                'message' => 'Report could not be saved',
            ]);
        }
        
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
