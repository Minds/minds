<?php
/**
 * Reports manager
 */
namespace Minds\Core\Reports;

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

    /** @var PreFeb2019Repository $preFeb2019Repository */
    private $preFeb2019Repository;    

    /** @var EntitiesResolver $entitiesResolver */
    private $entitiesResolver;

    /** @var ACL $acl */
    private $acl;

    public function __construct(
        $repository = null,
        $preFeb2019Repository = null, 
        $entitiesResolver = null,
        $acl = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->preFeb2019Repository = $preFeb2019Repository ?: new PreFeb2019Repository();
        $this->entitiesResolver = $entitiesResolver ?: new EntitiesResolver;
        $this->acl = $acl ?: new ACL;
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

        $response = $this->repository->getList($opts);

        if ($opts['hydrate']) {
            foreach ($response as $report) {
                $ignore = $this->acl->setIgnore(true);
                $entity = $this->entitiesResolver->single(
                    (new Urn())->setUrn($report->getEntityUrn())
                );
                $this->acl->setIgnore($ignore);
                $report->setEntity($entity);
            }
        }

        return $response;
    }

    /**
     * Return a single report
     * @param string $urn
     * @return Report
     */
    public function getReport($urn)
    {
        $report = $this->repository->get($urn);
        $ignore = $this->acl->setIgnore(true);
        $entity = $this->entitiesResolver->single(
            (new Urn())->setUrn($report->getEntityUrn())
        );
        $this->acl->setIgnore($ignore);
        $report->setEntity($entity);
        return $report;
    }

    /**
     * Add a report
     * @param Report $report
     * @return boolean
     */
    public function add(Report $report)
    {
        return $this->repository->add($report);
    }

    /**
     * Indempotent function to return the latest report found 
     * or supplied
     * @param Report $report
     * @return Report
     */
    public function getLatestReport($report)
    {
        $report->setState('reported')
            ->setTimestamp(time());

        $reports = $this->getList([
            'entity_urn' => $report->getEntityUrn(),
            'reason_code' => $report->getReasonCode(),
            'sub_reason_code' => $report->getSubReasonCode(),
            'hydrate' => true,
        ]);

        if (!$reports || !count($reports)) {
            return $report;
        }

        return $reports[0];
    }

}
