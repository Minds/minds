<?php
/**
 * RateLimits Manager
 * @author Mark
 */
namespace Minds\Core\Security\RateLimits;

use Minds\Core\Data\Sessions;

class Manager
{

    /** @var string $key */
    private $key;

    /** @var int $limitLength */
    private $limitLength = 300; //5 minutes

    /** @var Sessions $sessions */

    public function __construct($sessions = null, $notificationDelegate = null)
    {
        $this->sessions = $sessions ?: new Sessions;
        $this->notificationDelegate = $notificationDelegate ?: new Delegates\Notification;
    }

    /**
     * Set user / actor
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
    
    /**
     * Set entity
     * @param Entity $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }
    
    /**
     * Set the interaction
     * @param User $user
     * @return $this
     */
    public function setInteraction($key)
    {
        return $this->setKey("interaction:$key");
    }

    /**
     * Set the key
     * @param User $user
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = "ratelimited_$key";
        return $this;
    }

    /**
     * Set the limit to impose
     * @param int $length
     * @return $this
     */
    public function setLimitLength($length)
    {
        $this->limitLength = $length;
        return $this;
    }

    /**
     * Impose the rate limit
     * @return void
     */
    public function impose()
    {
        $this->user->set($this->key, time() + $this->limitLength);
        $this->user->save(); //TODO: update to new repo system soon

        $this->sessions->syncRemote($this->user->guid, $this->user);

        //Send a notification
        $this->notificationDelegate->notify($this->user, $this->key);
    }
    
    /**
     * Return if a rate limit is imposed
     * @param User $user
     * @return bool
     */
    public function isLimited()
    {
        if (!$this->user->get($this->key)) {
            return false;
        }

        if ($this->user->get($this->key) < time()) {
            return false;
        }

        return true;
    }

}
