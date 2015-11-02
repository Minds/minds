<?php

namespace Spec\Minds\Entities\Boost;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Data\Call;
use Minds\Entities\Entity;
use Minds\Entities\User;

class PeerSpec extends ObjectBehavior
{
    private $mockData = [];

    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Entities\Boost\Peer');
    }

    public function let()
    {
        $this->mockData = [
          'guid' => "mockguid",
          'type' => 'points',
          'entity' => json_encode(['guid'=>'mock_entity_guid', 'type'=>'activity']),
          'bid' => 10,
          'destination' => json_encode(['guid'=>'mock_destination_guid', 'type'=>'user']),
          'owner' => json_encode(['guid'=>'mock_owner_guid', 'type'=>'user']),
          'state' => 'testing',
          'transactionId' => null,
          'time_created' => time(),
          'last_updated' => time()
        ];
    }

    public function it_should_load_from_array()
    {
        $this->loadFromArray($this->mockData)->shouldReturn($this);
        $this->getBid()->shouldReturn(10);
        $this->getState()->shouldReturn('testing');
    }

  //function it_should_should_save(Call $db){
    //$db->insert(Argument::type('string'), Argument::type('array'))->willReturn(true);
    //$this->beConstructedWith($db);
    //$this->save()->willReturn('foo');
  //}

    public function it_should_set_the_entity(Entity $entity)
    {
        $this->setEntity($entity)->shouldReturn($this);
        $this->getEntity()->shouldReturn($entity);
    }

    public function it_should_set_the_destination(User $user)
    {
        $this->setDestination($user)->shouldReturn($this);
        $this->getDestination()->shouldReturn($user);
    }

    public function it_should_set_the_owner(User $user)
    {
        $this->setOwner($user)->shouldReturn($this);
        $this->getOwner()->shouldReturn($user);
    }

    public function it_should_set_the_bid()
    {
        $this->setBid(100)->shouldReturn($this);
        $this->getBid()->shouldReturn(100);
    }

    public function it_should_set_the_state()
    {
        $this->setState('testing-set-state')->shouldReturn($this);
        $this->getState()->shouldReturn('testing-set-state');
    }
}
