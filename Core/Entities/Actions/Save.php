<?php

/**
 * Minds Entities Save action.
 *
 * @author emi
 */

namespace Minds\Core\Entities\Actions;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Helpers\MagicAttributes;

/**
 * Save Action
 * @method Save setEntity($entity)
 * @method bool save(...$args)
 */
class Save
{
    /** @var Dispatcher */
    protected $eventsDispatcher;

    /** @var mixed */
    protected $entity;

    /**
     * Save constructor.
     *
     * @param null $eventsDispatcher
     */
    public function __construct(
        $eventsDispatcher = null
    ) {
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
    }

    /**
     * Sets the entity.
     *
     * @param mixed $entity
     *
     * @return Save
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Saves the entity.
     *
     * @param mixed ...$args
     *
     * @return bool
     *
     * @throws \Minds\Exceptions\StopEventException
     */
    public function save(...$args)
    {
        if (!$this->entity) {
            return false;
        }

        $this->beforeSave();
        
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

    /**
     * Manipulate all compliant entities before saving.
     */
    protected function beforeSave()
    {
        $this->tagNSFW();
    }

    protected function tagNSFW()
    {
        $nsfwReasons = [];

        if(method_exists($this->entity, 'getNSFW')) {
            $nsfwReasons = array_merge($nsfwReasons, $this->entity->getNSFW());
            $nsfwReasons = array_merge($nsfwReasons, $this->entity->getNSFWLock());
        }

        if (method_exists($this->entity, 'getOwnerEntity') && $this->entity->getOwnerEntity()) {
            $nsfwReasons = array_merge($nsfwReasons, $this->entity->getOwnerEntity()->getNSFW());
            $nsfwReasons = array_merge($nsfwReasons, $this->entity->getOwnerEntity()->getNSFWLock());
            // Legacy explicit follow through
            if ($this->entity->getOwnerEntity()->isMature()) {
                $nsfwReasons = array_merge($nsfwReasons, [ 6 ]);
                if (MagicAttributes::setterExists($this->entity, 'setMature')) {
                    $this->entity->setMature(true);
                } elseif (method_exists($this->entity, 'setFlag')) {
                    $this->entity->setFlag('mature', true);
                }
            }
        }

        if (method_exists($this->entity, 'getContainerEntity') && $this->entity->getContainerEntity()) {
            $nsfwReasons = array_merge($nsfwReasons, $this->entity->getContainerEntity()->getNSFW());
            $nsfwReasons = array_merge($nsfwReasons, $this->entity->getContainerEntity()->getNSFWLock());
        }

        $this->entity->setNSFW($nsfwReasons);
    }
}
