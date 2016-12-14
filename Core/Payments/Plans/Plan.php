<?php
/**
 * Payment Plan
 */
namespace Minds\Core\Payments\Plans;

use Minds\Entities\User;

class Plan
{
    private $name;
    private $status = "pending";
    private $entity_guid;
    private $user_guid;
    private $expires = -1;
    private $subscription_id;

    /**
    * Set the name of the plan
    * @param string $name
    * @return $this;
    */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
    * Get the name of the plan
    * @return string
    */
    public function getName()
    {
        return $this->name;
    }

    /**
    * Set the status of the plan
    * @param string $status
    * @return $this;
    */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
    * Get the status of the plan
    * @return string
    */
    public function getStatus()
    {
        return $this->status;
    }

    /**
    * Set the entity_guid of the plan
    * @param string $guid
    * @return $this;
    */
    public function setEntityGuid($guid)
    {
        $this->entity_guid = $guid;
        return $this;
    }

    /**
    * Get the entity_guid of the plan
    * @return string
    */
    public function getEntityGuid()
    {
        return $this->entity_guid;
    }

    /**
    * Set the user_guid of the plan
    * @param string $guid
    * @return $this;
    */
    public function setUserGuid($guid)
    {
        $this->user_guid = $guid;
        return $this;
    }

    /**
    * Get the entity_guid of the plan
    * @return string
    */
    public function getUserGuid()
    {
        return $this->user_guid;
    }

    /**
    * Set when the plan expires
    * @param int $ts - unix timestamp of when the plan ends. -1 is never
    * @return $this;
    */
    public function setExpires($ts)
    {
        $this->expires = (int) $ts;
        return $this;
    }

    /**
    * Get the unix timestamp of when the plan expires
    * @return int
    */
    public function getExpires()
    {
        return (int) $this->expires;
    }

    /**
    * Set the subscripion id
    * @param string $id
    * @return $this;
    */
    public function setSubscriptionId($id)
    {
        $this->subscription_id = $id;
        return $this;
    }

    /**
    * Get the subscription id
    * @return string
    */
    public function getSubscriptionId()
    {
        return $this->subscription_id;
    }

}
