<?php

namespace Minds\Controllers\api\v1\admin;

use Minds\Api\Exportable;
use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Config;

class purchases implements Interfaces\Api, Interfaces\ApiAdminPam
{
    public function get($pages)
    {
        $offset = $_GET['offset'] ? base64_decode($_GET['offset']) : '';
        /** @var Core\Blockchain\Pledges\Repository $repo */
        $repo = Di::_()->get('Blockchain\Purchase\Repository');

        $result = $repo->getList(['offset' => $offset]);

        $response['purchases'] = (new Exportable($result['purchases']))->setExportArgs(true);
        $response['load-next'] = base64_encode($result['token']);

        return Factory::response($response);
    }

    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Issue tokens
     */
    public function put($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
                'status' => 'error'
            ]);
        }

        /** @var Core\Blockchain\Pledges\Repository $repository */
        $repository = Di::_()->get('Blockchain\Purchase\Repository');

        $purchase = $repository->get($pages[0], $pages[1]);

        if (!$purchase) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Pledge not found'
            ]);
        }

        if ($purchase->getStatus() !== 'purchased') {
            return Factory::response([
                'status' => 'error',
                'message' => 'Pledge already actioned'
            ]);
        }

        /** @var Core\Blockchain\Purchase\Manager $manager */
        $manager = Di::_()->get('Blockchain\Purchase\Manager');

        try {
            $manager->issue($purchase);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return Factory::response([
            'purchase' => $purchase->export(true)
        ]);
    }

    public function delete($pages)
    {
        if (!isset($pages[0]) || !$pages[0]) {
            return Factory::response([
                'status' => 'error'
            ]);
        }

        /** @var Core\Blockchain\Pledges\Repository $repository */
        $repository = Di::_()->get('Blockchain\Pledges\Repository');

        $pledge = $repository->get($pages[0]);

        if (!$pledge) {
            return Factory::response([
                'status' => 'error',
                'message' => 'Pledge not found'
            ]);
        }

        if ($pledge->getStatus() !== 'review') {
            return Factory::response([
                'status' => 'error',
                'message' => 'Pledge already actioned'
            ]);
        }

        /** @var Core\Blockchain\Pledges\Manager $manager */
        $manager = Di::_()->get('Blockchain\Pledges\Manager');

        try {
            $manager->reject($pledge);
        } catch (\Exception $e) {
            return Factory::response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return Factory::response([
            'pledge' => $pledge->export(true)
        ]);
    }

}
