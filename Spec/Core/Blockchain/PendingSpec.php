<?php

namespace Spec\Minds\Core\Blockchain;

use Cassandra\Varint;
use Minds\Core\Blockchain\Pending;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PendingSpec extends ObjectBehavior
{
    /** @var Client */
    private $cql;

    function let(Client $cql)
    {
        $this->beConstructedWith($cql);

        $this->cql = $cql;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Pending::class);
    }

    function it_should_add_a_new_entry()
    {
        $data = [
            'type' => 'wire',
            'tx_id' => '0x123',
            'sender_guid' => '123',
            'data' => 'data'
        ];

        $this->cql->request(Argument::that(function ($query) use ($data) {
            $built = $query->build();
            return $built['string'] = "INSERT INTO blockchain_pending (type, tx_id, sender_guid, data) VALUES (?, ?, ?, ?) USING TTL ?"
                && $built['values'][0] === $data['type']
                && $built['values'][1] === $data['tx_id']
                && $built['values'][2]->value() === $data['sender_guid']
                && $built['values'][3] === json_encode($data['data'])
                && $built['values'][4] === Pending::PENDING_TTL;
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($data)->shouldReturn(true);
    }

    function it_should_fail_while_adding_a_new_entry()
    {
        $data = [
            'type' => 'wire',
            'tx_id' => '0x123',
            'sender_guid' => '123',
            'data' => 'data'
        ];

        $this->cql->request(Argument::that(function ($query) use ($data) {
            $built = $query->build();
            return $built['string'] = "INSERT INTO blockchain_pending (type, tx_id, sender_guid, data) VALUES (?, ?, ?, ?) USING TTL ?"
                && $built['values'][0] === $data['type']
                && $built['values'][1] === $data['tx_id']
                && $built['values'][2]->value() === $data['sender_guid']
                && $built['values'][3] === json_encode($data['data'])
                && $built['values'][4] === Pending::PENDING_TTL;
        }))
            ->shouldBeCalled()
            ->willThrow(new \Exception());

        $this->add($data)->shouldReturn(false);
    }

    function it_should_get_an_entry()
    {
        $this->cql->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] = "SELECT * from blockchain_pending WHERE type = ? AND tx_id = ?"
                && $built['values'][0] === 'boost'
                && $built['values'][1] === '0x123';
        }))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'sender_guid' => new Varint('123'),
                    'data' => '"data"'
                ]
            ]);

        $this->get('boost', '0x123')->shouldReturn([
            'sender_guid' => '123',
            'data' => 'data'
        ]);
    }

    function it_shouldnt_find_an_entry()
    {
        $this->cql->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] = "SELECT * from blockchain_pending WHERE type = ? AND tx_id = ?"
                && $built['values'][0] === 'boost'
                && $built['values'][1] === '0x123';
        }))
            ->shouldBeCalled()
            ->willReturn(null);

        $this->get('boost', '0x123')->shouldReturn(false);
    }

    function it_should_fail_while_getting_an_entry()
    {
        $this->cql->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] = "SELECT * from blockchain_pending WHERE type = ? AND tx_id = ?"
                && $built['values'][0] === 'boost'
                && $built['values'][1] === '0x123';
        }))
            ->shouldBeCalled()
            ->willThrow(new \Exception());

        $this->get('boost', '0x123')->shouldReturn(false);
    }

    function it_should_delete_an_entry()
    {
        $this->cql->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] = "DELETE FROM blockchain_pending WHERE type = ? AND tx_id = ?"
                && $built['values'][0] === 'boost'
                && $built['values'][1] === '0x123';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->delete('boost', '0x123')->shouldReturn(true);
    }

    function it_should_fail_while_deleting_an_entry()
    {
        $this->cql->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] = "DELETE FROM blockchain_pending WHERE type = ? AND tx_id = ?"
                && $built['values'][0] === 'boost'
                && $built['values'][1] === '0x123';
        }))
            ->shouldBeCalled()
            ->willThrow(new \Exception());

        $this->delete('boost', '0x123')->shouldReturn(false);
    }
}
