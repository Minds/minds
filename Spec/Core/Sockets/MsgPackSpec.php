<?php

namespace Spec\Minds\Core\Sockets;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MsgPackSpec extends ObjectBehavior
{
    function let()
    {
        // Force disable big-endian
        $this->beConstructedWith(false);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Sockets\MsgPack');
    }

    function it_should_pack_null_msg()
    {
        $this->pack(null)->shouldBe(pack('C', 0xC0));
    }

    function it_should_pack_false_msg()
    {
        $this->pack(false)->shouldBe(pack('C', 0xC2));
    }

    function it_should_pack_true_msg()
    {
        $this->pack(true)->shouldBe(pack('C', 0xC3));
    }

    // pfixnum <= 127
    function it_should_pack_positive_fixnum_msg()
    {
        $this->pack(125)->shouldBe(pack('C', 125 & 0x7F));
    }

    // 0 > nfixnum >= -32
    function it_should_pack_negative_fixnum_msg()
    {
        $this->pack(-30)->shouldBe(pack('c', -30));
    }

    // uint8 > 127
    function it_should_pack_uint8_msg()
    {
        $this->pack(250)->shouldBe(pack('CC', 0xCC, 250));
    }

    // uint16 > 255
    function it_should_pack_uint16_msg()
    {
        $this->pack(65530)->shouldBe(pack('Cn', 0xCD, 65530));
    }

    // uint32 > 65535
    function it_should_pack_uint32_msg()
    {
        $this->pack(4294967290)->shouldBe(pack('CN', 0xCE, 4294967290));
    }

    // uint64 > 4294967295
    function it_should_pack_uint64_msg()
    {
        $this->pack(4294967300)->shouldBe(pack(
            'CNN',
            0xCF,
            (4294967300 & 0xFFFFFFFF00000000) >> 32,
            4294967300 & 0xFFFFFFFF
        ));
    }

    function it_should_pack_int8_msg()
    {
        $this->pack(-123)->shouldBe(pack('Cc', 0xD0, -123));
    }

    function it_should_pack_int16_msg()
    {
        $this->pack(-32763)->shouldBe(pack('Ca2', 0xD1, strrev(pack('s', -32763))));
    }

    function it_should_pack_int32_msg()
    {
        $this->pack(-2147483643)->shouldBe(pack('Ca4', 0xD2, strrev(pack('l', -2147483643))));
    }

    function it_should_pack_int64_msg()
    {
        $this->pack(-2147483653)->shouldBe(pack(
            'Ca4a4',
            0xD3,
            strrev(pack('l', (-2147483653 >> 32) & 0xFFFFFFFF)),
            strrev(pack('l', -2147483653 & 0xFFFFFFFF))
        ));
    }

    function it_should_pack_float_msg()
    {
        $this->pack(3.1415926)->shouldBe(pack('C', 0xCB) . strrev(pack('d', 3.1415926)));
    }

    function it_should_pack_tiny_string_msg()
    {
        $str = str_repeat('X', 31);
        $this->pack($str)->shouldBe(pack('Ca*', 0xA0 | strlen($str), $str));
    }

    function it_should_pack_string_msg()
    {
        $str = str_repeat('X', 65530);
        $this->pack($str)->shouldBe(pack('Cna*', 0xDA, strlen($str), $str));
    }

    function it_should_pack_long_string_msg()
    {
        $str = str_repeat('X', 65540);
        $this->pack($str)->shouldBe(pack('CNa*', 0xDB, strlen($str), $str));
    }
}
