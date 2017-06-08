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
    protected $stripe;
    protected $payload;
    protected $signature;
    protected $event;
    protected $aliases = [
      'invoice.created' => 'onInvoicePaymentSuccess'
    ];
    protected $hooks;

    public function __construct($hooks = null, $stripe)
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

    public function buildEvent()
    {
        $this->event = \Stripe\Webhook::constructEvent($this->payload, $this->signature, 'whsec_0NzTmT5Ts216W7muNCqLLYpuzGEZEJSj');
        return $this->event;
    }

    /**
      * Run the notification hook
      * @return $this
      */
    public function run()
    {
        $this->buildEvent();
        $this->routeAlias();
        return $this;
    }

    protected function routeAlias()
    {
        if (method_exists($this, $this->aliases[$this->event->type])) {
            $method = $this->aliases[$this->event->type];
            $this->$method();
        }
    }

    protected function onInvoicePaymentSuccess()
    {
        $invoiceObj = $this->event->data->object;
        $lines = $invoiceObj->lines->data;
        $chargeId = $invoiceObj->charge;

        $metadata = [];

        foreach ($lines as $line) {
            if($line->type == "subscription"){
                $metadata = $line->metadata->__toArray(false);
            }
        }

        $charge = \Stripe\Charge::retrieve($chargeId, [
          'stripe_account' => $this->event->account
        ]);
        $charge->metadata = (array) $metadata;
        $charge->save();
    }

    /**
     * @return void
     */
    protected function check()
    {
        error_log("[webook]:: check is OK!");
    }
}
