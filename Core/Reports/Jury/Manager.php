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
use Minds\Common\Urn;
use Minds\Core\Entities\Resolver as EntitiesResolver;

class Manager
{

    /** @var Repository $repository */
    private $repository;

    /** @var EntitiesBuilder $entitiesResolver */
    private $entitiesResolver;

    /** @var VerdictManager $verdictManager */
    private $verdictManager;

    /** @var string $juryType */
    private $juryType;

    /** @var User $user */
    private $user;

    public function __construct(
        $repository = null,
        $entitiesResolver = null,
        $verdictManager = null
    )
    {
        $this->repository = $repository ?: new Repository;
        $this->entitiesResolver = $entitiesResolver  ?: new EntitiesResolver;
        $this->verdictManager = $verdictManager ?: Di::_()->get('Moderation\Verdict\Manager');
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

        $response = $this->repository->getList($opts);

        if ($opts['hydrate']) {
            foreach ($response as $report) {
                $entity = $this->entitiesResolver->single(
                    (new Urn())->setUrn($report->getEntityUrn())
                );
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
        if ($report) {
            $entity = $this->entitiesResolver->single(
                (new Urn())->setUrn($report->getEntityUrn())
            );
            $report->setEntity($entity);
        }
        return $report;
    }

    /**
     * Cast a decision
     * @param Decision $decision
     * @return boolean
     */
    public function cast(Decision $decision)
    {
        $report = $decision->getReport();

        if (!in_array($report->getState(), [ 'reported', 'appealed' ])) {
            throw new JuryClosedException();
        }

        $success = $this->repository->add($decision);

        if ($decision->isAppeal()) {
            $decisions = $report->getAppealJuryDecisions();
            $decisions[] = $decision;
            $report->setAppealJuryDecisions($decisions);
        } else {
            $decisions = $report->getInitialJuryDecisions();
            $decisions[] = $decision;
            $report->setInitialJuryDecisions($decisions);
        }

        $this->verdictManager->decideFromReport($report);
  
        return $success;
    }

}
