<?php

namespace Spec\Minds\Entities\Boost;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Data\Call;
use Minds\Entities\Entity;
use Minds\Entities\User;

class NetworkSpec extends ObjectBehavior
{
    private $mockData = [];

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Entities\Boost\Network');
    }

    public function let()
    {
        $this->mockData = [
          'guid' => "mockguid",
          '_id' => 'abcj1k203',
          'type' => 'points',
          'entity' => json_encode(['guid'=>'mock_entity_guid', 'type'=>'activity']),
          'bid' => 10,
          'destination' => json_encode(['guid'=>'mock_destination_guid', 'type'=>'user']),
          'owner' => json_encode(['guid'=>'mock_owner_guid', 'type'=>'user']),
          'state' => 'testing',
          'transactionId' => null,
          'handler' => 'newsfeed',
          'time_created' => time(),
          'last_updated' => time()
        ];
    }

    public function it_should_load_from_array()
    {
        $this->loadFromArray($this->mockData)->shouldReturn($this);
        $this->getBid()->shouldReturn(10);
        $this->getState()->shouldReturn('testing');
        $this->getId()->shouldReturn('abcj1k203');
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

    public function it_should_set_the_handler()
    {
        $this->setHandler('newsfeed')->shouldReturn($this);
        $this->getHandler()->shouldReturn('newsfeed');
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
