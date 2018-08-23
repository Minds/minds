<?php

/**
 * Minds Entities Delete action
 *
 * @author Mark
 */

namespace Minds\Core\Entities\Actions;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;

class Delete
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
     * Delete the entity
     * @param mixed ...$args
     * @return bool
     * @throws \Minds\Exceptions\StopEventException
     */
    public function delete(...$args)
    {
        if (!$this->entity) {
            return false;
        }

        //// DELETES ARE SCARY SO NO FALLBACK?
        //if (method_exists($this->entity, 'delete')) {
        //    return $this->entity->delete(...$args);
        //}

        $namespace = $this->entity->type;

        if ($this->entity->subtype) {
            $namespace .= ":{$this->entity->subtype}";
        }

        return $this->eventsDispatcher->trigger('entity:delete', $namespace, [
            'entity' => $this->entity,
        ], false);
    }
}
