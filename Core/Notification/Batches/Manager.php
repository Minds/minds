<?php
/**
 * NotificationBatches Manager
 */
namespace Minds\Core\Notification\Batches;

use Minds\Core\Di\Di;
use Minds\Common\Repository\Response;
use Minds\Core\Guid;
use Minds\Entities\Factory;
use Minds\Entities\User;

class Manager
{

    /** @var Config $config */
    private $config;

    /** @var Repository $repository */
    private $repository;

    /** @var User $user */
    private $user;

    /** @var string $batchId */
    private $batchId;

    public function __construct($config = null, $repository = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->repository = $repository ?: new Repository;
    }

    /**
     * Set the user to return notifications for
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        if (is_numeric($user)) {
            $this->user = new User();
            $this->user->set('guid', $user);
            return $this;
        }
        $this->user = $user;
        return $this;
    }

    /**
     * Set the batchId
     * @param string $batchId
     * @return $this
     */
    public function setBatchId($batchId)
    {
        $this->batchId = $batchId;
        return $this;
    }

    /**
     * Return if batch is subscribed
     * @return bool
     */
    public function isSubscribed()
    {
        try {
            $subscription = $this->buildBatchSubscription();
            return (bool) $this->repository->get($subscription);
        } catch (\Exception $e) { 
            return false;
        }
    }
    

    /**
     * Subscribe to batch
     * @return bool
     */
    public function subscribe()
    {
        try {
            $subscription = $this->buildBatchSubscription();
            return $this->repository->add($subscription);
        } catch (\Exception $e) { 
            return false;
        }
    }

    /**
     * UnSubscribe to batch
     * @return bool
     */
    public function unSubscribe()
    {
        try {
            $subscription = $this->buildBatchSubscription();
            return $this->repository->delete($subscription);
        } catch (\Exception $e) { 
            return false;
        }
    }

    /**
     * Build the batch subscription object
     * @return BatchSubscription
     */
    protected function buildBatchSubscription()
    {
        return (new BatchSubscription)
            ->setUserGuid($this->user->getGuid())
            ->setBatchId($this->batchId);
    }

}
