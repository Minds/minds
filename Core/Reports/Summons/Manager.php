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
use Minds\Core\Reports\Manager as ReportsManager;

class Manager
{
    /** @var Cohort $cohort */
    protected $cohort;

    /** @var Repository $repository */
    protected $repository;

    /** @var ReportsManager $reportsManager */
    protected $reportsManager;

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
        $reportsManager = null,
        $queueClient = null,
        $socketDelegate = null
    )
    {
        $this->cohort = $cohort ?: new Cohort();
        $this->repository = $repository ?: new Repository();
        $this->reportsManager = $reportsManager ?: new ReportsManager;
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
        // Get a fresh report to collect completed jurors
        $report = $report = $this->reportsManager->getReport($appeal->getReport()->getUrn());
        $reportUrn = $report->getUrn();
        $juryType = 'appeal_jury';

        $missing = 0;

        if (!$cohort) {
            $summonses = iterator_to_array($this->repository->getList([
                'report_urn' => $reportUrn,
                'jury_type' => $juryType,
            ]));

            $completedJurorGuids = array_map(function($decision) {
                return $decision->getJurorGuid();
            }, array_merge($report->getAppealJuryDecisions(), $report->getInitialJuryDecisions()));

            // Remove the summons of jurors who have already voted

            $summonses = array_filter($summonses, function (Summon $summons) use ($completedJurorGuids) {
                return !in_array($summons->getJurorGuid(), $completedJurorGuids);
            });

            // Check how many are missing

            $notDeclined = array_filter($summonses, function (Summon $summons) {
                return $summons->isAccepted() || $summons->isAwaiting();
            });

            $missing = 12 - count($notDeclined);

            // If we have a full jury, don't summon

            if ($missing <= 0) {
                return 0;
            }

            // Reduce jury to juror guids and try to pick up to missing size

            $pendingJurorGuids = array_map(function (Summon $summons) {
                return (string) $summons->getJurorGuid();
            }, $summonses);

            $cohort = $this->cohort->pick([
                'size' => $missing,
                'for' => $appeal->getOwnerGuid(),
                'except' => $pendingJurorGuids,
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
