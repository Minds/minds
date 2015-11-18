<?php

namespace Spec\Minds\Core\Notification;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Events\Dispatcher;
use Minds\Entities\User;
use Minds\Entities\Notification;
use Minds\Entities\Entity;

class EventsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Notification\Events');
    }

    public function it_should_return_an_array_of_notifications_when_handling_event(
        User $from_user,
        User $to_user_a,
        User $to_user_b,
        Entity $entity
    ) {
        $this::registerEvents();

        expect(Dispatcher::trigger('notification', 'mock', [
            'to' => [ $to_user_a->guid, $to_user_b->guid ],
            'entity' => $entity,
            'notification_view' => 'mock_test',
            'description' => 'I am a mock',
            'params' => [ 'message' => 'I am foobar' ],
            'dry' => true
        ]))->shouldReturnArrayOfNotifications();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfNotifications' => function ($array) {

                if (!is_array($array)) {
                    return false;
                }

                foreach ($array as $item) {
                    if (!($item instanceof Notification)) {
                        return false;
                    }
                }

                return true;
            }
        ];
    }
}
