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
use Minds\Core\Reports\UserReports\UserReport;
use Minds\Helpers\Text;

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
     * @param ReportsManager $reportsManager
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
        $this->reportsManager = $reportsManager ?: new ReportsManager();
        $this->queueClient = $queueClient ?: Client::build();
        $this->socketDelegate = $socketDelegate ?: new Delegates\SocketDelegate();
    }

    /**
     * @param Appeal $appeal
     * @param array $opts
     * @return int
     * @throws Exception
     */
    public function summon(Appeal $appeal, array $opts = [])
    {
        $opts = array_merge([
            'include_only' => null,
            'active_threshold' => 5 * 60,
            'jury_size' => 12,
            'awaiting_ttl' => 120,
        ], $opts);

        // Get a fresh report to collect completed jurors

        $report = $report = $this->reportsManager->getReport($appeal->getReport()->getUrn());
        $reportUrn = $report->getUrn();
        $juryType = 'appeal_jury';

        $completedJurorGuids = array_map(function($decision) {
            return $decision->getJurorGuid();
        }, array_merge($report->getAppealJuryDecisions() ?: [], $report->getInitialJuryDecisions() ?: []));

        // Get all summonses for this case

        $summonses = iterator_to_array($this->repository->getList([
            'report_urn' => $reportUrn,
            'jury_type' => $juryType,
        ]));

        // Remove the summonses of jurors who have already voted

        $summonses = array_filter($summonses, function (Summons $summons) use ($completedJurorGuids) {
            return !in_array($summons->getJurorGuid(), $completedJurorGuids);
        });

        // Check how many are missing

        $missing = $opts['jury_size'] - count(array_filter($summonses, function (Summons $summons) {
            return $summons->isAccepted();
        }));

        // If we have a full jury, don't summon

        if ($missing <= 0) {
            return 0;
        }

        // Check how many accepted or are awaiting, it's needed to know the approximate pool size

        $poolSize = $opts['jury_size'] - count(array_filter($summonses, function (Summons $summons) {
            return $summons->isAccepted() || $summons->isAwaiting();
        }));

        // Create an array of channel GUIDs that are involved in this case

        $alreadyInvolvedGuids = array_map(function (Summons $summons) {
            return (string) $summons->getJurorGuid();
        }, $summonses);

        $alreadyInvolvedGuids = array_merge($alreadyInvolvedGuids, array_map(function (UserReport $userReport) {
            return $userReport->getReporterGuid();
        }, $report->getReports()));

        $alreadyInvolvedGuids = array_values(array_unique(Text::buildArray($alreadyInvolvedGuids)));

        // Create an array of channel phone hashes that are involved in this case

        $alreadyInvolvedPhoneHashes = $report->getUserHashes() ?: [];

        // Pick up to missing size

        $cohort = $this->cohort->pick([
            'size' => $poolSize * 500, // 500 users
            'for' => $appeal->getOwnerGuid(),
            'except' => $alreadyInvolvedGuids,
            'except_hashes' => $alreadyInvolvedPhoneHashes,
            'include_only' => $opts['include_only'],
            'active_threshold' => $opts['active_threshold'],
        ]);

        // Build Summonses

        $sent = 0;
        foreach ($cohort as $juror) {
            if (++$sent > $poolSize) {
                break;
            }
            $summons = new Summons();
            $summons
                ->setReportUrn($reportUrn)
                ->setJuryType($juryType)
                ->setJurorGuid($juror)
                ->setTtl($opts['awaiting_ttl'])
                ->setStatus('awaiting');

            $this->repository->add($summons);
            $this->socketDelegate->onSummon($summons);
            echo "\nSummoning $juror for $reportUrn";
        }

        //

        return $missing;
    }

    /**
     * @param Summons $summons
     * @return bool
     */
    public function isSummoned(Summons $summons)
    {
        return $this->repository->exists($summons);
    }

    /**
     * @param Summons $summons
     * @return Summons
     * @throws Exception
     */
    public function respond(Summons $summons)
    {
        if (!$this->isSummoned($summons)) {
            throw new Exception('User is not summoned');
        }

        if (!$summons->isDeclined()) {
            $summons
                ->setTtl(10 * 60);
        }

        $this->repository->add($summons);

        return $summons;
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
            ->setQueue('ReportsAppealSummon')
            ->send([
                'appeal' => serialize($appeal),
            ], 10); // loop every 10 seconds
    }
}
