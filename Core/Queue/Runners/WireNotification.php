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

                if (isset($data['walletNotSetupException']) && $data['walletNotSetupException']) {
                    $message = 'Somebody wanted to send you a Tokens wire, but you need to setup your wallet address first! You can set it up in your Wallet.';
                    Dispatcher::trigger('notification', 'wire', [
                        'to' => [$receiverUser->getGUID()],
                        'from' => 100000000000000519,
                        'notification_view' => 'custom_message',
                        'params' => ['message' => $message],
                        'message' => $message,
                    ]);
                } else {
                    $amount = $this->getAmountString($data['amount']);
                    $senderUser = unserialize($data['sender']);

                    //send notification to receiver
                    Dispatcher::trigger('notification', 'wire', [
                        'to' => [$receiverUser->guid],
                        'from' => $senderUser->guid,
                        'notification_view' => 'wire_happened',
                        'params' => [
                            'amount' => $amount,
                            'from_guid' => $senderUser->guid,
                            'from_username' => $senderUser->username,
                            'to_guid' => $receiverUser->guid,
                            'to_username' => $receiverUser->username,
                            'subscribed' => $data['subscribed']
                        ]
                    ]);

                    //send notification to sender
                    Dispatcher::trigger('notification', 'wire', [
                        'to' => [ $senderUser->guid ],
                        'from' => $receiverUser->guid,
                        'notification_view' => 'wire_happened',
                        'params' => [
                            'amount' => $amount,
                            'from_guid' => $senderUser->guid,
                            'from_username' => $senderUser->username,
                            'to_guid' => $receiverUser->guid,
                            'to_username' => $receiverUser->username,
                            'subscribed' => $data['subscribed']
                        ]
                    ]);
                }

                echo "Succesfully dispatched wire notifications\n\n";
            });
    }

    private function getAmountString($amount)
    {
        $currency = $amount > 1 ? ' tokens' : ' token';
        return $amount . $currency;
    }

}
