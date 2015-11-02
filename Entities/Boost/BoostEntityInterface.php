<?php
/**
 * Boost Entity Interface
 */

namespace Minds\Entities\Boost;

use Minds\Entities\Entity;

interface BoostEntityInterface
{
    /**
   * Set the entity to boost
   * @param Entity $entity
   * @return $this
   */
  public function setEntity(Entity $entity);

  /**
   * Get the entity
   * @return Entity
   */
  public function getEntity();

  /**
   * Set the state of the boost
   * @param string $state
   * @return $this
   */
  public function setState($state);

  /**
   * Return the state of the boost
   * @return string
   */
  public function getState();
}
