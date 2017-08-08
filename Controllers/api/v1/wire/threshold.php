<?php

namespace Minds\Controllers\api\v1\wire;

use Minds\Api\Factory;
use Minds\Core\Di\Di;
use Minds\Core\Session;
use Minds\Entities;
use Minds\Interfaces;

class threshold implements Interfaces\Api
{
    /**
     * Checks if the amount of wires the logged user has sent to the activity owner passes the threshold, in which case
     * it unlocks/removes the paywall from the activity
     */
    public function get($pages)
    {
        $response = [];
        if (!isset($pages[0])) {
            return Factory::response($response);
        }

        $activity = new Entities\Activity($pages[0]);

        if (!$activity) {
            return Factory::response(['status' => 'error', 'message' => 'Activity couldn\'t be found']);
        }

        $threshold = $activity->getWireThreshold();

        //make sure legacy posts can work
        if (!$threshold && $activity->isPaywall()) {
            $threshold = [
              'type' => 'money',
              'min' => $activity->getOwnerEntity()->getMerchant()['exclusive']['amount']
            ];
        }

        $amount = 0;
        $repository = Di::_()->get('Wire\Repository');
        if ($threshold['type'] == 'points') {
            $amount = $repository->getSumBySenderForReceiver(Session::getLoggedInUser()->guid,
                $activity->getOwnerGUID(), 'points', (new \DateTime('midnight'))->modify("-30 days"));
        } else {
            $amount = $repository->getSumBySenderForReceiver(Session::getLoggedInUser()->guid,
                $activity->getOwnerGUID(), 'usd', (new \DateTime('midnight'))->modify("-30 days"));
        }

        // if the user wires amounts to the threshold or more
        if ($amount - $threshold['min'] >= 0) {
            $activity->paywall = false;
            $response['activity'] = $activity->export();
        }

        return Factory::response($response);
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
