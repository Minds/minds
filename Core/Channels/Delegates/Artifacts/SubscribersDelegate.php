<?php
/**
 * SubscribersDelegate.
 *
 * @author emi
 */

namespace Minds\Core\Channels\Delegates\Artifacts;

use Exception;
use Minds\Core\Channels\Snapshots\Repository;
use Minds\Core\Channels\Snapshots\Snapshot;
use Minds\Core\Data\Cassandra\Prepared\Custom;
use Minds\Core\Data\Cassandra\Scroll as CassandraScroll;
use Minds\Core\Di\Di;
use Minds\Core\Subscriptions\Manager as SubscriptionsManager;
use Minds\Entities\User;

class SubscribersDelegate implements ArtifactsDelegateInterface
{
    /** @var Repository */
    protected $repository;

    /** @var CassandraScroll */
    protected $scroll;

    /** @var SubscriptionsManager */
    protected $subscriptionsManager;

    /**
     * SubscriptionsDelegate constructor.
     * @param Repository $repository
     * @param CassandraScroll $scroll
     * @param SubscriptionsManager $subscriptionsManager
     */
    public function __construct(
        $repository = null,
        $scroll = null,
        $subscriptionsManager = null
    )
    {
        $this->repository = $repository ?: new Repository();
        $this->scroll = $scroll ?: Di::_()->get('Database\Cassandra\Cql\Scroll');
        $this->subscriptionsManager = $subscriptionsManager ?: new SubscriptionsManager();
    }

    /**
     * @param string|int $userGuid
     * @return bool
     */
    public function snapshot($userGuid)
    {
        $cql = "SELECT * FROM friendsof WHERE key = ?";
        $values = [
            (string) $userGuid,
        ];

        $prepared = new Custom();
        $prepared->query($cql, $values);

        try {
            $rows = $this->scroll->request($prepared);

            foreach ($rows as $row) {
                $snapshot = new Snapshot();
                $snapshot
                    ->setUserGuid($userGuid)
                    ->setType('friendsof')
                    ->setKey([$row['key'], $row['column1']])
                    ->setJsonData($row);

                $this->repository->add($snapshot);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param string|int $userGuid
     * @return bool
     * @throws Exception
     */
    public function restore($userGuid)
    {
        $publisher = new User();
        $publisher->set('guid', $userGuid);

        /** @var Snapshot $snapshot */
        foreach ($this->repository->getList([
            'user_guid' => $userGuid,
            'type' => 'friendsof',
        ]) as $snapshot) {
            $row = $snapshot->getJsonData();

            $subscriber = new User();
            $subscriber->set('guid', $row['column1']);

            $this->subscriptionsManager
                ->setSubscriber($subscriber)
                ->setSendEvents(false)
                ->subscribe($publisher);
        }

        return true;
    }

    /**
     * @param string|int $userGuid
     * @return bool
     * @throws Exception
     */
    public function hide($userGuid)
    {
        return $this->delete($userGuid);
    }

    /**
     * @param string|int $userGuid
     * @return bool
     * @throws Exception
     */
    public function delete($userGuid)
    {
        $publisher = new User();
        $publisher->set('guid', $userGuid);

        try {
            $cql = "SELECT * FROM friendsof WHERE key = ?";
            $values = [
                (string) $userGuid,
            ];

            $prepared = new Custom();
            $prepared->query($cql, $values);

            $rows = $this->scroll->request($prepared);

            foreach ($rows as $row) {
                $subscriber = new User();
                $subscriber->set('guid', $row['column1']);

                $this->subscriptionsManager
                    ->setSubscriber($subscriber)
                    ->setSendEvents(false)
                    ->unSubscribe($publisher);
            }

            return true;
        } catch (Exception $e) {
            error_log((string) $e);
            return false;
        }
    }
}
