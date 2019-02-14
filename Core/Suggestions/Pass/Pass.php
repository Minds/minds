<?php
/**
 * Pass model
 */
namespace Minds\Core\Suggestions\Pass;

use Minds\Traits\MagicAttributes;

class Pass
{
    use MagicAttributes;

    /** @var int $suggestedGuid */
    protected $suggestedGuid;

    /** @var int $userGuid */
    protected $userGuid;

}
