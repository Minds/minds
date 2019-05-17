<?php

namespace Minds\Controllers\Cli;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Cli;
use Minds\Core\Reports\Summons\Summon;
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

        /** @var Core\Reports\Summons\Manager $summonsManager */
        $summonsManager = Di::_()->get('Moderation\Summons\Manager');

        $userId = $this->getOpt('user');
        $reportUrn = $this->getOpt('report');
        $juryType = $this->getOpt('jury-type') ?? null;
        $respond = $this->getOpt('respond') ?? null;

        if (!$userId || !$reportUrn) {
            $this->out([
                'Usage:',
                '- Summoning: cli.php moderation summon --user=<username_or_guid> --report=<report_urn>',
                '- Responding: cli.php moderation summon --user=<username_or_guid> --report=<report_urn> --jury-type=<initial_jury|appeal_jury> --respond=<accepted|declined>',
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

            $summonsManager->summon($appeal, [ $user->guid ]);

            $this->out("Summoned {$user->guid} to {$reportUrn}");
        } else {
            $summon = new Summon();
            $summon
                ->setReportUrn($reportUrn)
                ->setJuryType($juryType)
                ->setJurorGuid((string) $user->guid)
                ->setStatus($respond);

                $summonsManager->respond($summon);

            $this->out("Responded to {$user->guid}'s summon to {$reportUrn} with {$respond}");
        }
    }

    public function dev_only_simulate_summon()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        /** @var Core\Reports\Repository $reportsRepository */
        $reportsRepository = Di::_()->get('Reports\Repository');

        /** @var Core\Reports\Summons\Manager $summonsManager */
        $summonsManager = Di::_()->get('Moderation\Summons\Manager');

        $reportUrn = $this->getOpt('report');

        if (!$reportUrn) {
            $this->out([
                'Usage: cli.php moderation dev_only_simulate_summon --report=<report_urn>',
            ]);

            exit(1);
        }
        $report = $reportsRepository->get($reportUrn);

        if (!$report) {
            $this->out('Error: Invalid report');
            exit(1);
        }

        $appeal = new Core\Reports\Appeals\Appeal();
        $appeal
            ->setReport($report)
            ->setOwnerGuid($report->getEntityOwnerGuid());

        $cohort = $summonsManager->summon($appeal, null);

        var_dump($cohort);
    }
}
