<?php
/**
 * Experiments manager
 */
namespace Minds\Core\Experiments;

use Minds\Interfaces\ModuleInterface;
use Minds\Traits\MagicAttributes;

class Bucket
{
    use MagicAttributes;

    /** @param string $id */
    private $id;

    /** @param int $weight */
    private $weight = 0;

}