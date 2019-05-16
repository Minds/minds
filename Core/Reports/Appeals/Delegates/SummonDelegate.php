<?php
/**
 * SummonDelegate
 *
 * @author edgebal
 */

namespace Minds\Core\Reports\Appeals\Delegates;

use Minds\Core\Queue\Client;
use Minds\Core\Queue\Interfaces\QueueClient;
use Minds\Core\Queue\Runners\ReportsAppealSummon;
use Minds\Core\Reports\Appeals\Appeal;

class SummonDelegate
{
    /** @var QueueClient */
    protected $queue;

    public function __construct(
        $queue = null
    )
    {
        $this->queue = $queue ?: Client::build();
    }

    public function onAppeal(Appeal $appeal)
    {
        $this->queue
            ->setQueue(ReportsAppealSummon::class)
            ->send([
                'appeal' => $appeal,
                'cohort' => null, // TODO: It can be an array of user guids. For development purposes only.
            ]);
    }
}
