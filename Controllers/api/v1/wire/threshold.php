<?php

namespace Minds\Controllers\api\v1\wire;

use Minds\Api\Factory;
use Minds\Entities;
use Minds\Interfaces;

class threshold implements Interfaces\Api
{
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
        $totals = $activity->getWireTotals();

        $amount = $threshold->type == 'points' ? $totals->points : $totals->usd;

        // if the user wires amounts to the threshold or more
        if ($amount - $threshold->amount <= 0) {
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
        $this->cancelSubscription();

        return Factory::response([]);
    }
}
