<?php
/**
 * Entities
 *
 * @author: Emiliano Balbuena <edgebal>
 */

namespace Minds\Core\Feeds\Top;

use Minds\Core\Blogs\Blog;
use Minds\Core\Di\Di;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Security\ACL;
use Minds\Entities\Activity;
use Minds\Entities\Group;
use Minds\Entities\Image;
use Minds\Entities\User;
use Minds\Entities\Video;

class Entities
{
    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    /** @var ACL */
    protected $acl;

    /** @var User */
    protected $actor = null;

    /**
     * Entities constructor.
     * @param EntitiesBuilder $entitiesBuilder
     * @param ACL $acl
     */
    public function __construct(
        $entitiesBuilder = null, $acl = null
    )
    {
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
        $this->acl = $acl ?: ACL::_();
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setActor(User $user = null)
    {
        $this->actor = $user;
        return $this;
    }

    /**
     * @param mixed $entity
     * @return bool
     */
    public function filter($entity)
    {
        return $this->acl->read($entity, $this->actor ?: null);
    }

    /**
     * @param mixed $entity
     * @return Activity
     * @throws \Exception
     */
    public function cast($entity)
    {
        if (is_string($entity) || is_numeric($entity)) {
            $entity = $this->entitiesBuilder->single($entity);
        } elseif (is_array($entity)) {
            $entity = $this->entitiesBuilder->build($entity);
        }

        if ($entity instanceof Activity || $entity instanceof User || $entity instanceof Group) {
            return $entity;
        }

        $fields = [
            'guid',
            'time_created',
            'owner_guid',
            'container_guid',
            'access_id',
            'time_updated',
            'mature',
            'spam',
            'deleted',
            'paywall',
            'edited',
            'comments_enabled',
            'wire_threshold',
            'rating',
            'impressions',
            'thumbs:up:user_guids',
            'thumbs:up:count',
            'thumbs:down:user_guids',
            'thumbs:down:count',
            'nsfw',
        ];

        $activity = new Activity();
        $activity->setEphemeral(true)
            ->setHideImpressions(true);

        if ($entity instanceof Blog) {
            // New entities
            $fromExport = $entity->export();

            $activity->set('message', implode(' ', array_map(function($tag) { return "#$tag"; }, $entity->getTags())));

            foreach ($fields as $field) {
                if (isset($fromExport[$field])) {
                    $activity->set($field, $fromExport[$field]);
                }
            }
        } else {
            // Legacy entity getter
            foreach ($fields as $field) {
                $activity->set($field, $entity->{$field});
            }
        }

        if ($entity instanceof Image || $entity instanceof Video) {
            // Images, Videos
            $activity
                ->setFromEntity($entity)
                ->setTitle($entity->title)
                ->setBlurb($entity->description)
                ->setMature($entity->getFlag('mature'))
                ->setCustom(...$entity->getActivityParameters());
        } elseif ($entity instanceof Blog) {
            $activity
                ->setFromEntity($entity)
                ->setTitle($entity->getTitle())
                ->setBlurb(strip_tags($entity->getBody()))
                ->setURL($entity->getURL())
                ->setThumbnail($entity->getIconUrl())
                ->setMature($entity->isMature());
        }

        return $activity;
    }
}
