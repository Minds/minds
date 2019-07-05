<?php
/**
 * Ban
 *
 * @author emi
 */

namespace Minds\Core\Channels;

use Minds\Core\Channels\Delegates\Artifacts;
use Minds\Core\Di\Di;
use Minds\Core\Queue\Interfaces\QueueClient;
use Minds\Entities\User;

class Ban
{
    /** @var string[] */
    const BAN_DELEGATES = [
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

    /** @var Delegates\Ban */
    protected $banDelegate;

    /** @var Delegates\Unban */
    protected $unbanDelegate;

    /** @var QueueClient */
    protected $queueClient;

    /**
     * Manager constructor.
     * @param Delegates\Artifacts\Factory $artifactsDelegatesFactory
     * @param Delegates\Logout $logoutDelegate
     * @param Delegates\Ban $banDelegate
     * @param Delegates\Unban $unbanDelegate
     * @param QueueClient $queueClient
     */
    public function __construct(
        $artifactsDelegatesFactory = null,
        $logoutDelegate = null,
        $banDelegate = null,
        $unbanDelegate = null,
        $queueClient = null
    )
    {
        $this->artifactsDelegatesFactory = $artifactsDelegatesFactory ?: new Delegates\Artifacts\Factory();
        $this->logoutDelegate = $logoutDelegate ?: new Delegates\Logout();
        $this->banDelegate = $banDelegate ?: new Delegates\Ban();
        $this->unbanDelegate = $unbanDelegate ?: new Delegates\Unban();
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
     * @param string $banReason
     * @return bool
     * @throws \Exception
     */
    public function ban($banReason = '')
    {
        if (!$this->user) {
            throw new \Exception('Missing User');
        }

        $banned = $this->banDelegate->ban($this->user, $banReason);

        if ($banned) {
            $this->logoutDelegate->logout($this->user);
            $this->queueClient
                ->setQueue('ChannelDeferredOps')
                ->send([
                    'type' => 'ban',
                    'user_guid' => $this->user->guid
                ]);
        }

        return $banned;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function banCleanup()
    {
        if (!$this->user) {
            throw new \Exception('Missing User');
        }

        if (!$this->user->isBanned()) {
            throw new \Exception('User is not banned');
        }

        $userGuid = $this->user->guid;

        // Snapshot

        $snapshotCreated = true;

        foreach (static::BAN_DELEGATES as $delegateClassName) {
            try {
                $delegate = $this->artifactsDelegatesFactory->build($delegateClassName);
                $done = $delegate->snapshot($userGuid);

                if (!$done) {
                    throw new \Exception("{$delegateClassName} ban cleanup snapshot failed for {$userGuid}");
                }
            } catch (\Exception $e) {
                // TODO: Fail?
                error_log((string) $e);
                $snapshotCreated = false;
            }
        }

        // Hide (only if all snapshots were successful)

        if (!$snapshotCreated) {
            return false;
        }

        $success = true;

        foreach (static::BAN_DELEGATES as $delegateClassName) {
            try {
                $delegate = $this->artifactsDelegatesFactory->build($delegateClassName);
                $done = $delegate->hide($userGuid);

                if (!$done) {
                    throw new \Exception("{$delegateClassName} ban cleanup hiding failed for {$userGuid}");
                }
            } catch (\Exception $e) {
                // TODO: Fail?
                error_log((string) $e);
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function unban()
    {
        if (!$this->user) {
            throw new \Exception('Missing User');
        }

        $unbanned = $this->unbanDelegate->unban($this->user);

        if ($unbanned) {
            $this->queueClient
                ->setQueue('ChannelDeferredOps')
                ->send([
                    'type' => 'unban',
                    'user_guid' => $this->user->guid
                ]);
        }

        return $unbanned;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function unbanRestore()
    {
        if (!$this->user) {
            throw new \Exception('Missing User');
        }

        if ($this->user->isBanned()) {
            throw new \Exception('User is still banned');
        }

        $userGuid = $this->user->guid;

        $success = true;

        foreach (static::BAN_DELEGATES as $delegateClassName) {
            try {
                $delegate = $this->artifactsDelegatesFactory->build($delegateClassName);
                $done = $delegate->restore($userGuid);

                if (!$done) {
                    throw new \Exception("{$delegateClassName} un-ban restore failed for {$userGuid}");
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
