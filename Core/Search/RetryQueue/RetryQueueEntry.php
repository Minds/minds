<?php
/**
 * RetryQueueEntry
 * @author edgebal
 */

namespace Minds\Core\Search\RetryQueue;

use Minds\Traits\MagicAttributes;

/**
 * Class RetryQueueEntry
 * @package Minds\Core\Search\RetryQueue
 * @method string getEntityUrn()
 * @method RetryQueueEntry setEntityUrn(string $entityUrn)
 * @method int getRetries()
 * @method RetryQueueEntry setRetries(int $retries)
 * @method int getLastRetry()
 * @method RetryQueueEntry setLastRetry(int $entityUrn)
 */
class RetryQueueEntry
{
    use MagicAttributes;

    protected $entityUrn;

    protected $retries;

    protected $lastRetry;
}
