<?php
/**
 * Notification Manager
 */

namespace Minds\Core\Notification;

use Minds\Common\Repository\Response;
use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Entities\User;

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
        $this->legacyRepository = $legacyRepository ?: new LegacyRepository;
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
     * @param $uuid
     * @return Notification
     */
    public function getSingle($uuid)
    {
        return $this->repository->get($uuid);
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
            $uuid = $this->repository->add($notification);

            return $uuid;
        } catch (\Exception $e) {
            error_log($e);
        }
    }

}
