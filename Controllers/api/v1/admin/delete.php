<?php
/**
 * Minds Admin: Delete (Flag)
 *
 * @version 1
 * @author Emiliano Balbuena
 *
 */
namespace Minds\Controllers\api\v1\admin;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Helpers;
use Minds\Entities;
use Minds\Interfaces;
use Minds\Api\Factory;

class delete implements Interfaces\Api, Interfaces\ApiAdminPam
{
    /**
     * Get's an entities' deleted state
     * @param array $pages
     */
    public function get($pages)
    {
        if (!is_numeric($pages[0])) {
            return Factory::response([]);
        }

        $isDeleted = false;
        $entity = Entities\Factory::build($pages[0]);

        if (method_exists($entity, 'getDeleted')) {
            $isDeleted = $entity->getDeleted();
        } else if (method_exists($entity, 'getFlag')) {
            $isDeleted = $entity->getFlag('deleted');
        }

        return Factory::response([
            'deleted' => $isDeleted
        ]);
    }

    /**
     * POST (not used)
     * @param array $pages
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * Sets an entity as deleted
     * @param array $pages
     */
    public function put($pages)
    {
        if (!is_numeric($pages[0])) {
            return Factory::response([]);
        }

        $entity = Entities\Factory::build($pages[0]);

        if (method_exists($entity, 'setDeleted')) {
            $entity->setDeleted(true);
        } else if (method_exists($entity, 'setFlag')) {
            $entity->setFlag('deleted', true);
        } else {
            return Factory::response([
                'status' => 'error',
                'message' => 'Cannot set this entity as deleted'
            ]);
        }

        if ($entity->entity_guid) {
            $child = Entities\Factory::build($entity->entity_guid);

            if (method_exists($child, 'setDeleted')) {
                $child->setDeleted(true);
            } else if (method_exists($child, 'setFlag')) {
                $child->setFlag('deleted', true);
            }

            $child->save();
        }

        $success = $entity->save();

        if ($success) {
            return Factory::response([
                'deleted' => true
            ]);
        } else {
            return Factory::response([
                'status' => 'error',
                'message' => 'Error setting as deleted'
            ]);
        }
    }

    /**
     * Removes an entity's deleted flag
     * @param array $pages
     */
    public function delete($pages)
    {
        if (!is_numeric($pages[0])) {
            return Factory::response([]);
        }

        $entity = Entities\Factory::build($pages[0]);

        if (method_exists($entity, 'setDeleted')) {
            $entity->setDeleted(false);
        } else if (method_exists($entity, 'setFlag')) {
            $entity->setFlag('deleted', false);
        } else {
            return Factory::response([
                'status' => 'error',
                'message' => 'Cannot unset this entity as deleted'
            ]);
        }

        if ($entity->entity_guid) {
            $child = Entities\Factory::build($entity->entity_guid);

            if (method_exists($child, 'setDeleted')) {
                $child->setDeleted(false);
            } else if (method_exists($child, 'setFlag')) {
                $child->setFlag('deleted', false);
            }

            $child->save();
        }

        $success = $entity->save();

        if ($success) {
            return Factory::response([
                'deleted' => false
            ]);
        } else {
            return Factory::response([
                'status' => 'error',
                'message' => 'Error setting as deleted'
            ]);
        }
    }
}
