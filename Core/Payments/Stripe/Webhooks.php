<?php
/**
 * Braintree webhooks
 */

namespace Minds\Core\Payments\Stripe;

use Minds\Core;
use Minds\Core\Guid;
use Minds\Core\Payments;
use Minds\Entities;


class Webhooks
{
    protected $payload;
    protected $signature;
    protected $notification;
    protected $aliases = [
    ];
    protected $hooks;

    public function __construct($hooks = null)
    {
        $this->hooks = $hooks ?: new Payments\Hooks();
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
    public function run()
    {
        $this->buildNotification();
        $this->routeAlias();
        return $this;
    }

    protected function buildNotification()
    {
    }

    protected function routeAlias()
    {
        if (method_exists($this, $this->aliases[$this->notification->kind])) {
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
            ->setId($this->notification->subscription->id)
            ->setBalance($this->notification->subscription->balance)
            ->setPrice($this->notification->subscription->price);
        $this->hooks->onCharged($subscription);
    }

    /**
     * @return void
     */
    protected function subscriptionActive()
    {
        $subscription = (new Subscription())
            ->setId($this->notification->subscription->id)
            ->setBalance($this->notification->subscription->balance)
            ->setPrice($this->notification->subscription->price);
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

    /**
     * @return void
     */
    protected function check()
    {
        error_log("[webook]:: check is OK!");
    }
}
