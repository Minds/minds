<?php
/**
 * Manager
 *
 * @author edgebal
 */

namespace Minds\Core\Reports\Summons;

use Exception;
use Minds\Core\Queue\Client as QueueClient;
use Minds\Core\Queue\Client;
use Minds\Core\Queue\Runners\ReportsAppealSummon;
use Minds\Core\Reports\Appeals\Appeal;
use Minds\Core\Reports\Summons\Delegates;

class Manager
{
    /** @var Cohort $cohort */
    protected $cohort;

    /** @var Repository $repository */
    protected $repository;

    /** @var QueueClient */
    protected $queueClient;

    /** @var Delegates\SocketDelegate $socketDelegate */
    protected $socketDelegate;

    /**
     * Manager constructor.
     * @param Cohort $cohort
     * @param Repository $repository
     * @param QueueClient $queueClient
     * @param Delegates\SocketDelegate $socketDelegate
     * @throws Exception
     */
    public function __construct(
        $cohort = null,
        $repository = null,
        $queueClient = null,
        $socketDelegate = null
    )
    {
        $this->cohort = $cohort ?: new Cohort();
        $this->repository = $repository ?: new Repository();
        $this->queueClient = $queueClient ?: Client::build();
        $this->socketDelegate = $socketDelegate ?: new Delegates\SocketDelegate();
    }

    /**
     * @param Appeal $appeal
     * @param array $cohort
     * @return int
     * @throws Exception
     */
    public function summon(Appeal $appeal, $cohort = null)
    {
        $reportUrn = $appeal->getReport()->getUrn();
        $juryType = 'appeal_jury';

        $missing = 0;

        if (!$cohort) {
            $jury = iterator_to_array($this->repository->getList([
                'report_urn' => $reportUrn,
                'jury_type' => $juryType,
            ]));

            // Check how many are missing

            $notDeclined = array_filter($jury, function (Summon $summon) {
                return $summon->isAccepted() || $summon->isAwaiting();
            });

            $missing = 12 - count($notDeclined);

            // If we have a full jury, don't summon

            if ($missing <= 0) {
                return 0;
            }

            // Reduce jury to juror guids and try to pick up to missing size

            $juryGuids = array_map(function (Summon $summon) {
                return (string) $summon->getJurorGuid();
            }, $jury);

            $cohort = $this->cohort->pick([
                'size' => $missing,
                'for' => $appeal->getOwnerGuid(),
                'except' => $juryGuids,
                'active_threshold' => 5 * 60,
            ]);
        }

        foreach ($cohort as $juror) {
            $summon = new Summon();
            $summon
                ->setReportUrn($reportUrn)
                ->setJuryType($juryType)
                ->setJurorGuid($juror)
                ->setTtl(120)
                ->setStatus('awaiting');

            $this->repository->add($summon);
            $this->socketDelegate->onSummon($summon);
        }

        return $missing;
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
     * @throws Exception
     */
    public function respond(Summon $summon)
    {
        if (!$this->isSummoned($summon)) {
            throw new Exception('User is not summoned');
        }

        $summon
            ->setTtl(10 * 60);

        $this->repository->add($summon);

        return $summon;
    }

    /**
     * @param string $reportUrn
     * @param string $juryType
     * @return bool
     * @throws Exception
     */
    public function release($reportUrn, $juryType)
    {
        return $this->repository->deleteAll([
            'report_urn' => $reportUrn,
            'jury_type' => $juryType,
        ]);
    }

    /**
     * @param Appeal $appeal
     */
    public function defer(Appeal $appeal)
    {
        $this->queueClient
            ->setQueue(ReportsAppealSummon::class)
            ->send([
                'appeal' => $appeal,
            ], 600);
    }
}
