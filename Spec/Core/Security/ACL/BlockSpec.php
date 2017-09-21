<?php

namespace Spec\Minds\Core\Security\ACL;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;
use Minds\Core\Data\Call as Call;
use Minds\Core\Data\Call as DataPool;
use Minds\Entities\Entity;

class BlockSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Security\ACL\Block');
    }

    //function it_should_listen_to_interact_acl_event(Entity $entity){
      //$this->
    //}

    public function it_should_return_if_blocked(Call $db)
    {
        $db->beConstructedWith(['entities_by_time']);
        $db->getRow("acl:blocked:foo", Argument::any())->willReturn(array(
        "bar" => time()
      ));

        $this->beConstructedWith($db);
        $this->isBlocked("bar", "foo")->shouldReturn(true);
        $this->isBlocked("boo", "foo")->shouldReturn(false);
    }

    public function it_should_add_a_user_to_the_list(Call $db)
    {
        $db->beConstructedWith(['entities_by_time']);
        $db->insert("acl:blocked:foo", Argument::type('array'))->willReturn("bar");

        $this->beConstructedWith($db);
        $this->block("bar", "foo")->shouldReturn("bar");
    }

    public function it_should_remove_a_user_from_the_list(Call $db)
    {
        $db->beConstructedWith(['entities_by_time']);
        $db->removeAttributes("acl:blocked:foo", array("bar"))->willReturn(true);

        $this->beConstructedWith($db);
        $this->unBlock("bar", "foo")->shouldReturn(true);
    }
}
