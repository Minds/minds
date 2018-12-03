<?php
/**
 * RateLimits Manager
 * @author Mark
 */

namespace Minds\Core\Security\RateLimits;

use Minds\Core\Data\Sessions;
use Minds\Entities\Entity;
use Minds\Entities\User;

class Manager
{
    /** @var Sessions */
    private $sessions;

    /** @var Delegates\Notification */
    private $notificationDelegate;

    /** @var Delegates\Analytics */
    private $analyticsDelegate;

    /** @var User */
    private $user;

    /** @var Entity */
    private $entity;

    /** @var string $key */
    private $key;

    /** @var int $limitLength */
    private $limitLength = 300; //5 minutes

    public function __construct(
        $sessions = null,
        $notificationDelegate = null,
        $analyticsDelegate = null
    )
    {
        $this->sessions = $sessions ?: new Sessions;
        $this->notificationDelegate = $notificationDelegate ?: new Delegates\Notification;
        $this->analyticsDelegate = $analyticsDelegate ?: new Delegates\Analytics;
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
     * @param string $key
     * @return $this
     */
    public function setInteraction($key)
    {
        return $this->setKey("interaction:$key");
    }

    /**
     * Set the key
     * @param string $key
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

        //Send a notification
        $this->notificationDelegate->notify($this->user, $this->key, $this->limitLength);
        //Emit to analytics
        $this->analyticsDelegate->emit($this->user, $this->key, $this->limitLength);
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
