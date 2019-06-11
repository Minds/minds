<?php

namespace Minds\Controllers\api\v2;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Interfaces;

class analytics implements Interfaces\Api, Interfaces\ApiIgnorePam
{
    public function get($pages)
    {
        if (!isset($pages[0])) {
            return Factory::response([
                'status' => 'error',
                'message' => 'report must be provided'
            ]);
        }

        $span = 12;
        $unit = 'month';

        switch ($_GET['timespan'] ?? null) {
            case 'hourly':
                $span = 24;
                $unit = 'hour';
                break;
            case 'daily':
                $span = 7;
                $unit = 'day';
                break;
            case 'monthly':
                $span = 12;
                $unit = 'month';
                break;
        }

        /** @var Core\Analytics\Metrics\Manager $manager */
        $manager = Di::_()->get('Analytics\Graphs\Manager');

        try {
            $urn = "urn:graph:" . $manager::buildKey([
                'aggregate' => $pages[0],
                'key' => $_GET['key'] ?? null,
                'span' => $span,
                'unit' => $unit,
            ]); 
            $graph = $manager->get($urn);
            if (!$graph) {
                throw new \Exception("Graph not found");
            }
            $data = $graph->getData();
        } catch (\Exception $e) {
            error_log($e);
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return Factory::response([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function post($pages)
    {
        // TODO: Implement post() method.
    }

    public function put($pages)
    {
        // TODO: Implement put() method.
    }

    public function delete($pages)
    {
        // TODO: Implement delete() method.
    }

}
