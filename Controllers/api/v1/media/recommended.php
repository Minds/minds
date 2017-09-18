<?php
/**
 * Minds Media Recommended API
 *
 * @version 1
 * @author Emi Balbuena
 */
namespace Minds\Controllers\api\v1\media;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Entities;
use Minds\Helpers;
use Minds\Interfaces;
use Minds\Api\Factory;

class recommended implements Interfaces\Api
{

    /**
     * Return the media items
     * @param array $pages
     *
     * API:: /v1/media/recommended/:type/:guid
     */
    public function get($pages)
    {
        $recommended = Di::_()->get('Media\Recommended');

        $type = $pages[0];
        $user = $pages[1];
        $current = isset($_GET['current']) ? $_GET['current'] : null;
        $next = isset($_GET['next']) ? $_GET['next'] : null;
        $limit = $_GET['limit'] ? (int) $_GET['limit'] : 12;

        $exclude = [];

        if ($current) {
            $exclude[] = $current;
        }

        $entities = [];

        // Get the next entity
        if ($next) {
            $entities[] = Entities\Factory::build($next);
        }

        // Calculate free slots
        $slots = $limit - count($entities);

        // Get from same user by type
        if ($slots > 0 && $type && $user) {
            $media = $recommended->getByOwner($slots, $user, $type);

            if ($media) {
                $entities = array_merge($entities, $media);
            }
        }

        // Cleanup and filter
        $this->_entitiesUnique($entities, $exclude);

        // Calculate free slots
        $slots = $limit - count($entities);

        // Get from same user (other type)
        if ($slots > 0 && $type && $user) {
            $otherType = $type == 'image' ? 'video' : 'image';

            $media = $recommended->getByOwner($slots, $user, $otherType);

            if ($media) {
                $entities = array_merge($entities, $media);
            }
        }

        // Cleanup and filter
        $this->_entitiesUnique($entities, $exclude);

        // Calculate free slots
        $slots = $limit - count($entities);

        // Get from featured type
        if ($slots > 0 && $type) {
            $media = $recommended->getFeatured($slots, $type);

            if ($media) {
                $entities = array_merge($entities, $media);
            }
        }

        // Cleanup and filter
        $this->_entitiesUnique($entities, $exclude);

        // Calculate free slots
        $slots = $limit - count($entities);

        // Get from featured (with extra ensure no slots left free)
        if ($slots > 0) {
            $media = $recommended->getFeatured($slots + $limit);

            if ($media) {
                $entities = array_merge($entities, $media);
            }
        }

        // Cleanup and filter
        $this->_entitiesUnique($entities, $exclude);

        // Trim to correct length
        $entities = array_slice($entities, 0, $limit);

        return Factory::response([
            'entities' => Factory::exportable($entities)
        ]);
    }

    /**
     * POST Method
     */
    public function post($pages)
    {
        return Factory::response([]);
    }

    /**
     * PUT Method
     */
    public function put($pages)
    {
        return Factory::response([]);
    }

    /**
     * DELETE Method
     */
    public function delete($pages)
    {
        return Factory::response([]);
    }

    /**
     * Filter entities
     */
     private function _entitiesUnique(&$entities, $exclude)
    {
        $guids = [];
        $entities = array_filter($entities, function ($entity) use (&$guids, $exclude) {
            if (in_array($entity->guid, $guids)) {
                return false;
            }

            if (in_array($entity->guid, $exclude)) {
                return false;
            }

            $guids[] = $entity->guid;
            return true;
        });
    }
}
