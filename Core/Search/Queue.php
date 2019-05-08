<?php

/**
 * Description
 *
 * @author emi
 */

namespace Minds\Core\Search;

use Minds\Core;

class Queue
{
    /**
     * @param $entity
     * @return bool
     */
    public function queue($entity)
    {
        Core\Queue\Client::build()
            ->setQueue('SearchIndexDispatcher')
            ->send([
                'entity' => serialize($entity)
            ]);

        return true;
    }

    public function queueCleanup($entity)
    {
        Core\Queue\Client::build()
            ->setQueue('SearchCleanupDispatcher')
            ->send([
                'entity' => serialize($entity)
            ]);

        return true;
    }
}
