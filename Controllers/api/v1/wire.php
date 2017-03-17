<?php
/**
 * Minds Wire Api endpoint
 *
 * @version 1
 * @author Mark Harding
 *
 */
namespace Minds\Controllers\api\v1;

use Minds\Core;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;
use Minds\Core\Wire\Methods;

class wire implements Interfaces\Api
{

    /**
     *
     */
    public function get($pages)
    {
        $response = [];

        return Factory::response($response);
    }

    /**
     * Send a wire to someone
     * @param array $pages
     *
     * API:: /v1/wire/:guid
     */
    public function post($pages)
    {
        Factory::isLoggedIn();
        $response = [];

        if (!isset($pages[0])) {
            return Factory::response(['status' => 'error', 'message' => ':guid must be passed in uri']);
        }

        $entity = Entities\Factory::build($pages[0]);

        if (!$entity) {
            return Factory::response(['status' => 'error', 'message' => 'Entity not found']);
        }

        $amount = $_POST['amount'];
        $method = $_POST['method'];

        if (!$amount) {
            return Factory::response(['status' => 'error', 'message' => 'you must send an amount']);
        }

        if ($amount <= 0) {
            return Factory::response(['status' => 'error', 'message' => 'amount must be a positive number']);
        }

        $service = Methods\Factory::build($method);

        try{
            $service->setAmount($amount)
              ->setEntity($entity)
              ->setPayload((array) $_POST['payload'])
              ->execute();

            //save the wire entity
            //trying to reduce data size, receiever can get this info from notifications
            /*$wire = new Entities\Wire();
            $wire->setEntity($this->entity)
              ->setTo($this->entity->ownerObj)
              ->setFrom(Core\Session::getLoggedInUser())
              ->setMethod('points')
              ->setTransactionId($service->getId())
              ->save();*/

            //now send notification

        } catch (\Exception $e) {
            $response['status'] = 'error';
            $respone['message'] = $e->getMessage();
            var_dump($e); exit;
        }

        return Factory::response($response);
    }

    /**
     */
    public function put($pages)
    {
    }

    /**
     */
    public function delete($pages)
    {
    }
}
