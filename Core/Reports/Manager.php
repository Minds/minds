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

class Manager
{

    /** @var Repository $repository */
    private $repository;

    /** @var PreFeb2019Repository $preFeb2019Repository */
    private $preFeb2019Repository;    

    public function __construct($repository = null, $preFeb2019Repository = null)
    {
        $this->repository = $repository ?: new Repository;
        $this->preFeb2019Repository = $preFeb2019Repository ?: new PreFeb2019Repository();
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

    public function getReport($entity_guid)
    {
        return $this->repository->get($entity_guid);
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
            ->setTimestamp(round(microtime(true) * 1000));

        $reports = $this->getList([
            'entity_urn' => $report->getEntityUrn(),
            'reason_code' => $report->getReasonCode(),
            'sub_reason_code' => $report->getSubReasonCode(),
        ]);

        if (!$reports || !count($reports)) {
            return $report;
        }

        return $reports[0];
    }

}
