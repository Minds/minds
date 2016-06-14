<?php

namespace Spec\Minds\Core\Sockets;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Sockets\Binary;
use Minds\Core\Sockets\MsgPack;
use Minds\Core\Sockets\Events as OriginalEventsClass;
use Minds\Core\Data\PubSub\Redis\Client as RedisPubSubClient;

class EventsSpec extends ObjectBehavior
{
    function it_is_initializable(RedisPubSubClient $redis)
    {
        $this->beConstructedWith($redis);
        $this->shouldHaveType('Minds\Core\Sockets\Events');
    }

    function it_should_not_emit_if_no_params(RedisPubSubClient $redis, MsgPack $msgpack)
    {
        $this->beConstructedWith($redis, $msgpack);
        $this->shouldThrow('\\Exception')->duringEmit();
    }

    function it_should_get_flag(RedisPubSubClient $redis, MsgPack $msgpack)
    {
        $this->beConstructedWith($redis, $msgpack);

        $this->setFlag('phpspec', true);
        $this->getFlag('phpspec')->shouldReturn(true);
    }

    function it_should_emit(RedisPubSubClient $redis, MsgPack $msgpack)
    {
        $this->beConstructedWith($redis, $msgpack);

        $msgpack->pack([
            OriginalEventsClass::EMITTER_UID,
            [
                'type' => OriginalEventsClass::EVENT,
                'data' => [ 'phpspec', '123456' ],
                'nsp' => '/'
            ],
            [ 'flags' => [] ]
        ])->shouldBeCalled()->willReturn('000$PHPSPEC_PACK_MOCK$000');

        $redis->publish('socket.io#/#', '000$PHPSPEC_PACK_MOCK$000')->shouldBeCalled();

        $this->emit('phpspec', '123456')->shouldReturn($this);
    }

    function it_should_emit_binary(RedisPubSubClient $redis, MsgPack $msgpack)
    {
        $this->beConstructedWith($redis, $msgpack);

        $msgpack->pack([
            OriginalEventsClass::EMITTER_UID,
            [
                'type' => OriginalEventsClass::BINARY_EVENT,
                'data' => [ 'phpspec', '123456' ],
                'nsp' => '/'
            ],
            [ 'flags' => [ 'binary' => true ] ]
        ])->shouldBeCalled()->willReturn(pack('c', 0xDA) . '000$PHPSPEC_PACK_MOCK$000' . pack('c', 0xDB));

        $redis->publish('socket.io#/#', pack('c', 0xD8) . '000$PHPSPEC_PACK_MOCK$000' . pack('c', 0xD9))->shouldBeCalled();

        $this->emit('phpspec', new Binary('123456'))->shouldReturn($this);
    }

    function it_should_emit_with_flags(RedisPubSubClient $redis, MsgPack $msgpack)
    {
        $this->beConstructedWith($redis, $msgpack);

        $msgpack->pack([
            OriginalEventsClass::EMITTER_UID,
            [
                'type' => OriginalEventsClass::EVENT,
                'data' => [ 'phpspec', '123456' ],
                'nsp' => '/'
            ],
            [ 'flags' => [ 'phpspec' => true, 'cepsphp' => true ] ]
        ])->shouldBeCalled()->willReturn('000$PHPSPEC_PACK_MOCK$000');

        $redis->publish('socket.io#/#', '000$PHPSPEC_PACK_MOCK$000')->shouldBeCalled();

        $this->setFlag('phpspec', true);
        $this->setFlag('cepsphp', true);
        $this->emit('phpspec', '123456')->shouldReturn($this);
    }

    function it_should_emit_to_rooms(RedisPubSubClient $redis, MsgPack $msgpack)
    {
        $this->beConstructedWith($redis, $msgpack);

        $msgpack->pack([
            OriginalEventsClass::EMITTER_UID,
            [
                'type' => OriginalEventsClass::EVENT,
                'data' => [ 'phpspec', '123456' ],
                'nsp' => '/'
            ],
            [ 'flags' => [], 'rooms' => [ 'phpspec:0000', 'phpspec:0001' ] ]
        ])->shouldBeCalled()->willReturn('000$PHPSPEC_PACK_MOCK$000');

        $redis->publish('socket.io#/#', '000$PHPSPEC_PACK_MOCK$000')->shouldBeCalled();

        $this->to([ 'phpspec:0000', 'phpspec:0001' ]);
        $this->emit('phpspec', '123456')->shouldReturn($this);
    }

    function it_should_emit_on_nsp(RedisPubSubClient $redis, MsgPack $msgpack)
    {
        $this->beConstructedWith($redis, $msgpack);

        $msgpack->pack([
            OriginalEventsClass::EMITTER_UID,
            [
                'type' => OriginalEventsClass::EVENT,
                'data' => [ 'phpspec', '123456' ],
                'nsp' => '/phpspec'
            ],
            [ 'flags' => [] ]
        ])->shouldBeCalled()->willReturn('000$PHPSPEC_PACK_MOCK$000');

        $redis->publish('socket.io#/phpspec#', '000$PHPSPEC_PACK_MOCK$000')->shouldBeCalled();

        $this->of('/phpspec');
        $this->emit('phpspec', '123456')->shouldReturn($this);
    }
}
