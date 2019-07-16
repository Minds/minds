<?php

namespace Minds\Core\Permissions;

use Minds\Traits\MagicAttributes;

/** 
* Class Permissions
* @method Permissions setAllowComments(bool $allowComments)
* @method bool getAllowComments();
*/
class Permissions {
    use MagicAttributes;

    /** @var bool AllowComments */
    private $allowComments = true;
}
