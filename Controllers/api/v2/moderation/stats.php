<?php
/**
 * Api endpoint for jury stats
 */
namespace Minds\Controllers\api\v2\moderation;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Entities;
use Minds\Entities\Activity;
use Minds\Interfaces;
use Minds\Core\Di\Di;
use Minds\Core\Reports\Jury\Decision;

class stats implements Interfaces\Api
{
    public function get($pages)
    {
        $statsManager = Di::_()->get('Moderation\Stats\Manager');
        return Factory::response([
            'stats' => $statsManager->getPublicStats(),
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
