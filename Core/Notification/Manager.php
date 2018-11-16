<?php
/**
 * Notification Manager
 */
namespace Minds\Core\Notification;

use Minds\Core\Di\Di;
use Minds\Common\Repository\Response;
use Minds\Core\Guid;
use Minds\Entities\Factory;

class Manager
{

    /** @var Config $config */
    private $config;

    /** @var Repository $repository */
    private $repository;

    /** @var LegacyRepository $legacyRepository */
    private $legacyRepository;

    /** @var User $user */
    private $user;

    public function __construct($config = null, $repository = null, $legacyRepository = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->repository = $repository ?: new Repository;
        $this->legacyRepository = $legacyRepository ?:new LegacyRepository;
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

        if ($this->config->get('use_sql_notifications')) {
            return $this->repository->getList($opts);
        }

        $this->legacyRepository->setOwner($this->user);        
        $legacy = $this->legacyRepository->getAll($opts['type'], $opts);
        $response = new Response();
        $response->setPagingToken($legacy['token']);

        foreach ($legacy['notifications'] as $row) {
            $response[] = $row;
        }
        
        return $response;
    }

    /**
     * Add notification to datastores
     * @param Notification $notification
     * @return void
     */
    public function add($notification)
    {
        try {
            $this->repository->add($notification);
        } catch (\Exception $e) { }

        $entity = Factory::build($notification->getEntityGuid());
        $to = Factory::build($notification->getToGuid());
        $from = Factory::build($notification->getFromGuid());
        $filter = 'other';
        switch ($notification->getType()) {
            case 'friends':
            case 'missed_call':
            case 'welcome_chat':
            case 'welcome_discover':
                $filter = 'subscriptions';
                break;
            case 'group_invite':
            case 'group_kick':
            case 'group_activity':
                $filter = 'groups';
                break;
                break;
            case 'comment':
                $filter = 'comments';
                break;
            case 'like':
            case 'downvote':
                $filter = 'votes';
                break;
            case 'remind':
                $filter = 'reminds';
                break;
            case 'tag':
                $filter = 'tags';
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
                $filter = 'boosts';
        }
        
        $data = [
            'guid' => Guid::build(),
            'filter' => $filter,
            'type' => $notification->getType(),
            'notification_view' => $notification->getType(),
            'description' => $this->description,
            'params' => $notification->getData(),
            'time_created' => time(),
            'entity' => $entity ? $entity->export() : null,
            'from' => $from ? $from->export() : null,
        ];
        // Remove soon!
        $this->legacyRepository->setOwner($to);
        $this->legacyRepository->store($data);
    }

}
