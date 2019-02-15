<?php
/**
 * Subscription Model
 */
namespace Minds\Core\Subscriptions;

use Minds\Traits\MagicAttributes;

/**
 * @method Subscription isActive(): boolean
 * @method Subscription getSubscriberGuid(): int
 * @method Subscription getPublisherGuid(): int
 */
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
