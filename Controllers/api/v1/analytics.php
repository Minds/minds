<?php
/**
 * Minds Analytics Api endpoint
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1;

use Swagger\Annotations as SWG;
use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class analytics implements Interfaces\Api, Interfaces\ApiIgnorePam
{

    public function get($pages)
    {
        //Factory::isLoggedIn();
        if(!Core\Session::isLoggedin()){
            return Factory::response(['status'=>'error']);
        }

        $span = isset($_GET['span']) ? $_GET['span'] : 5;
        $unit = isset($_GET['unit']) ? $_GET['unit'] : 'day';

        $data = Core\Analytics\User::_()
        ->setMetric('impression')
        ->setKey($pages[0])
        ->get($span, $unit);

        $response = [
          'data' => $data
        ];

        return Factory::response($response);
    }

    public function post($pages)
    {
    }

    /**
     * Sets an analytic
     * @param array $pages
     * @SWG\PUT(
     *     tags={"analytics"},
     *     summary="Send an analytic metric",
     *     path="/v1/analytics",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Response(name="200", description="An example resource", @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Pet")
     *         )),
     *  security={
     *         {
     *             "minds_oauth2": {}
     *         }
     *     }
     * )
     */
    public function put($pages)
    {
        switch ($pages[0]) {
            case 'open':
              Helpers\Analytics::increment("app-opens"); //@todo move this to a metric factory soon
              break;
            case 'play':
              Helpers\Counters::increment($pages[1], 'plays');
              break;
        }

        return Factory::response(array());
    }

    public function delete($pages)
    {
    }
}
