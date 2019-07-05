<?php
/**
 * Channels manager
 */

namespace Minds\Core\Channels;

use Minds\Core\Di\Di;
use Minds\Core\Queue\Interfaces\QueueClient;
use Minds\Entities\User;
use Minds\Core\Channels\Delegates\Artifacts;

class Manager
{
    /** @var string[] */
    const DELETION_DELEGATES = [
        Artifacts\EntityDelegate::class,
        Artifacts\LookupDelegate::class,
        Artifacts\UserIndexesDelegate::class,
        Artifacts\UserEntitiesDelegate::class,
        Artifacts\SubscribersDelegate::class,
        Artifacts\SubscriptionsDelegate::class,
        Artifacts\ElasticsearchDocumentsDelegate::class,
        Artifacts\CommentsDelegate::class,
    ];

    /** @var User $user */
    protected $user;

    /** @var Delegates\Artifacts\Factory */
    protected $artifactsDelegatesFactory;

    /** @var Delegates\Logout */
    protected $logoutDelegate;

    /** @var QueueClient */
    protected $queueClient;

    /**
     * Manager constructor.
     * @param Delegates\Artifacts\Factory $artifactsDelegatesFactory
     * @param Delegates\Logout $logoutDelegate
     * @param QueueClient $queueClient
     */
    public function __construct(
        $artifactsDelegatesFactory = null,
        $logoutDelegate = null,
        $queueClient = null
    )
    {
        $this->artifactsDelegatesFactory = $artifactsDelegatesFactory ?: new Delegates\Artifacts\Factory();
        $this->logoutDelegate = $logoutDelegate ?: new Delegates\Logout();
        $this->queueClient = $queueClient ?: Di::_()->get('Queue');
    }

    /**
     * Set the user to manage
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function snapshot()
    {
        if (!$this->user) {
            throw new \Exception('Missing User');
        }

        $userGuid = $this->user->guid;

        foreach (static::DELETION_DELEGATES as $delegateClassName) {
            try {
                $delegate = $this->artifactsDelegatesFactory->build($delegateClassName);
                $done = $delegate->snapshot($userGuid);

                if (!$done) {
                    throw new \Exception("{$delegateClassName} snapshot failed for {$userGuid}");
                }
            } catch (\Exception $e) {
                // TODO: Fail?
                error_log((string) $e);
                return false;
            }
        }

        return true;
    }

    /**
     * Deletes a channel
     * @return bool
     * @throws \Exception
     */
    public function delete()
    {
        if (!$this->user) {
            throw new \Exception('Missing User');
        }

        $this->logoutDelegate->logout($this->user);

        $this->queueClient
            ->setQueue('ChannelDeferredOps')
            ->send([
                'type' => 'delete',
                'user_guid' => $this->user->guid
            ]);

        return true;
    }

    public function deleteCleanup()
    {
        if (!$this->user) {
            throw new \Exception('Missing User');
        }

        $userGuid = $this->user->guid;

        foreach (static::DELETION_DELEGATES as $delegateClassName) {
            try {
                $delegate = $this->artifactsDelegatesFactory->build($delegateClassName);
                $done = $delegate->delete($userGuid);

                if (!$done) {
                    throw new \Exception("{$delegateClassName} deletion failed for {$userGuid}");
                }
            } catch (\Exception $e) {
                // TODO: Fail?
                error_log((string) $e);
                return false;
            }
        }

        $this->logoutDelegate->logout($this->user);

        return true;
    }

    /**
     * @param $userGuid
     * @return bool
     * @throws \Exception
     */
    public function restore($userGuid)
    {
        if (!$userGuid) {
            throw new \Exception('Missing User GUID');
        }

        $success = true;

        foreach (static::DELETION_DELEGATES as $delegateClassName) {
            try {
                $delegate = $this->artifactsDelegatesFactory->build($delegateClassName);
                $done = $delegate->restore($userGuid);

                if (!$done) {
                    throw new \Exception("{$delegateClassName} restore failed for {$userGuid}");
                }
            } catch (\Exception $e) {
                // TODO: Fail?
                error_log((string) $e);
                $success = false;
            }
        }

        return $success;
    }
}
