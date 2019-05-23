<?php
/**
 * Api endpoint to appeal a decision
 */
namespace Minds\Controllers\api\v2\moderation;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Entities;
use Minds\Entities\Activity;
use Minds\Interfaces;
use Minds\Core\Di\Di;
use Minds\Core\Reports\Appeals\Appeal;

class appeals implements Interfaces\Api
{
    public function get($pages)
    {
        if ($_POST['offset']) {
            return Factory::response([ ]);
        }

        $appealManager = Di::_()->get('Moderation\Appeals\Manager');
        $appeals = $appealManager->getList([
            'hydrate' => true,
            'showAppealed' => ($pages[0] ?? 'review') === 'pending',
            'state' => $pages[0],
            'owner_guid' => Core\Session::getLoggedInUser()->getGuid(),
        ]);

        return Factory::response([
            'appeals' => Factory::exportable($appeals),
        ]);
    }

    public function post($pages)
    {
        $entityGuid = $pages[0] ?? null;

        if (!$entityGuid) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must supply the entity guid in the URI like /appeals/:entityGuid',
            ]);
        }

        if (!($_POST['note'] ?? null)) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must supply a note along with your appeal',
            ]);
        }

        $appealManager = Di::_()->get('Moderation\Appeals\Manager');
        $moderationManager = Di::_()->get('Moderation\Manager');
        $report = $moderationManager->getReport($entityGuid);

        $appeal = new Appeal();
        $appeal->setNote($_POST['note'])
            ->setReport($report)
            ->setTimestamp(time());

        $success = $appealManager->appeal($appeal);

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
