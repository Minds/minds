<?php
/**
 * Notification Manager
 */

namespace Minds\Core\Notification;

use Minds\Common\Repository\Response;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Entities\User;
use Minds\Core\Features\Manager as FeaturesManager;

class Manager
{

    /** @var Config $config */
    private $config;

    /** @var Repository $repository */
    private $repository;

    /** @var CassandraRepository $cassandraRepository */
    private $cassandraRepository;

    /** @var FeaturesManager $features */
    private $features;

    /** @var User $user */
    private $user;

    public function __construct(
        $config = null,
        $repository = null,
        $cassandraRepository = null,
        $features = null
    )
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->cassandraRepository = $cassandraRepository ?: new CassandraRepository;
        $this->features = $features ?: new FeaturesManager;

        if (!$this->features->has('cassandra-notifications')) {
            $this->repository = $repository ?: new Repository;
        }
    }

    /**
     * Set the user to return notifications for
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Return a single notification
     * @param $urn
     * @return Notification
     */
    public function getSingle($urn)
    {
        if (strpos($urn, 'urn:') === FALSE) {
            $urn = "urn:notification:" . implode('-', [
                    $this->user->getGuid(),
                    $urn
                ]);
        }
        return $this->cassandraRepository->get($urn);
    }

    /**
     * Return a list of notifications
     * @param $opts
     * @return Response
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'to_guid' => $this->user ? $this->user->getGuid() : null,
            'type' => null,
            'types' => null,
            'limit' => 12,
            'offset' => '',
        ], $opts);

        $opts['type_group'] = $opts['type'];

        switch ($opts['type']) {
            case "tags":
                $opts['types'] = [
                    'tag',
                ];
                break;
            case "subscriptions":
                $opts['types'] = [
                    'friends',
                    'welcome_chat',
                    'welcome_discover',
                ];
                break;
            case "groups":
                $opts['types'] = [
                    'group_invite',
                    'group_kick',
                    'group_activity',
                ];
                break;
            case "comments":
                $opts['types'] = [
                    'comment',
                ];
                break;
            case "votes":
                $opts['types'] = [
                    'like',
                    'downvote',
                ];
                break;
            case "reminds":
                $opts['types'] = [
                    'remind',
                ];
                break;
            case "boosts":
                $opts['types'] = [
                    'boost_gift',
                    'boost_submitted',
                    'boost_submitted_p2p',
                    'boost_request',
                    'boost_rejected',
                    'boost_revoked',
                    'boost_accepted',
                    'boost_completed',
                    'boost_peer_request',
                    'boost_peer_accepted',
                    'boost_peer_rejected',
                    'welcome_points',
                    'welcome_boost',
                ];
                break;
        }

        if ($this->features->has('cassandra-notifications')) {
            return $this->cassandraRepository->getList($opts);
        }

        return $this->repository->getList($opts);
    }

    /**
     * Add notification to datastores
     * @param Notification $notification
     * @return string|false
     */
    public function add($notification)
    {
        try {
            $this->cassandraRepository->add($notification);

            if (!$this->features->has('cassandra-notifications')) {
                $uuid = $this->repository->add($notification);
            }

            return $uuid;
        } catch (\Exception $e) {
            error_log($e);
            if (php_sapi_name() === 'cli') {
                //exit;
            }
        }
    }

    /**
     * @param $type
     * @return string
     */
    public static function getGroupFromType($type)
    {
        switch ($type) {
            case 'tag':
                return 'tags';
                break;
            case 'friends':
            case 'welcome_chat':
            case 'welcome_discover':
                return 'subscriptions';
                break;
            case 'group_invite':
            case 'group_kick':
            case 'group_activity':
                return 'groups';
                break;
            case 'comment':
                return 'comments';
                break;
            case 'like':
            case 'downvote':
                return 'votes';
                break;
            case 'remind':
                return 'reminds';
                break;
            case 'boost_gift':
            case 'boost_submitted':
            case 'boost_submitted_p2p':
            case 'boost_request':
            case 'boost_rejected':
            case 'boost_revoked':
            case 'boost_accepted':
            case 'boost_completed':
            case 'boost_peer_request':
            case 'boost_peer_accepted':
            case 'boost_peer_rejected':
            case 'welcome_points':
            case 'welcome_boost':
                return 'boosts';
                break;
        }
        return 'unknown';
    }

}
