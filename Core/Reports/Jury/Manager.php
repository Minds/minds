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
use Minds\Common\Repository\Response;

class Manager
{

    /** @var Repository $repository */
    private $repository;

    /** @var ReportsRepository $reportsRepository */
    private $reportsRepository;

    /** @var EntitiesBuilder $entitiesBuilder */
    private $entitiesBuilder;

    /** @var string $juryType */
    private $juryType;

    /** @var User $user */
    private $user;

    public function __construct(
        $repository = null,
        $reportsRepository = null,
        $entitiesBuilder = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->reportsRepository = $reportsRepository ?: new ReportsRepository;
        $this->entitiesBuilder = $entitiesBuilder  ?: Di::_()->get('EntitiesBuilder');
    }

    /**
     * Set the jury type
     * @param string $juryType
     * @return $this
     */
    public function setJuryType($juryType)
    {
        $this->juryType = $juryType;
        return $this;
    }

    /**
     * Set the session user
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
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
     * @param array $opts
     * @return Response
     */
    public function getUnmoderatedList($opts = [])
    {
        $opts = array_merge([
            'hydrate' => false,
            'juryType' => $this->juryType,
            'user' => $this->user, // Session user
        ], $opts);

        $response = $this->reportsRepository->getList($opts);

        if ($opts['hydrate']) {
            foreach ($response as $report) {
                $entity = $this->entitiesBuilder->single($report->getEntityGuid());
                $report->setEntity($entity);
            }
        }

        return $response;
    }

    /**
     * 
     */
    public function getReportEntity($entity_guid) 
    {
        $report = $this->reportsRepository->get($entity_guid);

        $entity = $this->entitiesBuilder->single($report->getEntityGuid());
        $report->setEntity($entity);
    
        return $report;
    }

    /**
     * Cast a decision
     * @param Decision $decision
     * @return boolean
     */
    public function cast(Decision $decision)
    {
        return $this->repository->add($decision);
    }

}
