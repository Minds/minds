<?php
/**
 * Video chat lease
 */
namespace Minds\Core\VideoChat\Leases;

use Minds\Traits\MagicAttributes;

class VideoChatLease
{

    use MagicAttributes;

    /** @var string $key */
    private $key;

    /** @var string $secret */
    private $secret;

    /** @var int $holderGuid  */
    private $holderGuid;

    /** @var int $lastRefreshed */
    private $lastRefreshed;

}