<?php
/**
 * Delete Artifacts (posts, comments etc) Delegate
 */
namespace Minds\Core\Channels\Delegates;

use Minds\Core\Data\Call;
use Minds\Core\Queue\Client as QueueClient;

class DeleteArtifacts
{
    /** @var QueueClient $queueClient */
    private $queueClient;

    /** @var Call $indexes */
    private $indexes;

    /** @var Call $entities */
    private $entities;

    /** @var Call $subscriptions */
    private $subscriptions;

    /** @var Call $subscribers */
    private $subscribers;

    public function __construct(
        $queueClient = null,
        $indexes = null,
        $entities = null,
        $subscriptions = null,
        $subscribers = null
    )
    {
        $this->queueClient = $queueClient ?: QueueClient::build();
        $this->indexes = $indexes ?: new Call('entities_by_time');
        $this->entities = $entities ?: new Call('entities');
        $this->subscriptions = $subscriptions ?: new Call('friends');
        $this->subscribers = $subscribers ?: new Call('friendsof');
    }

    /**
     * Send to background task
     * @param User $user
     */
    public function queue($user)
    {
        $this->queueClient
            ->setQueue('ChannelDeleteArtifactsCleanup')
            ->send([
                'user_guid' => (string) $user->getGuid()
            ]);
    }

    /**
     * Execute delete (from queue runner)
     * @param int $user_guid
     * @return void
     */
    public function delete($user_guid)
    {
        $this->deletePosts($user_guid);
        $this->deleteSubscriptions($user_guid);
        $this->deleteSubscribers($user_guid);
    }

    /**
     * Delete posts
     * @param int $user_guid
     * @return void
     */
    private function deletePosts($user_guid)
    {
        $keys = [
            "activity:user:$user_guid",
            "object:blog:user:$user_guid",
            "object:image:user:$user_guid",
            "object:video:user:$user_guid",
        ];

        foreach ($keys as $key) {
            $offset = "";

            while (true) {
                $rows = $this->indexes->getRow($key, [
                    'limit' => 500,
                    'offset' => $offset,
                ]);

                if (!$rows) {
                    break;
                }

                foreach ($rows as $guid => $ts) {
                    $this->entities->removeRow($guid);
                    $this->indexes->removeAttributes($key, [ $guid ]);
                    $offset = $guid;
                }
            }
        }
    }

    /**
     * Delete subscriptions
     * @param int $user_guid
     * @return void
     */
    private function deleteSubscriptions($user_guid)
    {
        $offset = "";
        while (true) {
            $rows = $this->subscriptions->getRow($user_guid, [
                'limit' => 500,
                'offset' => $offset,
            ]);

            if (!$rows) {
                break;
            }

            foreach ($rows as $guid => $ts) {
                $this->subscribers->removeAttributes($guid, [ $user_guid ]);
                $this->subscriptions->removeAttributes($user_guid, [ $guid ]);
                $offset = $guid;
            }
        }
        $this->subscriptions->removeRow($user_guid);
    }

    /**
     * Delete subscribers
     * @param int $user_guid
     * @return void
     */
    private function deleteSubscribers($user_guid)
    {
        $offset = "";
        while (true) {
            $rows = $this->subscribers->getRow($user_guid, [
                'limit' => 500,
                'offset' => $offset,
            ]);

            if (!$rows) {
                break;
            }

            foreach ($rows as $guid => $ts) {
                $this->subscriptions->removeAttributes($guid, [ $user_guid ]);
                $this->subscribers->removeAttributes($user_guid, [ $guid ]);
                $offset = $guid;
            }
        }
        $this->subscribers->removeRow($user_guid);
    }

    /**
     * Delete comment
     * @param int $user_guid
     */
    private function deleteComments($user_guid)
    {
        //COMING SOON
    }

}
