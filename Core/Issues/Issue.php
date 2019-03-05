<?php
/**
 * Minds Issue
 */
namespace Minds\Core\Issues;

use Minds\Traits\MagicAttributes;

class Issue
{
    use MagicAttributes;

    /** @var string $title */
    private $title;

    /** @var int $description */
    private $description;

    /** @var int $labels */
    private $labels;
}
