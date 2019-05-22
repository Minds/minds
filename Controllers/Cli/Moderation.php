<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Core\Reports\Strikes\Strike;
use Minds\Core\Reports\Summons\Summons;
use Minds\Interfaces;
use Minds\Entities;

class Moderation extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function __construct()
    {
    }

    public function help($command = null)
    {
        $this->out('TBD');
    }
    
    public function exec()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    public function runVerdicts()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $manager = Di::_()->get('Moderation\Verdict\Manager');
        $manager->run($this->getOpt('jury') ?? 'initial');
    }

    public function summon()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        /** @var Core\Reports\Repository $reportsRepository */
        $reportsRepository = Di::_()->get('Reports\Repository');

        /** @var Core\Queue\Interfaces\QueueClient $queueClient */
        $queueClient = Core\Queue\Client::build();

        $reportUrn = $this->getOpt('report');
        $cohort = $this->getOpt('cohort');

        if (!$reportUrn || !$cohort) {
            $this->out([
                'Usage:',
                ' - cli.php moderation summon --report=<report_urn> --cohort=<guid1, guid2, ..., guidN>',
                ' - cli.php moderation summon --report=<report_urn> --cohort=auto',
            ]);

            exit(1);
        }

        $guids = null;

        if ($cohort !== 'auto') {
            $guids = explode(',', $cohort);
        }

        $report = $reportsRepository->get($reportUrn);

        if (!$report) {
            $this->out('Error: Invalid report');
            exit(1);
        } elseif($report->getState() !== 'initial_jury_decided') {
            $this->out("Error: Report is not appealable. State is [{$report->getState()}].");
            exit(1);
        }

        $appeal = new Core\Reports\Appeals\Appeal();
        $appeal
            ->setReport($report)
            ->setOwnerGuid($report->getEntityOwnerGuid());

        $queueClient
            ->setQueue('ReportsAppealSummon')
            ->send([
                'appeal' => $appeal,
                'cohort' => $guids ?: null,
            ]);

        $this->out('Sent to summon queue!');
    }

    public function dev_only_summon_individual()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        /** @var Core\Reports\Repository $reportsRepository */
        $reportsRepository = Di::_()->get('Reports\Repository');

        /** @var Core\Reports\Summons\Manager $summonsManager */
        $summonsManager = Di::_()->get('Moderation\Summons\Manager');

        $userId = $this->getOpt('user');
        $reportUrn = $this->getOpt('report');
        $juryType = $this->getOpt('jury-type') ?? null;
        $respond = $this->getOpt('respond') ?? null;
        $activeThreshold = $this->getOpt('active-threshold') ?? 5 * 60;

        if (!$userId || !$reportUrn) {
            $this->out([
                'Usage:',
                '- Summoning: cli.php moderation dev_only_summon_individual --user=<username_or_guid> --report=<report_urn>',
                '- Responding: cli.php moderation dev_only_summon_individual --user=<username_or_guid> --report=<report_urn> --jury-type=<initial_jury|appeal_jury> --respond=<accepted|declined>',
            ]);

            exit(1);
        }

        $user = new Entities\User($userId, false);

        if (!$user || !$user->guid) {
            $this->out('Error: Invalid user');
            exit(1);
        }

        if (!$respond) {
            $report = $reportsRepository->get($reportUrn);

            if (!$report) {
                $this->out('Error: Invalid report');
                exit(1);
            }

            $appeal = new Core\Reports\Appeals\Appeal();
            $appeal->setReport($report);

            $missing = $summonsManager->summon($appeal, [
                'include_only' => [ (string) $user->guid ],
                'active_threshold' => (int) $activeThreshold,
            ]);

            $this->out("Summoned {$user->guid} to {$reportUrn}");
            $this->out("${missing} juror(s) missing.");
        } else {
            $summons = new Summons();
            $summons
                ->setReportUrn($reportUrn)
                ->setJuryType($juryType)
                ->setJurorGuid((string) $user->guid)
                ->setStatus($respond);

                $summonsManager->respond($summons);

            $this->out("Responded to {$user->guid}'s summons to {$reportUrn} with {$respond}");
        }
    }
}
