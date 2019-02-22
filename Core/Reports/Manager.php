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

    public function __construct()
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
        
    }

    /**
     * Add a report
     * @param Report $report
     * @return boolean
     */
    public function add(Report $report)
    {

    }

}
