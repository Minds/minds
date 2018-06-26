<?php

/**
 * Minds Entities Save action
 *
 * @author emi
 */

namespace Minds\Core\Entities\Actions;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;

class Save
{
    /** @var Dispatcher */
    protected $eventsDispatcher;

    /** @var mixed */
    protected $entity;

    /**
     * Save constructor.
     * @param null $eventsDispatcher
     */
    public function __construct(
        $eventsDispatcher = null
    )
    {
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
    }

    /**
     * Sets the entity
     * @param mixed $entity
     * @return Save
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Saves the entity
     * @param mixed ...$args
     * @return bool
     * @throws \Minds\Exceptions\StopEventException
     */
    public function save(...$args)
    {
        if (!$this->entity) {
            return false;
        }

        if (method_exists($this->entity, 'save')) {
            return $this->entity->save(...$args);
        }

        $namespace = $this->entity->type;

        if ($this->entity->subtype) {
            $namespace .= ":{$this->entity->subtype}";
        }

        return $this->eventsDispatcher->trigger('entity:save', $namespace, [
            'entity' => $this->entity,
        ], false);
    }
}
