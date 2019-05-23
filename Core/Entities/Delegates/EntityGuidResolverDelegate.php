<?php
/**
 * GuidResolverDelegate.
 *
 * @author emi
 */

namespace Minds\Core\Entities\Delegates;

use Minds\Common\Urn;
use Minds\Core\Di\Di;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Feeds\Top\Entities as TopEntities;

class EntityGuidResolverDelegate implements ResolverDelegate
{
    /**
     * @var EntitiesBuilder
     */
    protected $entitiesBuilder;

    /**
     * EntityGuidResolverDelegate constructor.
     * @param EntitiesBuilder $entitiesBuilder
     */
    public function __construct($entitiesBuilder = null)
    {
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
    }

    /**
     * @param Urn $urn
     * @return boolean
     */
    public function shouldResolve(Urn $urn)
    {
        return $urn->getNid() === 'entity' || $urn->getNid() === 'activity' || $urn->getNid() === 'user';
    }

    /**
     * @param array $urns
     * @param array $opts
     * @return mixed
     */
    public function resolve(array $urns, array $opts = [])
    {
        $opts = array_merge([
            'asActivities' => false,
        ], $opts);

        if (!$urns) {
            return [];
        }

        $guids = array_map(function (Urn $urn) {
            return $urn->getNss();
        }, $urns);

        $entities = $this->entitiesBuilder->get(['guids' => $guids]);

        // Map, if faux-Activities are needed
        if ($opts['asActivities']) {
            /** @var TopEntities $entities */
            $topEntities = new TopEntities();

            // Cast to ephemeral Activity entities, if another type
            $entities = array_map([$topEntities, 'cast'], $entities);
        }

        return $entities;
    }

    /**
     * @param mixed $entity
     * @return mixed
     */
    public function map($urn, $entity)
    {
        // NOTE: No need to attach URN as GUID fallback defaults to this delegate
        return $entity;
    }

    /**
     * @param mixed $entity
     * @return string|null
     */
    public function asUrn($entity)
    {
        if (!$entity) {
            return null;
        }

        if ($entity->getUrn()) {
            return $entity->getUrn();
        }

        if (method_exists($entity, '_magicAttributes') || method_exists($entity, 'getGuid')) {
            return "urn:entity:{$entity->getGuid()}";
        } elseif (isset($entity->guid)) {
            return "urn:entity:{$entity->guid}";
        }

        return null;
    }
}
