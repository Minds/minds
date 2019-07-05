<?php

namespace Minds\Core\Queue\Runners;

use Minds\Core\Channels\Ban;
use Minds\Core\Di\Di;
use Minds\Core\Queue\Interfaces;
use Minds\Core\Queue;
use Minds\Core\Channels\Manager;
use Minds\Entities\User;

class ChannelDeferredOps implements Interfaces\QueueRunner
{
    public function run()
    {
        /** @var Interfaces\QueueClient $client */
        $client = Queue\Client::build();

        $client
            ->setQueue('ChannelDeferredOps')
            ->receive(function (Queue\Message $data) {
                /** @var Manager $channelsManager */
                $channelsManager = Di::_()->get('Channels\Manager');

                /** @var Ban $channelsBanManager */
                $channelsBanManager = Di::_()->get('Channels\Ban');

                $data = $data->getData();
                $type = $data['type'] ?? null;

                switch ($type) {
                    case 'delete':
                        echo "Got channel deletion cleanup request\n";

                        $userGuid = $data['user_guid'] ?? null;

                        if (!$userGuid) {
                            echo "ERROR! Invalid User GUID";
                        }

                        echo "User GUID: {$userGuid}\n";

                        try {
                            $done = $channelsManager
                                ->setUser(new User($userGuid, false))
                                ->deleteCleanup();
                        } catch (\Exception $e) {
                            echo (string) $e;
                            $done = false;
                        }

                        if ($done) {
                            echo "SUCCESS!\n\n";
                        } else {
                            echo "ERROR...\n\n";
                        }

                        break;

                    case 'ban':
                        echo "Got channel ban cleanup request\n";

                        $userGuid = $data['user_guid'] ?? null;

                        if (!$userGuid) {
                            echo "ERROR! Invalid User GUID";
                        }

                        echo "User GUID: {$userGuid}\n";

                        try {
                            $done = $channelsBanManager
                                ->setUser(new User($userGuid, false))
                                ->banCleanup();
                        } catch (\Exception $e) {
                            echo (string) $e;
                            $done = false;
                        }

                        if ($done) {
                            echo "SUCCESS!\n\n";
                        } else {
                            echo "ERROR...\n\n";
                        }

                        break;

                    case 'unban':
                        echo "Got channel unban restore request\n";

                        $userGuid = $data['user_guid'] ?? null;

                        if (!$userGuid) {
                            echo "ERROR! Invalid User GUID";
                        }

                        echo "User GUID: {$userGuid}\n";

                        try {
                            $done = $channelsBanManager
                                ->setUser(new User($userGuid, false))
                                ->unbanRestore();
                        } catch (\Exception $e) {
                            echo (string) $e;
                            $done = false;
                        }

                        if ($done) {
                            echo "SUCCESS!\n\n";
                        } else {
                            echo "ERROR...\n\n";
                        }

                        break;

                    default:
                        echo "ERROR! Invalid type {$type} passed\n\n";
                }
            });
    }
}
