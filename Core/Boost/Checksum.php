<?php

/**
 * Description
 *
 * @author emi
 */

namespace Minds\Core\Boost;

use Minds\Entities\Entity;
use Minds\Entities\Factory;

class Checksum
{
    /** @var int $guid */
    protected $guid;

    /** @var int|Entity */
    protected $entity;

    /**
     * @param int $guid
     * @return Checksum
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * @param int|Entity $entity
     * @return Checksum
     */
    public function setEntity($entity)
    {
        if (is_numeric($entity)) {
            $entity = Factory::build($entity);
        }

        $this->entity = $entity;
        return $this;
    }

    /**
     * Generates a checksum hash based on a GUID and an entity
     * @return string
     * @throws \Exception
     */
    public function generate()
    {
        if (!$this->guid) {
            throw new \Exception('GUID is required');
        }

        if (!$this->entity) {
            throw new \Exception('Entity is required');
        }

        $body = $this->guid
            . $this->entity->type
            . $this->entity->guid
            . ($this->entity->owner_guid ?: '')
            . ($this->entity->perma_url ?: '')
            . ($this->entity->message ?: '')
            . ($this->entity->title ?: '')
            . $this->entity->time_created;

        return md5($body);
    }
}
