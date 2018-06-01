<?php
/**
 * Payment Subscription Entity
 */
namespace Minds\Core\Payments\Subscriptions;

use Minds\Entities\User;
use Minds\Entities\Entity;
use Minds\Entities\Factory;
use Minds\Core\Guid;
use Minds\Traits\MagicAttributes;

class Subscription
{

    use MagicAttributes;

    private $payment_method;
    private $id;
    private $customer;
    private $merchant;

    private $fee;
    private $quantity = 1;
    private $created_at;

    private $amount;
    private $last_billing;
    private $next_billing;
    private $interval = 'monthly';
    private $status = 'active';

    private $plan_id;
    private $trial_period;
    private $addOns;
    private $coupon;

    public function __construct()
    {
    }

    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    public function setPaymentMethod($method)
    {
        $this->payment_method = $method;
        return $this;
    }

    public function getId()
    {
        return $this->id ?: 'guid:' . Guid::build();
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the user who is purchasing the subscriptions
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        if (is_numeric($user)) {
            $this->user = new User();
            $this->user->guid = $user;
            return $this;
        }
        $this->user = $user;
        return $this;
    }

    /**
     * Return the customer who is purchasing the subscription
     * @return $this
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the entity that is benefitting from the subscription
     * @param Entity $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        if (is_numeric($entity)) {
            $this->entity = new Entity();
            $this->entity->guid = $entity;
            return $this;
        }
        $this->entity = $entity;
        return $this;
    }

    /**
     * Return the entity that is benefitting from the subscription
     * @return $this
     */
    public function getEntity()
    {
        return $this->entity;
    }

    public function setMerchant($merchant)
    {
        if ($merchant instanceof User) {
            $merchant = $merchant->getMerchant();
        }
        $this->merchant = $merchant;
        return $this;
    }

    public function getMerchant()
    {
        return $this->merchant;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getFee()
    {
        return $this->fee;
    }

    public function setFee($fee)
    {
        $this->fee = $fee;
        return $this;
    }

    public function setLastBilling($timestamp)
    {
        $this->last_billing = $timestamp;
        return $this;
    }

    public function getLastBilling()
    {
        return $this->last_billing ?: time();
    }

    public function getNextBilling()
    {
        return $this->next_billing;
    }

    public function setNextBilling($timestamp)
    {
        $this->next_billing = $timestamp;
        return $this;
    }

    public function setInterval($interval)
    {
        $this->interval = $interval;
        return $this;
    }

    public function getInterval()
    {
        return $this->interval;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
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

    public function setCoupon($coupon)
    {
        $this->coupon = $coupon;
        return $this;
    }

    public function getCoupon()
    {
        return $this->coupon;
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

    public function getExportableValues()
    {
        return [
            'id',
            'amount',
            'created_at',
            'next_billing',
            'last_billing',
            'status',
            'interval',
            'plan_id',
            'payment_method',
            'trial_period',
            'addOns'
        ];
    }

    public function export()
    {
        $export = [];

        foreach ($this->getExportableValues() as $v) {
            if (!is_null($this->$v)) {
                $export[$v] = $this->$v;
            }
        }

        $export['entity'] = Factory::build($this->getEntity()->guid);
        $export['entity_guid'] = $this->getEntity() ? $this->getEntity()->guid : null;
        $export['user_guid'] = $this->getUser() ? $this->getUser()->guid : null;

        //$export = \Minds\Helpers\Export::dateTimeToTimestamp($export);
        $export = array_merge($export, \Minds\Core\Events\Dispatcher::trigger('export:extender', 'all', ['entity' => $this], []));
        $export = \Minds\Helpers\Export::sanitize($export);
        return $export;
    }

    public function isValid()
    {
        if (!$this->plan_id) {
            throw new \Exception('Plan id is required');
        }

        if (!$this->payment_method) {
            throw new \Exception('Payment Method is required');
        }

        if (!$this->user || !$this->user->guid) {
            throw new \Exception('User is required');
        }
    }

}
