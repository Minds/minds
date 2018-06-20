<?php

namespace Spec\Minds\Core\Blockchain;

use Minds\Core\Blockchain\Util;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UtilSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Util');
    }

    function it_should_parse_a_number()
    {
        $data = "0x0000000000000000000000000f17f52151ebef6c7334fad080c5704d77216b73";
        $this::parseData($data, [Util::NUMBER])->shouldHaveCount(1);

        $this::parseData($data, [Util::NUMBER])->shouldHaveKeyWithValue(0, '0xf17f52151ebef6c7334fad080c5704d77216b73');
    }

    function it_should_parse_an_address()
    {
        $data = "0x0000000000000000000000000f17f52151ebef6c7334fad080c5704d77216b73";
        $this::parseData($data, [Util::ADDRESS])->shouldHaveCount(1);

        $this::parseData($data, [Util::ADDRESS])->shouldHaveKeyWithValue(0, '0x0f17f52151ebef6c7334fad080c5704d77216b73');
    }

    function it_should_parseData()
    {
        $data = "0x000000000000000000000000f17f52151ebef6c7334fad080c5704d77216b732000000000000000000000000c5fdf4076b8f3a5357c5e395ab970b5b54098fef000000000000000000000000000000000000000000000000000000000000000a";
        $this::parseData($data, [Util::ADDRESS, Util::ADDRESS, Util::NUMBER])->shouldHaveCount(3);

        $this::parseData($data, [Util::ADDRESS, Util::ADDRESS, Util::NUMBER])->shouldHaveKeyWithValue(0, '0xf17f52151ebef6c7334fad080c5704d77216b732');
        $this::parseData($data, [Util::ADDRESS, Util::ADDRESS, Util::NUMBER])->shouldHaveKeyWithValue(1, '0xc5fdf4076b8f3a5357c5e395ab970b5b54098fef');
        $this::parseData($data, [Util::ADDRESS, Util::ADDRESS, Util::NUMBER])->shouldHaveKeyWithValue(2, '0xa');
    }
}
