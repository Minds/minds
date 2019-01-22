<?php
/**
 * Spam Block Model
 */
namespace Minds\Core\Security\SpamBlocks;

use Minds\Traits\MagicAttributes;

class SpamBlock
{

    use MagicAttributes;

    /** @var $key */
    private $key;

    /** @var $value */
    private $value;

}