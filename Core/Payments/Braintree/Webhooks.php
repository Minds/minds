<?php
/**
 * Braintree webhooks
 */

namespace Minds\Core\Payments\Braintree;

use Minds\Core;
use Minds\Core\Guid;
use Minds\Core\Payments;
use Minds\Entities;

use Braintree_ClientToken;
use Braintree_Configuration;
use Braintree_MerchantAccount;
use Braintree_Transaction;
use Braintree_TransactionSearch;
use Braintree_Customer;
use Braintree_CustomerSearch;
use Braintree_PaymentMethod;
use Braintree_Subscription;
use Braintree_Test_MerchantAccount;
use Braintree_WebhookNotification;
use Minds\Core\Payments\Subscriptions\Subscription;

class Webhooks
{

    protected $payload;
    protected $signature;
    protected $notification;
    protected $aliases = [
        Braintree_WebhookNotification::SUB_MERCHANT_ACCOUNT_APPROVED => 'subMerchantApproved',
        Braintree_WebhookNotification::SUB_MERCHANT_ACCOUNT_DECLINED => 'subMerchantDeclined',
        Braintree_WebhookNotification::SUBSCRIPTION_CHARGED_SUCCESSFULLY => 'subscriptionCharged',
        Braintree_WebhookNotification::SUBSCRIPTION_WENT_ACTIVE => 'subscriptionActive',
        Braintree_WebhookNotification::SUBSCRIPTION_EXPIRED => 'subscriptionExpired',
        Braintree_WebhookNotification::SUBSCRIPTION_WENT_PAST_DUE => 'subscriptionOverdue',
        Braintree_WebhookNotification::SUBSCRIPTION_CANCELED => 'subscriptionCanceled'
    ];
    protected $hooks;

    public function __construct($hooks = NULL, $braintree = NULL)
    {
        $this->hooks = $hooks ?: new Payments\Hooks();
        $this->braintree = $braintree ?: Payments\Factory::build("Braintree");
    }

    /**
     * Set the request payload
     * @param string $payload
     * @return $this
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * Set the request signature
     * @param string $signature
     * @return $this
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;
        return $this;
    }

    /**
      * Run the notification hook
      * @return $this
      */
    public function run(){
        $this->buildNotification();
        $this->routeAlias();
        return $this;
    }

    protected function buildNotification()
    {
        $this->notification = Braintree_WebhookNotification::parse($this->signature, $this->payload);
    }

    protected function routeAlias()
    {
        if(method_exists($this, $this->aliases[$this->notification->kind])){
            $method = $this->aliases[$this->notification->kind];
            $this->$method();
        }
    }

    /**
     * @return void
     */
    protected function subMerchantApproved()
    {
        $message = "Congrats, you are now a Minds Merchant";
        Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', [
          'to'=>[$notification->merchantAccount->id],
          'from' => 100000000000000519,
          'notification_view' => 'custom_message',
          'params' => ['message'=>$message],
          'message'=>$message
        ]);
  }

    /**
     * @return void
     */
    protected function subMerchantDeclined()
    {
        $reason = $notification->message;
        $message = "Sorry, we could not approve your Merchant Account: $reason";
        Core\Events\Dispatcher::trigger('notification', 'elgg/hook/activity', [
          'to'=>[$notification->merchantAccount->id],
          'from' => 100000000000000519,
          'notification_view' => 'custom_message',
          'params' => ['message'=>$message],
          'message'=>$message
        ]);
    }

    /**
     * @return void
     */
    protected function subscriptionCharged()
    {
        $subscription = (new Subscription())
          ->setId($this->notification->subscription->id);
        $this->hooks->onCharged($subscription);
    }

    /**
     * @return void
     */
    protected function subscriptionActive()
    {
        $subscription = (new Subscription())
          ->setId($this->notification->subscription->id);
        $this->hooks->onActive($subscription);
    }

    /**
     * @return void
     */
    protected function subscriptionExpired()
    {
        $subscription = (new Subscription())
          ->setId($this->notification->subscription->id);
        $this->hooks->onExpired($subscription);
    }

    /**
     * @return void
     */
    protected function subscriprionOverdue()
    {
        $subscription = (new Subscription())
          ->setId($this->notification->subscription->id);
        $this->hooks->onOverdue($subscription);
    }

    /**
     * @return void
     */
    protected function subscriptionCanceled()
    {
        $subscription = (new Subscription())
          ->setId($this->notification->subscription->id);
        $this->hooks->onCanceled($subscription);
    }

}
