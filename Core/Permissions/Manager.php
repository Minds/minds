<?php

namespace Minds\Core\Permissions;

use Minds\Core\Di\Di;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Data\Call;
use Minds\Core\Entities\Actions\Save;
use Minds\Core\Permissions\Permissions;

class Manager
{
    /** @var EntitiesBuilder $entitiesBuilder */
    protected $entitiesBuilder;
    /** @var Call */
    protected $db;
    /** @var Save */
    protected $save;

    public function __construct(
        EntitiesBuilder $entitiesBuilder = null,
        Call $db = null,
        Save $save = null)
    {
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->db = $db ?: new Call('entities_by_time');
        $this->save = $save ?: new Save(); //Mockable, else instantiate a new one on save.
    }


    /** 
    * Save permissions for an entity and propegate it to linked objects
    * @param mixed $entity a minds entity that implements the save function
    * @param Permissions $permissions the flag to apply to the entity 
    */
    public function save($entity, Permissions $permissions)
    {
        $entity->setAllowComments($permissions->getAllowComments());

        $this->save
            ->setEntity($entity)
            ->save();

        if (method_exists($entity, 'getType')
            && $entity->getType() == 'activity'
            && $entity->get('entity_guid')
        ) {
            $attachment = $this->entitiesBuilder->single($entity->get('entity_guid'));
            $attachment->setAllowComments($permissions->getAllowComments());
            $this->save
                ->setEntity($attachment)
                ->save();
        }

        foreach ($this->db->getRow('activity:entitylink:'.$entity->getGUID()) as $parentGuid => $ts) {
            $activity = $this->entitiesBuilder->single($parentGuid);
            $activity->setAllowComments($permissions->getAllowComments());
            $this->save
                ->setEntity($activity)
                ->save();
        }
    }
}
