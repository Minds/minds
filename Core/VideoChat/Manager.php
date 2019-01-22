<?php
namespace Minds\Core\VideoChat;

use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Di\Di;
use Minds\Entities\Entity;
use Minds\Entities\NormalizedEntity;

class Manager
{
    /** @var abstractCacher */
    private $cacher;

    /** @var Entity */
    private $entity;

    /** @var User */
    private $user;

    /** @var LeaseManager $leaseManager */
    private $leaseManager;

    public function __construct($cacher = null, $leaseManager = null)
    {
        $this->cacher = $cacher ?: Di::_()->get('Cache');
        $this->leaseManager = $leaseManager ?: new Leases\Manager;
    }

    /**
     * @param Entity $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getRoomKey()
    {
        $guid = $this->entity->getGuid();

        if ($lease = $this->leaseManager->get($guid)) {
            return $lease->getSecret();
        }
        
        $secret = "minds-{$guid}-" . uniqid();

        $lease = new Leases\VideoChatLease();
        $lease
            ->setKey($guid)
            ->setSecret($secret)
            ->setHolderGuid($this->user->guid)
            ->setLastRefreshed(time());
        
        $this->leaseManager->add($lease);

        return $secret;
    }

    public function refreshTTL($roomName)
    {
        // TODO: bring this back
    }

}
