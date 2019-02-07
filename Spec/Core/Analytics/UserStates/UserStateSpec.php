<?php

namespace Spec\Minds\Core\Analytics\UserStates;

use Minds\Core\Analytics\UserStates\UserState;
use PhpSpec\ObjectBehavior;

class UserStateSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(UserState::class);
    }

    public function it_should_export()
    {
        $this->setUserGuid(934155581860614163)
            ->setReferenceDateMs(1549497600)
            ->setState('cold')
            ->setPreviousState('curious')
            ->setActivityPercentage(0.14);
        $export = $this->export();
        $export->shouldBeArray();
        $export['user_guid']->shouldEqual($this->getUserGuid());
        $export['reference_date']->shouldEqual($this->getReferenceDateMs());
        $export['state']->shouldEqual($this->getState());
        $export['previous_state']->shouldEqual($this->getPreviousState());
        $export['activity_percentage']->shouldEqual($this->getActivityPercentage());
    }
}
