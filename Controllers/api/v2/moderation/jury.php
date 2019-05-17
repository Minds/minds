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
use Minds\Core\Reports\Jury\JuryClosedException;
use Minds\Core\Reports\Summons\SummonsNotFoundException;

class jury implements Interfaces\Api
{
    public function get($pages)
    {
        $juryType = $pages[0] ?? 'appeal';

        if ($juryType === 'appeal' || !Core\Session::isAdmin()) {
            exit;
        }

        $juryManager = Di::_()->get('Moderation\Jury\Manager');
        $juryManager->setJuryType($juryType)
            ->setUser(Core\Session::getLoggedInUser());

        if (isset($pages[1])) {
            $report = $juryManager->getReport($pages[1]);
            return Factory::response([
                'report' => $report ? $report->export() : null,
            ]);
        }

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
        $urn = $pages[1] ?? null;
        $uphold = $_POST['uphold'] ?? null;

        if (!$juryType) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must supply the jury type in the URI like /:juryType/:entityGuid',
            ]);
        }
        
        if (!$urn) {
            return Factory::response([
                'status' => 'error',
                'message' => 'You must supply the entity urn in the URI like /:juryType/:urn',
            ]);
        }

        if (!isset($uphold)) {
            return Factory::response([
                'status' => 'error',
                'message' => 'uphold must be supplied in POST body',
            ]);
        }

        if (!Core\Session::getLoggedInUser()->getPhoneNumberHash()) {
            return Factory::response([
                'status' => 'error',
                'message' => 'juror must be in the rewards program',
            ]);
        }

        $juryManager = Di::_()->get('Moderation\Jury\Manager');
        $moderationManager = Di::_()->get('Moderation\Manager');
        $report = $moderationManager->getReport($urn);

        $decision = new Decision();
        $decision
            ->setAppeal($juryType === 'appeal')
            ->setUphold($uphold)
            ->setReport($report)
            ->setTimestamp(time())
            ->setJurorGuid(Core\Session::getLoggedInUser()->getGuid())
            ->setJurorHash(Core\Session::getLoggedInUser()->getPhoneNumberHash());

        try {
            $juryManager->cast($decision);
        } catch (JuryClosedException $e) {
            return Factory::response([
                'status' => 'error',
                'message' => 'The jury has already closed'
            ]);
        } catch (SummonsNotFoundException $e) {
            return Factory::response([
                'status' => 'error',
                'message' => 'A summons could not be found'
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
