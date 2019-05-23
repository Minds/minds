<?php

namespace Spec\Minds\Core\Security;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core;
use Minds\Entities\User;
use Minds\Entities\Entity;
use Minds\Entities\Object;

class ACLSpec extends ObjectBehavior
{
    /** @var Core\Security\RateLimits\Manager */
    private $rateLimits;

    function let(Core\Security\RateLimits\Manager $rateLimits) {
        $this->rateLimits = $rateLimits;

        $this->beConstructedWith($rateLimits);
    }

    public function mock_session($on = true)
    {
        if ($on) {
            $user = new User;
            $user->guid = 123;
            $user->username = 'minds';
        } else {
            $user = null;
        }
        Core\Session::setUser($user);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Security\ACL');
    }

    public function it_should_allow_read_of_public_entities(Entity $entity)
    {
        $entity->get('access_id')->willReturn(2);
        $entity->get('container_guid')->willReturn(123);
        $entity->get('owner_guid')->willReturn(123);
        $entity->get('type')->willReturn('activity');
        $this->read($entity)->shouldReturn(true);
    }

    public function it_should_not_allow_read_of_private_entities(Entity $entity)
    {
        $entity->getType()->willReturn('specy');
        $entity->get('access_id')->willReturn(0);
        $entity->get('owner_guid')->willReturn(123);
        $entity->get('type')->willReturn('activity');
        $this->read($entity)->shouldReturn(false);
    }

    public function it_should_trigger_acl_read_event(Object $entity)
    {
        $this->mock_session(true);

        Core\Events\Dispatcher::register('acl:read', 'all', function ($event) {
        $event->setResponse(true);
            });

        $this->read($entity)->shouldReturn(true);
        $this->mock_session(false);
    }

    public function it_should_not_allow_write_for_logged_out_users(Entity $entity)
    {
        $this->write($entity)->shouldReturn(false);
    }

    public function it_should_not_allow_write_for_none_owned_entities(Entity $entity)
    {
        $this->mock_session(true);

        $this->write($entity)->shouldReturn(false);
        $this->mock_session(false);
    }

    public function it_should_allow_write_for_own_entities(Entity $entity)
    {
        $this->mock_session(true);

        $entity->get('owner_guid')
            ->willReturn(123);
        
        $entity->get('container_guid')
            ->willReturn(123);

        $this->write($entity)->shouldReturn(true);
        $this->mock_session(false);
    }

    public function it_should_trigger_acl_write_event(Object $entity)
    {
        $this->mock_session(true);

        Core\Events\Dispatcher::register('acl:write', 'all', function ($event) {
        $event->setResponse(true);
      });

        $this->read($entity)->shouldReturn(true);
        $this->mock_session(false);
    }

    public function it_should_not_allow_interaction_for_logged_out_users(Entity $entity)
    {
        $this->interact($entity)->shouldReturn(false);
    }

    public function it_should_allow_interaction(Entity $entity)
    {
        $this->mock_session(true);

        $this->rateLimits->setUser(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->rateLimits);

        $this->rateLimits->setEntity($entity)
            ->shouldBeCalled()
            ->willReturn($this->rateLimits);

        $this->rateLimits->setInteraction(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->rateLimits);

        $this->rateLimits->isLimited()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->interact($entity)->shouldReturn(true);
        $this->mock_session(false);
    }

    public function it_should_return_false_on_acl_interact_event(Object $entity)
    {
        $this->mock_session(true);

        $this->rateLimits->setUser(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->rateLimits);

        $this->rateLimits->setEntity($entity)
            ->shouldBeCalled()
            ->willReturn($this->rateLimits);

        $this->rateLimits->setInteraction(Argument::any())
            ->shouldBeCalled()
            ->willReturn($this->rateLimits);

        $this->rateLimits->isLimited()
            ->shouldBeCalled()
            ->willReturn(false);

        Core\Events\Dispatcher::register('acl:interact', 'all', function ($event) {
        $event->setResponse(false);
      });

        $this->interact($entity)->shouldReturn(false);
        $this->mock_session(false);
    }

    public function it_should_ignore(Entity $entity)
    {
        $this->setIgnore(true);
        $this->read($entity)->shouldReturn(true);
        $this->write($entity)->shouldReturn(true);
        $this->setIgnore(false);
    }
}
