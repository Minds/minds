<?php
/**
 * Manager
 *
 * @author edgebal
 */

namespace Minds\Core\Reports\Summons;

use Minds\Core\Reports\Appeals\Appeal;
use Minds\Core\Reports\Summons\Delegates;

class Manager
{
    /** @var Cohort $cohort */
    protected $cohort;

    /** @vat Repository $repository */
    protected $repository;

    /** @var Delegates\SocketDelegate $socketDelegate */
    protected $socketDelegate;

    /**
     * Manager constructor.
     * @param Cohort $cohort
     * @param Repository $repository
     * @param Delegates\SocketDelegate $socketDelegate
     */
    public function __construct(
        $cohort = null,
        $repository = null,
        $socketDelegate = null
    )
    {
        $this->cohort = $cohort ?: new Cohort();
        $this->repository = $repository ?: new Repository();
        $this->socketDelegate = $socketDelegate ?: new Delegates\SocketDelegate();
    }

    /**
     * @param Appeal $appeal
     * @param array $cohort
     * @throws \Exception
     */
    public function summon(Appeal $appeal, $cohort = null)
    {
        $cohort = $cohort ?: $this->cohort->getList([
            'active_threshold' => 5 * 60,
            'platform' => 'browser',
            'for' => $appeal->getOwnerGuid(),
            'validated' => true,
            'limit' => 12,
        ]);

        foreach ($cohort as $juror) {
            $summon = new Summon();
            $summon
                ->setReportUrn($appeal->getReport()->getUrn())
                ->setJuryType('appeal_jury')
                ->setJurorGuid($juror)
                ->setTtl(120)
                ->setStatus('awaiting');

            $this->repository->add($summon);
            $this->socketDelegate->onSummon($summon);
        }
    }

    /**
     * @param Summon $summon
     * @return bool
     */
    public function isSummoned(Summon $summon)
    {
        return $this->repository->exists($summon);
    }

    /**
     * @param Summon $summon
     * @return Summon
     */
    public function respond(Summon $summon)
    {
        $summon
            ->setTtl(10 * 60);

        $this->repository->add($summon);

        return $summon;
    }
}
