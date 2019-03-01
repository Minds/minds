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
    const INITIAL_JURY_SIZE = 1;
    const INITIAL_JURY_MAJORITY = 1;

    const APPEAL_JURY_SIZE = 12;
    const APPEAL_JURY_MAJORITY = 9;

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
     * @param long $entity_guid
     * @return Verdict
     */
    public function get($entity_guid)
    {
        return $this->repository->get($entity_guid);
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

    /**
     * Reach a verdict
     * @param Verdict $verdict
     * @return string
     */
    public function getAction(Verdict $verdict)
    {
        $requiredAction = $verdict->isAppeal() ? $verdict->getInitialJuryAction() : null;
        $upheldCount = 0;
        $uphealdCountRequired = $verdict->isAppeal() ? static::APPEAL_JURY_MAJORITY : static::INITIAL_JURY_MAJORITY;

        foreach ($verdict->getDecisions() as $decision) {
            if ($requiredAction && $requiredAction === $decision->getAction()) {
                $upheldCount++;
            }

            if (!$verdict->isAppeal() && !$requiredAction) {
                $upheldCount++;
                $requiredAction = $decision->getAction();
            }
        }

        if ($upheldCount >= $uphealdCountRequired) {
            return $requiredAction;
        }

        return 'overturned';
    }

}
