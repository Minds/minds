<?php

namespace Spec\Minds\Core\Blockchain;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UtilSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Util');
    }

    function it_should_parseData()
    {
        $data = "0x000000000000000000000000f17f52151ebef6c7334fad080c5704d77216b732000000000000000000000000c5fdf4076b8f3a5357c5e395ab970b5b54098fef000000000000000000000000000000000000000000000000000000000000000a";
        $this::parseData($data)->shouldHaveCount(3);

        $this::parseData($data)->shouldHaveKeyWithValue(0, '0xf17f52151ebef6c7334fad080c5704d77216b732');
        $this::parseData($data)->shouldHaveKeyWithValue(1, '0xc5fdf4076b8f3a5357c5e395ab970b5b54098fef');
        $this::parseData($data)->shouldHaveKeyWithValue(2, '0xa');
    }

    function it_should_convert_hex_to_dec()
    {
        $hex = "0xa";
        $this::toDec($hex)->shouldBe((double) 10);
    }
}
