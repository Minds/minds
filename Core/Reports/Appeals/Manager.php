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
use Minds\Core\Entities\Resolver as EntitiesResolver;
use Minds\Common\Urn;

class Manager
{

    /** @var Repository $repository */
    private $repository;

    /** @var NotificationDelegate $notificationDelegate */
    private $notificationDelegate;

    /** @var EntitiesResolver $entitiesResolver */
    private $entitiesResolver;

    public function __construct(
        $repository = null,
        $entitiesResolver = null,
        $notificationDelegate = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->entitiesResolver = $entitiesResolver ?: new EntitiesResolver;
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
                $entity = $this->entitiesResolver->single(
                    (new Urn())->setUrn($report->getEntityUrn())
                );
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
        if ($appeal->getReport()->getState() !== 'initial_jury_decided') {
            throw new NotAppealableException();
        }

        $added = $this->repository->add($appeal);

        $this->notificationDelegate->onAction($appeal);

        return $added;
    }

}
