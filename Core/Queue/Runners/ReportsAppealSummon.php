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
            ->setQueue(static::class)
            ->receive(function (Message $data) {
                $params = $data->getData();

                $appeal = $params['appeal'] ?? null;
                $cohort = $params['cohort'] ?? null;

                if (!$appeal) {
                    echo 'Invalid empty appeal' . PHP_EOL;
                    return;
                }

                /** @var Manager $manager */
                $manager = Di::_()->get('Moderation\Summons\Manager');
                $manager->summon($appeal, $cohort);
            });
    }
}
