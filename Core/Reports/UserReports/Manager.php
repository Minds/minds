<?php
/**
 * Reports manager
 */
namespace Minds\Core\Reports\UserReports;

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

    /** @var ElasticRepository $elasticRepository */
    private $elasticRepository;

    /** @var Delegates\NotificationDelegate $notificationDelegate */
    private $notificationDelegate;

    public function __construct(
        $repository = null,
        $elasticRepository = null,
        $notificationDelegate = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->elasticRepository = $elasticRepository ?: new ElasticRepository;
        $this->notificationDelegate = $notificationDelegate ?: new Delegates\NotificationDelegate;
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

        return $this->elasticRepository->getList($opts);
    }

    /**
     * Add a report
     * @param Report $report
     * @return boolean
     */
    public function add(UserReport $report)
    {
        $this->repository->add($report);
        $this->elasticRepository->add($report);
        $this->notificationDelegate->onAction($report);
        return true;
    }

}
