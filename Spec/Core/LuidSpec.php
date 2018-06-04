<?php

namespace Spec\Minds\Core;

use Minds\Core\Luid;
use Minds\Exceptions\InvalidLuidException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LuidSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Luid');
    }

    function it_should_set_get_and_delete_components()
    {
        $this
            ->set('phpspec1', 'test1')
            ->shouldReturn($this);

        $this
            ->set('phpspec2', 'test2')
            ->shouldReturn($this);

        $this
            ->set('phpspec3', 'test3')
            ->shouldReturn($this);

        $this
            ->delete('phpspec3')
            ->shouldReturn($this);

        $this
            ->get('phpspec1')
            ->shouldReturn('test1');

        $this
            ->get('phpspec2')
            ->shouldReturn('test2');

        $this
            ->get('phpspec3')
            ->shouldReturn(null);

        $this
            ->get('phpspec4')
            ->shouldReturn(null);
    }

    function it_should_parse()
    {
        $encoded = base64_encode(json_encode([
            '_type' => 'test',
            'phpspec1' => '1',
            'phpspec2' => '2',
            'phpspec3' => '3',
        ]));

        $this->parse($encoded);

        $this
            ->getType()
            ->shouldReturn('test');

        $this
            ->get('phpspec1')
            ->shouldReturn('1');

        $this
            ->get('phpspec2')
            ->shouldReturn('2');

        $this
            ->get('phpspec3')
            ->shouldReturn('3');
    }

    function it_should_throw_during_parse_if_value_is_not_valid()
    {
        $this
            ->shouldThrow(InvalidLuidException::class)
            ->duringParse(null);

        $this
            ->shouldThrow(InvalidLuidException::class)
            ->duringParse(true);

        $this
            ->shouldThrow(InvalidLuidException::class)
            ->duringParse('å∫´≤ñ…');

        $this
            ->shouldThrow(InvalidLuidException::class)
            ->duringParse(base64_encode('{invalidJSON'));

        $this
            ->shouldThrow(InvalidLuidException::class)
            ->duringParse(base64_encode('"a JSON string"'));

        $this
            ->shouldThrow(InvalidLuidException::class)
            ->duringParse(base64_encode('{ notype: true }'));
    }

    function it_should_build()
    {
        $this->set('phpspec2', 2);
        $this->set('phpspec1', 1);
        $this->set('phpspec3', 3);
        $this->setType('test');

        $encoded = base64_encode(json_encode([
            '_type' => 'test',
            'phpspec1' => "1",
            'phpspec2' => "2",
            'phpspec3' => "3",
        ]));


        $this
            ->build()
            ->shouldReturn($encoded);
    }

    function it_should_throw_during_build_if_no_components()
    {
        $this
            ->shouldThrow(new \Exception('No components'))
            ->duringBuild();
    }

    function it_should_throw_during_build_if_no_type_component()
    {
        $this->set('phpspec', 1);

        $this
            ->shouldThrow(new \Exception('No type specified'))
            ->duringBuild();
    }

    function it_should_initialize_with_a_string_value()
    {
        $encoded = base64_encode(json_encode([
            '_type' => 'test',
            'phpspec' => "1"
        ]));

        $this->beConstructedWith($encoded);

        $this
            ->build()
            ->shouldReturn($encoded);
    }

    function it_should_initialize_with_a_luid_instance()
    {
        $originalLuid = new Luid();
        $originalLuid->setType('test');
        $originalLuid->set('phpspec', 1);

        $encoded = $originalLuid->build();

        $this->beConstructedWith($originalLuid);

        $this
            ->build()
            ->shouldReturn($encoded);
    }

    function it_should_build_when_casting_to_string()
    {
        $this->setType('test');
        $this->set('phpspec', '1');

        $encoded = base64_encode(json_encode([
            '_type' => 'test',
            'phpspec' => '1'
        ]));

        $this
            ->__toString()
            ->shouldReturn($encoded);
    }

    function it_should_return_an_empty_string_if_exception_when_casting_to_string()
    {
        $this
            ->__toString()
            ->shouldReturn('');
    }

    function it_should_build_when_serializing_to_json()
    {
        $this->setType('test');
        $this->set('phpspec', '1');

        $encoded = base64_encode(json_encode([
            '_type' => 'test',
            'phpspec' => '1'
        ]));

        $this
            ->jsonSerialize()
            ->shouldReturn($encoded);
    }
}
