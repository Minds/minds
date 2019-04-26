<?php

namespace Minds\Core\Email\Batches;

use Minds\Core\Analytics\Timestamps;
use Minds\Core\Di\Di;
use Minds\Core\Email\Campaigns\WhenNotifications;
use Minds\Core\Email\EmailSubscribersIterator;
use Minds\Core\Notification\Counters;
use Minds\Core\Notification\Repository;
use Minds\Traits\MagicAttributes;

class Notifications implements EmailBatchInterface
{
    use MagicAttributes;

    /** @var Repository */
    protected $repository;

    /** @var Counters $counters */
    protected $counters;

    protected $dryRun;

    protected $from;

    protected $offset = '';

    public function __construct(
        $notificationRepository = null,
        $counters = null
    ) {
        $this->repository = $notificationRepository ?: Di::_()->get('Notification\Repository');
        $this->counters = $counters ?: new Counters();
        $this->from = Timestamps::get(['day'])['day'];
    }

    public function setOffset($offset)
    {
        $this->offset = isset($offset) ?: '';

        return $this;
    }

    public function run()
    {
        $iterator = new EmailSubscribersIterator();
        $iterator->setCampaign('when')
            ->setTopic('unread_notifications')
            ->setValue(true)
            ->setOffset($this->offset);

        foreach ($iterator as $user) {
            $this->counters->setUser($user->guid);
            $count = $this->counters->getCount();

            if ($count >= 0) {
                // get latest notifications
                try {
                    $notifications = $this->repository->getList(['limit' => $count, 'to_guid' => $user->guid])->toArray();
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                    continue;
                }

                $i = 0;

                //count all notifications created today
                while ($i < count($notifications) && $this->from <= $notifications[$i]->getCreatedTimestamp()) {
                    ++$i;
                }

                if ($i >= 1) {
                    error_log("Sending email to {$user->guid} for {$i} notifications");
                    $campaign = new WhenNotifications();
                    $campaign->setUser($user)
                        ->setAmount($i)
                        ->send();
                }
            }
        }
    }
}
