<?php
/**
 * Payment Subscription Entity
 */
namespace Minds\Core\Payments\Subscriptions;

class Subscription
{

    private $payment_method;
    private $id;

    private $balance;
    private $created_at;
    private $next_billing_period_amount;
    private $next_billing_date;
    private $plan_id;
    private $trial_period;
    private $addOns;

    public function __construct()
    {
    }

    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(PaymentMethod $payment_method)
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getNextBillingPeriodAmount()
    {
        return $this->next_billing_period_amount;
    }

    public function setNextBillingPeriodAmount($next_billing_period_amount)
    {
        $this->next_billing_period_amount = $next_billing_period_amount;

        return $this;
    }

    public function getNextBillingDate()
    {
        return $this->next_billing_date;
    }

    public function setNextBillingDate($next_billing_date)
    {
        $this->next_billing_date = $next_billing_date;

        return $this;
    }

    public function getPlanId()
    {
        return $this->plan_id;
    }

    public function setPlanId($plan_id)
    {
        $this->plan_id = $plan_id;

        return $this;
    }

    public function getTrialPeriod()
    {
        return $this->trial_period;
    }

    public function setTrialPeriod($trial_period)
    {
        $this->trial_period = $trial_period;

        return $this;
    }

    public function getAddOns()
    {
        return $this->addOns ?: [];
    }

    public function setAddOns($addOns)
    {
        $this->addOns = $addOns;
        return $this;
    }

    public function setAddOn($addOn)
    {
        $this->addOns[] = $addOn;
        return $this;
    }

    public function getExportableValues() {
        return [
            'balance',
            'created_at',
            'next_billing_period_amount',
            'next_billing_date',
            'plan_id',
            'trial_period',
            'addOns'
        ];
    }

    public function export(){

        $export = [];

        foreach ($this->getExportableValues() as $v) {
            if (!is_null($this->$v)) {
                $export[$v] = $this->$v;
            }
        }

        //$export = \Minds\Helpers\Export::dateTimeToTimestamp($export);
        $export = array_merge($export, \Minds\Core\Events\Dispatcher::trigger('export:extender', 'all', ['entity' => $this], []));
        $export = \Minds\Helpers\Export::sanitize($export);
        return $export;

    }
}
