<?php

namespace Minds\Core\Email\Batches;

use Minds\Core\Data\Call;
use Minds\Core\Di\Di;
use Minds\Core\Email\EmailSubscription;
use Minds\Core\Email\Repository;
use Minds\Entities\User;

class Subscribe implements EmailBatchInterface
{
    protected $offset;

    /**
     * @param mixed $offset
     * @return Subscribe
     */
    public function setOffset($offset)
    {
        $this->offset = isset($offset) ?: '';
        return $this;
    }

    public function run()
    {
        $indexes = new Call('entities_by_time');
        /** @var Repository $repository */
        $repository = Di::_()->get('Email\Repository');

        $sFails = 0;

        $config = Di::_()->get('Config');
        $subscriptions = $config->get('default_email_subscriptions');

        while (true) {
            $guids = $indexes->getRow('user', array('limit' => 750, 'offset' => $this->offset, 'reversed' => true));
            if (count($guids) <= 1) {
                break;
            }

            foreach ($guids as $guid => $ts) {

                if ($sFails > 5) {
                    $this->out("Too many failures [pausing for 5 seconds]");
                    sleep(5);
                }

                try {

                    $user = new User($guid);

                    if ($user && !$user->getDeleted() && !$user->getSpam()) {
                        foreach ($subscriptions as $subscription) {
                            $sub = array_merge($subscription, ['user_guid' => $user->guid]);
                            $repository->add(new EmailSubscription($sub));
                        }
                        echo "\r{$user->guid}…";

                        echo " [done]";
                        $sFails = 0;
                    }
                } catch (\Exception $e) {
                    echo " [failed]";
                    $sFails++;
                }

            }

            end($guids);
            $this->offset = key($guids);
        }

    }
}