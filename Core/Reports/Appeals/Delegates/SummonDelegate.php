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
        switch ($appeal->getReport()->getReasonCode()) {
            case 1: // Illegal
            case 3: // Encourages or incites violence
            case 5: // Personal and confidential information
            case 15: // Trademark infringement
            case 10: // Copyright
            case 16: // Token manipulation
            case 13: // Malware
                return; // Can not have community jury
        }

        $this->queue
            ->setQueue('ReportsAppealSummon')
            ->send([
                'appeal' => serialize($appeal),
                'cohort' => null, // TODO: It can be an array of user guids. For development purposes only.
            ]);
    }
}
