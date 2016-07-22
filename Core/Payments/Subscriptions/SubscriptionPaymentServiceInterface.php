<?php
/**
 * Payment service interface
 */
namespace Minds\Core\Payments\Subscriptions;

use Minds\Core\Payments\Customer;
use Minds\Core\Payments\PaymentMethod;
use Minds\Core\Payments\Subscriptions\Subscription;

interface SubscriptionPaymentServiceInterface
{
    public function createCustomer(Customer $customer);

    public function createPaymentMethod(PaymentMethod $payment_method);

    public function createSubscription(Subscription $subscription);

    public function getSubscription($subscription_id);
}
