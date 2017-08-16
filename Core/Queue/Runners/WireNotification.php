<?php
/**
 * @author Marcelo
 */

namespace Minds\Core\Queue\Runners;

use Minds\Core\Events\Dispatcher;
use Minds\Core\Queue;
use Minds\Core\Queue\Interfaces;

class WireNotification implements Interfaces\QueueRunner
{
    public function run()
    {
        $client = Queue\Client::Build();
        $client->setQueue("WireNotification")
            ->receive(function ($data) {
                echo "Received a wire notification request \n";

                $data = $data->getData();
                $entity = unserialize($data['entity']);
                $receiverUser = $entity->type === 'user' ? $entity : $entity->getOwnerEntity();

                if (isset($data['notMonetizedException']) && $data['notMonetizedException']) {
                    $message = 'Somebody wanted to send you a money wire, but you need to setup your merchant account first! You can monetize your account in your Wallet.';
                    Dispatcher::trigger('notification', 'wire', [
                        'to' => [$receiverUser->getGUID()],
                        'from' => 100000000000000519,
                        'notification_view' => 'custom_message',
                        'params' => ['message' => $message],
                        'message' => $message,
                    ]);
                } else {
                    $amount = $this->getAmountString($data['amount'], $data['currency']);
                    $senderUser = unserialize($data['sender']);

                    //send notification to receiver
                    Dispatcher::trigger('notification', 'wire', [
                        'to' => [$receiverUser->getGUID()],
                        'from' => $senderUser,
                        'notification_view' => 'wire_happened',
                        'params' => [
                            'amount' => $amount,
                            'from_guid' => $senderUser->getGUID(),
                            'from_username' => $senderUser->username,
                            'to_guid' => $receiverUser->getGUID(),
                            'to_username' => $receiverUser->username,
                            'subscribed' => $data['subscribed']
                        ]
                    ]);

                    //send notification to sender
                    /*Dispatcher::trigger('notification', 'wire', [
                        'to' => [$senderUser->getGUID()],
                        'from' => $receiverUser,
                        'notification_view' => 'wire_happened',
                        'params' => [
                            'amount' => $amount,
                            'from_guid' => $receiverUser->getGUID(),
                            'from_username' => $receiverUser->username,
                            'to_guid' => $senderUser->getGUID(),
                            'to_username' => $senderUser->username,
                            'subscribed' => $data['subscribed']
                        ]
                        ]);*/
                }

                echo "Succesfully dispatched wire notifications\n\n";
            });
    }

    private function getAmountString($amount, $method)
    {
        $amountString = null;
        if ($method == 'money') {
            $amountString = '$' . $amount;
        } else if ($method == 'points') {
            $currency = $amount > 1 ? ' points' : ' point';
            $amountString = $amount . $currency;
        } else {
            $currency = $amount > 1 ? ' bitcoins' : ' bitcoin';
            $amountString = $amount . $currency;
        }

        return $amountString;
    }

}
