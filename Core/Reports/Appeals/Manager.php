<?php
/**
 * Appeals manager
 */
namespace Minds\Core\Reports\Appeals;

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

    /** @var NotificationDelegate $notificationDelegate */
    private $notificationDelegate;

    /** @var EntitiesBuilder $entitesBuilder */
    private $entitesBuilder;

    public function __construct(
        $repository = null,
        $entitesBuilder = null,
        $notificationDelegate = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->entitiesBuilder = $entitesBuilder ?: Di::_()->get('EntitiesBuilder');
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
            'showAppealed' => false,
        ], $opts);

        $response = $this->repository->getList($opts);

        if ($opts['hydrate']) {
            foreach ($response as $appeal) {
                $report = $appeal->getReport();
                $entity = $this->entitiesBuilder->single($report->getEntityGuid());
                $report->setEntity($entity);
                $appeal->setReport($report);
            }
        }

        return $response;
    }

    /**
     * Appeal
     * @param Appeal $appeal
     * @return boolean
     */
    public function appeal(Appeal $appeal)
    {
        $added = $this->repository->add($appeal);

        $this->notificationDelegate->onAction($appeal);

        return $added;
    }

}
