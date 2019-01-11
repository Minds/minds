<?php

namespace Minds\Core\Boost;

use Minds\Interfaces\BoostHandlerInterface;
use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Helpers;

/**
 * Newsfeed Boost handler
 */
class Newsfeed extends Network implements BoostHandlerInterface
{
    protected $handler = 'newsfeed';

    /**
     * @param mixed $entity
     * @return bool
     */
    public static function validateEntity($entity)
    {
        if (!$entity || !is_object($entity)) {
            return false;
        }

        return
            $entity instanceof Entities\Activity ||
            $entity instanceof Entities\Video ||
            $entity instanceof Entities\Image ||
            $entity instanceof Core\Blogs\Blog;
    }
}
