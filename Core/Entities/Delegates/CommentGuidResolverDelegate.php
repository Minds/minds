<?php
/**
 * @author: Marcelo
 */

namespace Minds\Core\Entities\Delegates;

use Minds\Common\Urn;
use Minds\Core\Boost\Repository;
use Minds\Core\Comments\Comment;
use Minds\Core\Comments\Manager;
use Minds\Core\Di\Di;
use Minds\Core\EntitiesBuilder;
use Minds\Entities\Boost\BoostEntityInterface;

class CommentGuidResolverDelegate implements ResolverDelegate
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * CommentGuidResolverDelegate constructor.
     * @param Manager $manager
     */
    public function __construct($manager = null)
    {
        $this->manager = $manager ?: new Manager();
    }

    /**
     * @param Urn $urn
     * @return boolean
     */
    public function shouldResolve(Urn $urn)
    {
        return $urn->getNid() === 'comment';
    }

    /**
     * @param array $urns
     * @param array $opts
     * @return mixed
     */
    public function resolve(array $urns, array $opts = [])
    {
        $entities = [];

        foreach ($urns as $urn) {
            /** @var Comment $comment */
            $comment = $this->manager->getByUrn($urn);

            $entities[] = $comment;
        }

        return $entities;
    }

    /**
     * @param $urn
     * @param Comment $entity
     * @return mixed
     */
    public function map($urn, $entity)
    {
        return $entity;
    }

    /**
     * @param Comment $entity
     * @return string|null
     */
    public function asUrn($entity)
    {
        if (!$entity) {
            return null;
        }

        return $entity->getUrn();
    }
}