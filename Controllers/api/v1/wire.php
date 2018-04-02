<?php
/**
 * Minds Wire Api endpoint
 *
 * @version 1
 * @author Mark Harding
 *
 */

namespace Minds\Controllers\api\v1;

use Minds\Api\Factory;
use Minds\Core;
use Minds\Core\Util\BigNumber;
use Minds\Core\Wire\Exceptions\WalletNotSetupException;
use Minds\Entities;
use Minds\Interfaces;

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

        $user = $entity->type == 'user' ? $entity : $entity->getOwnerEntity();
        if (Core\Session::getLoggedInUserGuid() === $user->guid) {
            return Factory::response(['status' => 'error', 'message' => 'You cannot send a wire to yourself!']);
        }

        $amount = BigNumber::_($_POST['amount']);

        $recurring = isset($_POST['recurring']) ? $_POST['recurring'] : false;

        if (!$amount) {
            return Factory::response(['status' => 'error', 'message' => 'you must send an amount']);
        }

        if ($amount->lt(0)) {
            return Factory::response(['status' => 'error', 'message' => 'amount must be a positive number']);
        }

        $manager = Core\Di\Di::_()->get('Wire\Manager');

        try {
            $manager
                ->setAmount((string) BigNumber::toPlain($amount, 18))
                ->setRecurring($recurring)
                ->setSender(Core\Session::getLoggedInUser())
                ->setEntity($entity)
                ->setPayload((array) $_POST['payload']);
            $result = $manager->create();

            if (!$result) {
                throw new \Exception("Something failed");
            }
        } catch (WalletNotSetupException $e) {
            Core\Queue\Client::build()->setQueue("WireNotification")
                ->send(array(
                    "entity" => $entity,
                    "walletNotSetupException" => true
                ));

            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
        } catch (\Exception $e) {
            $response['status'] = 'error';
            $response['message'] = $e->getMessage();
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

    private function sendNotifications($amount, $sender, $entity, $subscribed = false)
    {
        Core\Queue\Client::build()->setQueue("WireNotification")
            ->send([
                "amount" => $amount,
                "sender" => serialize($sender),
                "entity" => serialize($entity),
                "subscribed" => $subscribed
            ]);
    }
}
