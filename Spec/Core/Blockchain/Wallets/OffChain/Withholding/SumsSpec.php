<?php

namespace Spec\Minds\Core\Blockchain\Wallets\OffChain\Withholding;

use Minds\Core\Blockchain\Wallets\OffChain\Withholding\Sums;
use Minds\Core\Config;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SumsSpec extends ObjectBehavior
{
    /** @var Client */
    private $db;
    /** @var Config */
    private $config;

    function let(Client $db, Config $config)
    {
        $this->db = $db;
        $this->config = $config;

        $this->beConstructedWith($db, $config);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Sums::class);
    }

    function it_should_get_the_sum()
    {
        $this->config->get('blockchain')
            ->shouldBeCalled()
            ->willReturn(['disable_creditcards' => 0]);

        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();

            return $built['string'] === "SELECT sum(amount) as total FROM withholdings WHERE user_guid = ?"
                && $built['values'][0]->value() === '123';
        }))
            ->shouldBeCalled()
            ->willReturn([
                ['total' => '1000000000000000000']
            ]);

        $this->setUserGuid('123');

        $this->get()->shouldReturn('1000000000000000000');
    }

    function it_shouldnt_get_the_sum_if_creditcards_are_disabled()
    {

        $this->config->get('blockchain')
            ->shouldBeCalled()
            ->willReturn(['disable_creditcards' => 1]);

        $this->setUserGuid('123');

        $this->get()->shouldReturn('0');
    }
}
