<?php
/**
 * Subscription Model
 */
namespace Minds\Core\Subscriptions;

use Minds\Traits\MagicAttributes;

class Subscription
{
    use MagicAttributes;

    /** @var int $publisherGuid */
    private $publisherGuid;

    /** @var int $subscriberGuid */
    private $subscriberGuid;

    /** @var bool $active */
    private $active = false;

}
