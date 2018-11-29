<?php
/**
 * Helpdesk vote entity
 */
namespace Minds\Core\Helpdesk\Question\Votes;

use Minds\Api\Factory;
use Minds\Traits\MagicAttributes;

class Vote
{
    use MagicAttributes;

    /** @var string $questionUuid */
    protected $questionUuid;

    /** @var int $userGuid */
    protected $userGuid;

    /** @var string $direction */
    protected $direction;

}