<?php
/**
 * Verdict manager
 */
namespace Minds\Core\Reports\Verdict;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;

class Manager
{

    /** @var Repository $repository */
    private $repository;

    /** @var Delegates\ActionDelegate $actionDelegate */
    private $actionDelegate;

    /** @var Delegates\NotificationDelegate $notificationDelegate */
    private $notificationDelegate;

    public function __construct(
        $repository = null,
        $actionDelegate = null,
        $notificationDelegate = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->actionDelegate = $actionDelegate ?: new Delegates\ActionDelegate;
        $this->notificationDelegate = $notificationDelegate ?: Delegates\NotificationDelegate;
    }

    /**
     * @param array $opts
     * @return Response
     */
    public function getList($opts = [])
    {
        $opts = array_merge([
            'hydrate' => false,
        ], $opts);

        return $this->repository->getList($opts);
    }

    /**
     * Cast a verdict
     * @param Verdict $verdict
     * @return boolean
     */
    public function cast(Verdict $verdict)
    {
        $added = $this->repository->add($verdict);
        
        // Make the action
        $this->actionDelegate->onAction($verdict);

        // Send a notification to the reported user
        $this->notificationDelegate->onAction($verdict);
    }

}
