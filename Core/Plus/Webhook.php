<?php
/**
 * Minds Wallet Subscription Hook
 */
namespace Minds\Core\Plus;

use Minds\Core;
use Minds\Core\Payments;
use Minds\Core\Payments\HookInterface;
use Minds\Helpers\Wallet as WalletHelper;
use Minds\Entities;

class Webhook implements HookInterface
{

    public function onCharged($subscription)
    {
        WalletHelper::createTransaction($subscription->getCustomer()->getUser()->guid, 10000, null, "Plus Points");
    }

    public function onActive($subscription)
    {
        error_log("[webhook]:: gotOnActive");
    }

    public function onExpired($subscription)
    {
    }

    public function onOverdue($subscription)
    {
    }

    public function onCanceled($subscription)
    {
    }
}
