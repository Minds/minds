<?php

namespace Spec\Minds\Entities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Entities\Entity;
use Minds\Entities\User;

class NotificationSpec extends ObjectBehavior
{

    private $mockData;
    private $mockUserA;
    private $mockUserB;

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Entities\Notification');
    }

    function let(User $user_a, User $user_b, Entity $entity)
    {

        $this->mockData = [
            'guid' => -99999,
            'to' => $user_a,
            'entity' => $entity,
            'from' => $user_b,
            'notification_view' => 'mock_test',
            'description' => 'Mock',
            'read' => 0,
            'access_id' => 2,
            'owner' => $user_a,
            'params' => [ 'message' => 'Message' ]
        ];

    }

    public function it_should_load_from_array()
    {

        $this->loadFromArray($this->mockData)->shouldReturn($this);

        $this->getType()->shouldReturn('notification');
        $this->getGuid()->shouldReturn(-99999);
        $this->getTo()->shouldReturnAnInstanceOf('\\Minds\\Entities\\User');
        $this->getEntity()->shouldReturnAnInstanceOf('\\Minds\\Entities\\Entity');
        $this->getFrom()->shouldReturnAnInstanceOf('\\Minds\\Entities\\User');
        $this->getNotificationView()->shouldReturn('mock_test');
        $this->getDescription()->shouldReturn('Mock');
        $this->getRead()->shouldReturn(0);
        $this->getAccessId()->shouldReturn(2);
        $this->getOwner()->shouldReturnAnInstanceOf('\\Minds\\Entities\\User');
        $this->getParams()->shouldBeArray();

    }

    public function it_should_set_properties(User $user_a, User $user_b, Entity $entity)
    {

        $this->setType('notification')->getType()->shouldReturn('notification');
        $this->setGuid(-123456)->getGuid()->shouldReturn(-123456);
        $this->setTo($user_a)->getTo()->shouldReturnAnInstanceOf('\\Minds\\Entities\\User');
        $this->setEntity($entity)->getEntity()->shouldReturnAnInstanceOf('\\Minds\\Entities\\Entity');
        $this->setFrom($user_b)->getFrom()->shouldReturnAnInstanceOf('\\Minds\\Entities\\User');
        $this->setNotificationView('another_mock_test')->getNotificationView()->shouldReturn('another_mock_test');
        $this->setDescription('Mock II')->getDescription()->shouldReturn('Mock II');
        $this->setRead(1)->getRead()->shouldReturn(1);
        $this->setAccessId(-2)->getAccessId()->shouldReturn(-2);
        $this->setOwner($user_a)->getOwner()->shouldReturnAnInstanceOf('\\Minds\\Entities\\User');
        $this->setParams([ 'message' => 'foo', 'action' => 'bar'])->getParams()->shouldBeArray();

    }

}

