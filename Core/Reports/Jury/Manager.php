<?php
/**
 * Jury manager
 */
namespace Minds\Core\Reports\Jury;

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

    public function __construct($repository = null)
    {
        $this->repository = $repository ?: new Repository;
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
     * Cast a decision
     * @param Decision $decision
     * @return boolean
     */
    public function cast(Decision $decision)
    {
        return $this->repository->add($report);
    }

}
