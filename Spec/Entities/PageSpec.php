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

    function it_should_load_from_array(){
        $this->loadFromArray([
            'title' => "spec tested",
            'body' => "spec body",
            'path' => "spec"
            ])->shouldReturn($this);
        $this->getTitle()->shouldReturn("spec tested");
        $this->getBody()->shouldReturn("spec body");
        $this->getPath()->shouldReturn("spec");
    }

    function it_should_load_from_json(){
        $this->loadFromArray(json_encode([
            'title' => "spec tested (json)",
            'body' => "spec body (json)",
            'path' => "spec-json"
            ]))->shouldReturn($this);
        $this->getTitle()->shouldReturn("spec tested (json)");
        $this->getBody()->shouldReturn("spec body (json)");
        $this->getPath()->shouldReturn("spec-json");
    }

    function it_should_should_save(Call $db){
        $db->insert(Argument::type('string'), Argument::any())->willReturn($this->getGuid());
        $this->save()->shouldReturn($this);
    }

    function it_should_throw_an_exception_on_failed_save(Call $db){
        $db->insert(Argument::type('string'), Argument::any())->willReturn(false);
        $this->shouldThrow('\Exception')->during('save');
    }

    function it_should_export(){
        $this->export()->shouldBeArray();
    }
}
