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
use Minds\Core\Security\ACL;

class Manager
{

    /** @var Repository $repository */
    private $repository;

    /** @var EntitiesResolver $entitiesResolver */
    private $entitiesResolver;

    /** @var Delegates\NotificationDelegate $notificationDelegate */
    private $notificationDelegate;

    /** @var Delegates\SummonDelegate $summonDelegate */
    private $summonDelegate;

    /** @var ACL $acl */
    private $acl;

    public function __construct(
        $repository = null,
        $entitiesResolver = null,
        $notificationDelegate = null,
        $summonDelegate = null,
        $acl = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->entitiesResolver = $entitiesResolver ?: new EntitiesResolver;
        $this->notificationDelegate = $notificationDelegate ?: new Delegates\NotificationDelegate;
        $this->summonDelegate = $summonDelegate ?: new Delegates\SummonDelegate();
        $this->acl = $acl ?: new ACL();
    }

    /**
     * @param array $opts
     * @return Response
     * @throws \Exception
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
                $ignore = $this->acl->setIgnore(true);
                $entity = $this->entitiesResolver->single(
                    (new Urn())->setUrn($report->getEntityUrn())
                );
                $this->acl->setIgnore($ignore); // Restore ACL
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

        $this->summonDelegate->onAppeal($appeal);
        $this->notificationDelegate->onAction($appeal);

        return $added;
    }

}
