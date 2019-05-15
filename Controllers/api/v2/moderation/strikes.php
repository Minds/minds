<?php
/**
 * Api endpoint to get strikes
 */
namespace Minds\Controllers\api\v2\moderation;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Entities;
use Minds\Entities\Activity;
use Minds\Interfaces;
use Minds\Core\Di\Di;
use Minds\Core\Reports\Appeals\Appeal;

class strikes implements Interfaces\Api
{
    public function get($pages)
    {
        if ($_POST['offset']) {
            return Factory::response([ ]);
        }

        $strikesManager = Di::_()->get('Moderation\Strikes\Manager');
        $strikes = $strikesManager->getList([
            'hydrate' => true,
            'user' => Core\Session::getLoggedInUser(),
        ]);

        return Factory::response([
            'strikes' => Factory::exportable($strikes),
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
