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

    public function __construct($cacher = null)
    {
        $this->cacher = $cacher ?: Di::_()->get('Cache');
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

    public function getRoomKey()
    {
        $guid = $this->entity->getGuid();
        
        if ($cached = $this->cacher->get($guid)) {
            return $cached;
        }
        
        $key = "minds-{$guid}-" . uniqid();

        $this->cacher->set($guid, $key, 3600 * 2); // 2 hour TTL

        return $key;
    }

    public function refreshTTL($roomName)
    {
        if ($key = $this->cacher->get($roomName) !== false) {
            $this->cacher->set($roomName, $key, 3600 * 2);
        }
    }
}
