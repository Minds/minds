<?php
/**
 * Subscription hook interface
 */
namespace Minds\Core\Payments\Subscriptions;

use Minds\Core\Payments;

interface SubscriptionsHookInterface extends Payments\HookInterface
{

    public function onCharged($subscription);

    public function onActive($subscription);

    public function onExpired($subscription);

    public function onOverdue($subscription);

    public function onCanceled($subscription);

}
