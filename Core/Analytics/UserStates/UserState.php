<?php
/**
 * User states.
 */

namespace Minds\Core\Analytics\UserStates;

use Minds\Traits\MagicAttributes;

class UserState
{
    use MagicAttributes;

    /** @var long $userGuid */
    private $userGuid;

    /** @var long $referenceDateMs */
    private $referenceDateMs;

    /** @var string $state */
    private $state;

    /** @var string $previousState */
    private $previousState;

    /** @var float $activityPercentage */
    private $activityPercentage;

    public function export()
    {
        return [
            'user_guid' => $this->userGuid,
            'reference_date' => $this->referenceDateMs,
            'state' => $this->state,
            'previous_state' => $this->previousState,
            'activity_percentage' => $this->activityPercentage,
        ];
    }
}
