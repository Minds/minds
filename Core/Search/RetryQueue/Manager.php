<?php
/**
 * RetryQueueManager
 * @author edgebal
 */

namespace Minds\Core\Search\RetryQueue;

use Exception;
use Minds\Common\Urn;
use Minds\Core\Di\Di;
use Minds\Core\Events\EventsDispatcher;

class Manager
{
    /** @var EventsDispatcher */
    protected $eventsDispatcher;

    /** @var Repository */
    protected $repository;

    /**
     * RetryQueueManager constructor.
     * @param EventsDispatcher $eventsDispatcher
     * @param Repository $repository
     */
    public function __construct(
        $eventsDispatcher = null,
        $repository = null
    )
    {
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
        $this->repository = $repository ?: new Repository();
    }

    /**
     * @param mixed $entity
     * @return bool
     * @throws Exception
     */
    public function prune($entity)
    {
        $urn = (string) (new Urn($entity->guid));

        $retryQueueEntry = new RetryQueueEntry();
        $retryQueueEntry
            ->setEntityUrn($urn);

        return (bool) $this->repository->delete($retryQueueEntry);
    }

    /**
     * @param mixed $entity
     * @return bool
     * @throws Exception
     */
    public function retry($entity)
    {
        $urn = (string) (new Urn($entity->guid));

        $retryQueueEntry = $this->repository->get($urn);
        $retries = $retryQueueEntry->getRetries() + 1;

        $retryQueueEntry
            ->setLastRetry(time())
            ->setRetries($retries);

        $retrySaved = $this->repository->add($retryQueueEntry);

        if (!$retrySaved) {
            error_log("[RetryQueueManager] Critical: Cannot save retry to queue table: {$urn}");
        } elseif ($retries < 5) {
            error_log("[RetryQueueManager] Warn: Re-queue: {$urn}");

            $this->eventsDispatcher->trigger('search:index', 'all', [
                'entity' => $entity
            ]);
        } else {
            error_log("[RetryQueueManager] Critical: Too many retries indexing: {$urn}");
        }

        return $retrySaved;
    }
}
