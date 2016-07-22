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
}
