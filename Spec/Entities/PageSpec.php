<?php

namespace Spec\Minds\Entities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Call;

class PageSpec extends ObjectBehavior
{

    function let(Call $db){
        $this->beConstructedWith($db);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Entities\Page');
    }

    function it_should_set_title(){
        $this->setTitle('hello world')->shouldReturn($this);
        $this->getTitle()->shouldReturn('hello world');
    }

    function it_should_set_body(){
        $this->setBody('hello body')->shouldReturn($this);
        $this->getBody()->shouldReturn('hello body');
    }

    function it_should_set_path(){
        $this->setPath('/foo')->shouldReturn($this);
        $this->getPath()->shouldReturn('/foo');
    }

    function it_should_should_save(Call $db){
        $db->insert(Argument::type('string'), Argument::any())->willReturn($this->getGuid());
        $this->save()->shouldReturn($this);
    }

    function it_should_throw_an_exception_on_failed_save(Call $db){
        $db->insert(Argument::type('string'), Argument::any())->willReturn(false);
        $this->shouldThrow('\Exception')->during('save');
    }
}
