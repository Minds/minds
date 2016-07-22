<?php
namespace Minds\Core\Boost;

use Minds\Interfaces\BoostHandlerInterface;
use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Helpers;

/**
 * Content Boost handler
 */
class Content extends Network implements BoostHandlerInterface
{
    protected $handler = 'content';
}
