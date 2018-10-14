<?php

namespace Minds\Core\Helpdesk\Entities;

use Minds\Traits\MagicAttributes;

/**
 * Class Category
 * @package Minds\Core\Helpdesk\Entities
 * @method string getUuid()
 * @method Category setUuid(string $value)
 * @method string getTitle()
 * @method Category setTitle(string $value)
 * @method string getParentUuid()
 * @method Category setParentUuid(string $value)
 * @method Category getParent()
 * @method Category setParent(Category $value)
 * @method string getBranch()
 * @method Category setBranch(string $value)
 */
class Category
{
    use MagicAttributes;

    protected $uuid;
    protected $title;
    protected $parent_uuid;
    /** @var Category */
    protected $parent;
    protected $branch;
}