<?php
/**
 * ReportsAppealSummon
 *
 * @author edgebal
 */

namespace Minds\Core\Queue\Runners;

use Minds\Core\Di\Di;
use Minds\Core\Queue\Message;
use Minds\Core\Queue\Client;
use Minds\Core\Queue\Interfaces\QueueClient;
use Minds\Core\Queue\Interfaces\QueueRunner;
use Minds\Core\Reports\Appeals\Appeal;
use Minds\Core\Reports\Summons\Manager;

class ReportsAppealSummon implements QueueRunner
{
    /**
     * Run the queue
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        /** @var QueueClient $client */
        $client = Client::build();

        $client
            ->setQueue('ReportsAppealSummon')
            ->receive(function (Message $data) {
                $params = $data->getData();

                /** @var Appeal $appeal */
                $appeal = $params['appeal'] ? unserialize($params['appeal']) : null;

                /** @var string[] $cohort */
                $cohort = $params['cohort'] ?? null;

                if (!$appeal) {
                    echo 'Invalid empty appeal' . PHP_EOL;
                    return;
                }

                // Reydrate each loop
                $reportsManager = Di::_()->get('Moderation\Manager');
                $appeal->setReport($reportsManager->getReport($appeal->getReport()->getUrn()));

                if ($appeal->getReport()->getState() !== 'appealed') {
                    echo "{$appeal->getReport()->getUrn()} is already appealed..." . PHP_EOL;
                    return;
                }

                echo "Summoning for {$appeal->getReport()->getUrn()}..." . PHP_EOL;
                
                /** @var Manager $manager */
                $manager = Di::_()->get('Moderation\Summons\Manager');

                $missing = $manager->summon($appeal, [
                    'include_only' => $cohort ?: null,
                ]);

                if ($missing > 0 || $appeal->getReport()->getState() === 'appealed') {
                    echo "There are {$missing} juror(s). Deferring until case is out of `appealed` state..." . PHP_EOL;
                    $manager->defer($appeal);
                }

                echo "Done!" . PHP_EOL;
            });
    }
}
