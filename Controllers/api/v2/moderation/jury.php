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

class jury implements Interfaces\Api
{
    public function get($pages)
    {
        $juryType = $pages[0] ?? 'appeal';

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
