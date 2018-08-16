<?php

namespace Spec\Minds\Core\Blockchain\Purchase;

use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SumsSpec extends ObjectBehavior
{
    /** @var Client */
    private $db;

    function let(Client $db)
    {
        $this->beConstructedWith($db);

        $this->db = $db;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Blockchain\Purchase\Sums');
    }

    function it_should_get_the_total_amount()
    {
        $this->db->request(Argument::any())
            ->willReturn([
                ['amount' => 12000]
            ]);

        $this->getTotalAmount()
            ->shouldReturn('12000');
    }

    function it_should_get_the_total_amount_but_return_0()
    {
        $this->db->request(Argument::any())
            ->willReturn([]);

        $this->getTotalAmount()
            ->shouldReturn(0);
    }

    function it_should_get_the_total_amount_but_encounter_a_database_error()
    {
        $this->db->request(Argument::any())
            ->willThrow(new \Exception());

        $this->getTotalAmount()
            ->shouldReturn(0);
    }


    function it_sould_get_the_total_count()
    {
        $this->db->request(Argument::any())
            ->willReturn([
                ['count' => 12]
            ]);

        $this->getTotalCount()
            ->shouldReturn('12');
    }

    function it_should_get_the_total_count_but_not_find_any_entry()
    {
        $this->db->request(Argument::any())
            ->willReturn([]);

        $this->getTotalCount()
            ->shouldReturn(0);
    }

    function it_should_get_the_total_count_but_encounter_a_database_error()
    {
        $this->db->request(Argument::any())
            ->willThrow(new \Exception());

        $this->getTotalCount()
            ->shouldReturn(0);
    }

    function it_should_get_the_requested_amount()
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT SUM(requested_amount) as amount FROM token_purchases 
            WHERE phone_number_hash = ?"
                && $built['values'][0] === 'hash';
        }))
            ->shouldBeCalled()
            ->willReturn([
                ['amount' => 100]
            ]);

        $this->getRequestedAmount('hash')
            ->shouldReturn('100');
    }

    function it_should_get_the_requested_amount_but_not_find_any_entry()
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT SUM(requested_amount) as amount FROM token_purchases 
            WHERE phone_number_hash = ?"
                && $built['values'][0] === 'hash';
        }))
            ->shouldBeCalled()
            ->willReturn([]);

        $this->getRequestedAmount('hash')
            ->shouldReturn(0);
    }

    function it_should_get_the_requested_amount_but_encounter_a_database_error()
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT SUM(requested_amount) as amount FROM token_purchases 
            WHERE phone_number_hash = ?"
                && $built['values'][0] === 'hash';
        }))
            ->shouldBeCalled()
            ->willThrow(new \Exception());

        $this->getRequestedAmount('hash')
            ->shouldReturn(0);
    }

    function it_should_get_issued_amount()
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT SUM(issued_amount) as amount FROM token_purchases 
            WHERE phone_number_hash = ?"
                && $built['values'][0] === 'hash';
        }))
            ->shouldBeCalled()
            ->willReturn([['amount' => 100]]);

        $this->getIssuedAmount('hash')
            ->shouldReturn('100');
    }

    function it_should_get_issued_amount_but_not_find_any_entry()
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT SUM(issued_amount) as amount FROM token_purchases 
            WHERE phone_number_hash = ?"
                && $built['values'][0] === 'hash';
        }))
            ->shouldBeCalled()
            ->willReturn([]);

        $this->getIssuedAmount('hash')
            ->shouldReturn(0);
    }

    function it_should_get_issued_amount_but_encounter_a_database_error()
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT SUM(issued_amount) as amount FROM token_purchases 
            WHERE phone_number_hash = ?"
                && $built['values'][0] === 'hash';
        }))
            ->shouldBeCalled()
            ->willThrow(new \Exception());

        $this->getIssuedAmount('hash')
            ->shouldReturn(0);
    }

    function it_should_get_unissued_amount()
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT SUM(requested_amount) as requested,
            SUM(issued_amount) as issued,
            FROM token_purchases 
            WHERE phone_number_hash = ?"
                && $built['values'][0] === 'hash';
        }))
            ->shouldBeCalled()
            ->willReturn([['requested' => 100, 'issued' => 30]]);

        $this->getUnissuedAmount('hash')
            ->shouldReturn('70');
    }

    function it_should_get_unissued_amount_but_not_find_any_entry()
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT SUM(requested_amount) as requested,
            SUM(issued_amount) as issued,
            FROM token_purchases 
            WHERE phone_number_hash = ?"
                && $built['values'][0] === 'hash';
        }))
            ->shouldBeCalled()
            ->willReturn([]);

        $this->getUnissuedAmount('hash')
            ->shouldReturn(0);
    }

    function it_should_get_unissued_amount_but_encounter_a_database_error()
    {
        $this->db->request(Argument::that(function ($query) {
            $built = $query->build();
            return $built['string'] === "SELECT SUM(requested_amount) as requested,
            SUM(issued_amount) as issued,
            FROM token_purchases 
            WHERE phone_number_hash = ?"
                && $built['values'][0] === 'hash';
        }))
            ->shouldBeCalled()
            ->willThrow(new \Exception());

        $this->getUnissuedAmount('hash')
            ->shouldReturn(0);
    }

}
