<?php

namespace Spec\Minds\Core\Security\ACL;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;
use Mockery;
use Minds\Core\Data\Cassandra\Thrift\Indexes as Call;
use Minds\Core\Data\Call as DataPool;
use Minds\Core\Data\Cassandra\Client as Cql;
use Minds\Entities\Entity;

class BlockSpec extends ObjectBehavior
{
    public function it_is_initializable(Call $db, Cql $cql)
    {
        $this->beConstructedWith($db, $cql, null, Argument::any());
        $this->shouldHaveType('Minds\Core\Security\ACL\Block');
    }

    //function it_should_listen_to_interact_acl_event(Entity $entity){
      //$this->
    //}

    public function it_should_return_if_blocked(Call $db, Cql $cql)
    {
        $cqlTypes = Mockery::mock('alias:Cassandra\Types');
        $cqlTypes->shouldReceive('collection')->andReturn($cqlTypes);
        $cqlTypes->shouldReceive('text')->andReturn();
        $cqlTypes->shouldReceive('collection->create')->andReturn([]);

        $cql->request(Argument::type('\Minds\Core\Data\Cassandra\Prepared\Custom'))->willReturn([
          [
            'key' => 'foo',
            'column1' => 'bar',
            'value' => time()
          ]
        ]);

        $this->beConstructedWith($db, $cql, null, $cqlTypes);
        $this->isBlocked("bar", "foo")->shouldReturn(true);
        $this->isBlocked("boo", "foo")->shouldReturn(false);
    }

    public function it_should_add_a_user_to_the_list(Call $db, Cql $cql)
    {
        $db->insert("acl:blocked:foo", Argument::type('array'))->willReturn("bar");

        $this->beConstructedWith($db, $cql, null, Argument::any());
        $this->block("bar", "foo")->shouldReturn("bar");
    }

    public function it_should_remove_a_user_from_the_list(Call $db, Cql $cql)
    {
        $db->remove("acl:blocked:foo", array("bar"))->willReturn(true);

        $this->beConstructedWith($db, $cql, null, Argument::any());
        $this->unBlock("bar", "foo")->shouldReturn(true);
    }
}
