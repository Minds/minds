<?php
/**
 * Api endpoint for jury duty
 */
namespace Minds\Controllers\api\v2\moderation;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Entities;
use Minds\Entities\Activity;
use Minds\Interfaces;
use Minds\Core\Di\Di;
use Minds\Core\Reports\Jury\Decision;

class jury implements Interfaces\Api
{
    public function get($pages)
    {
        $juryType = $pages[0] ?? 'appeal';

        if ($juryType !== 'appeal' && !Core\Session::isAdmin()) {
            exit;
        }

        $juryManager = Di::_()->get('Moderation\Jury\Manager');
        $juryManager->setJuryType($juryType)
            ->setUser(Core\Session::getLoggedInUser());

        $reports = $juryManager->getUnmoderatedList([
            'limit' => 12,
            'hydrate' => true,
        ]);

        return Factory::response([
            'reports' => Factory::exportable($reports),
        ]);
    }

    public function post($pages)
    {
        $juryType = $pages[0] ?? null;
        $entityGuid = $pages[1] ?? null;
        $action = $_POST['decision'] ?? null;

        if (!$juryType) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must supply the jury type in the URI like /:juryType/:entityGuid',
            ]);
        }
        
        if (!$entityGuid) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must supply the entity guid in the URI like /:juryType/:entityGuid',
            ]);
        }

        if (!$action) {
            return Factory::response([
                'status' => 'error',
                'message' => 'decision must be supplied in POST body',
            ]);
        }

        $juryManager = Di::_()->get('Moderation\Jury\Manager');
        $moderationManager = Di::_()->get('Moderation\Manager');
        $report = $moderationManager->getReport($entityGuid);

        $decision = new Decision();
        $decision
            ->setAppeal($juryType === 'appeal')
            ->setAction($action)
            ->setReport($report)
            ->setTimestamp(round(microtime(true) * 1000))
            ->setJurorGuid(Core\Session::getLoggedInUser()->getGuid());

        $juryManager->cast($decision);
        
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
