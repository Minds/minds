<?php


namespace Minds\Core\Email\Batches;


use Minds\Core\Analytics\Timestamps;
use Minds\Core\Di\Di;
use Minds\Core\Email\Campaigns\WhenNotifications;
use Minds\Core\Email\EmailSubscribersIterator;
use Minds\Core\Notification\Counters;
use Minds\Core\Notification\Repository;
use Minds\Entities\Notification;

class Notifications implements EmailBatchInterface
{
    /** @var Repository */
    protected $repository;

    protected $offset = '';

    public function __construct($notificationRepository= null) {
        $this->repository = $notificationRepository ?: Di::_()->get('Notification\Repository');
    }

    public function setOffset($offset)
    {
        $this->offset = isset($offset) ?: '';
        return $this;
    }

    public function run()
    {
        $counters = new Counters();

        $iterator = new EmailSubscribersIterator();
        $iterator->setCampaign('when')
            ->setTopic('unread_notifications')
            ->setValue(true)
            ->setOffset($this->offset);

        foreach ($iterator as $user) {
            $counters->setUser($user->guid);
            $count = $counters->getCount();

            if ($count >= 0) {

                // get latest notifications
                try {
                    /** @var Notification[] $notifications */
                    $notifications = $this->repository->getAll(['limit' => $count])['notifications'];
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                    continue;
                }

                $today = Timestamps::get(['day'])['day'];

                $i = 0;

                //count all notifications created today
                while ($i < count($notifications) && $today <= $notifications[$i]->getTimeCreated()) {
                    $i++;
                }

                if ($i >= 1) {
                    $campaign = new WhenNotifications();

                    $campaign->setUser($user)
                        ->setAmount($i)
                        ->send();
                }
            }
        }
    }
}