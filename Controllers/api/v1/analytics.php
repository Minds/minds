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
        if ($pages[0] == '@counter') {
            return $this->getCounter($pages);
        }

        //Factory::isLoggedIn();
        if (!Core\Session::isLoggedin()) {
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

    public function getCounter($pages)
    {
        $response = [];

        switch ($pages[1]) {
          case 'play':
            $response['data'] = $pages[2] ? Helpers\Counters::get($pages[2], 'plays') : -1;
            break;
      }

        return Factory::response($response);
    }

    public function post($pages)
    {
        Factory::isLoggedIn();

        $event = new Core\Analytics\Metrics\Event();
        if (!isset($_POST['type'])) {
            return Factory::response(['status' => 'error', 'message' => 'type not set']);
        }
        if (!isset($_POST['fields'])) {
            return Factory::response(['status' => 'error', 'message' => 'fields not set']);
        }
        if (!isset($_POST['entityGuid'])) {
            return Factory::response(['status' => 'error', 'message' => 'entityGuid not set']);
        }
        $type = $_POST['type'];
        $fields = $_POST['fields'];
        $entity_guid = (string) $_POST['entityGuid'];

        $entity = Entities\Factory::build($entity_guid);

        if (!$entity) {
            return Factory::response([
                'status' => 'error',
                'message' => 'entity with guid ' . $entity_guid . ' does not set'
            ]);
        }

        $event->setType($type)
            ->setFields($fields)
            ->setUserGuid(Core\Session::getLoggedInUser()->guid)
            ->setUserPhoneNumberHash(Core\Session::getLoggedInUser()->getPhoneNumberHash())
            ->setEntityGuid((string) $entity->getGUID())
            ->setEntityType($entity->type)
            ->setEntitySubtype($entity->subtype)
            ->setEntityOwnerGuid($entity->getOwnerEntity()->getGUID())
            ->push();

        return Factory::response([]);
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
