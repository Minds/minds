<?php
/**
 * BoostGuidResolverDelegate.
 *
 * @author emi
 */

namespace Minds\Core\Entities\Delegates;

use Minds\Common\Urn;
use Minds\Core\Boost\Repository;
use Minds\Core\Di\Di;
use Minds\Core\EntitiesBuilder;
use Minds\Entities\Boost\BoostEntityInterface;

class BoostGuidResolverDelegate implements ResolverDelegate
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * BoostGuidResolverDelegate constructor.
     * @param Repository $repository
     */
    public function __construct($repository = null)
    {
        $this->repository = $repository ?: Di::_()->get('Boost\Repository');
    }

    /**
     * @param Urn $urn
     * @return boolean
     */
    public function shouldResolve(Urn $urn)
    {
        return $urn->getNid() === 'boost';
    }

    /**
     * @param array $urns
     * @param array $opts
     * @return mixed
     */
    public function resolve(array $urns, array $opts = [])
    {
        $meta = array_map(function (Urn $urn) {
            return explode(':', $urn->getNss(), 2);
        }, $urns);

        $entities = [];

        foreach ($meta as list($type, $guid)) {
            /** @var BoostEntityInterface $boost */
            $boost = $this->repository->getEntity($type, $guid);

            $entities[] = $boost;
        }

        return $entities;
    }

    /**
     * @param BoostEntityInterface $entity
     * @return mixed
     */
    public function map($urn, $entity)
    {
        $boostedEntity = $entity->getEntity();

        if ($boostedEntity) {
            $boostedEntity->boosted = true;
            $boostedEntity->boosted_guid = $entity->getGuid();
            $boostedEntity->urn = $urn;
        }

        return $boostedEntity;
    }

    /**
     * @param BoostEntityInterface $entity
     * @return string|null
     */
    public function asUrn($entity)
    {
        if (!$entity) {
            return null;
        }

        return "urn:boost:{$entity->getHandler()}:{$entity->getGuid()}";
    }
}
