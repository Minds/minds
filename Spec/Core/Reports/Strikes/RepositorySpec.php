<?php

namespace Spec\Minds\Core\Reports\Strikes;

use Minds\Core\Reports\Strikes\Repository;
use Minds\Core\Reports\Strikes\Strike;
use Minds\Core\Data\Cassandra\Client;
use Minds\Common\Urn;
use Cassandra\Bigint;
use Cassandra\Timestamp;
use Cassandra\Tinyint;
use Cassandra\Decimal;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    private $cql;

    function let(Client $cql)
    {
        $this->beConstructedWith($cql);
        $this->cql = $cql;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_return_a_single_strike_from_urn()
    {
        $this->cql->request(Argument::that(function($prepared) {
            return true;
        }))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'user_guid' => new Bigint(123),
                    'timestamp' => new Timestamp(1557226524000),
                    'reason_code' => new Tinyint(2),
                    'sub_reason_code' => new Decimal(5),
                    'report_urn' => 'urn:goes:here',
                ]
            ]);

        $strike = $this->get('urn:strike:123-1557226524000-2-5');
        $strike->getUserGuid()
            ->shouldBe('123');
        $strike->getTimestamp()
            ->shouldBe(1557226524000);
        $strike->getReasonCode()
            ->shouldBe(2);
        $strike->getSubReasonCode()
            ->shouldBe(5);
        $strike->getReportUrn()
            ->shouldBe('urn:goes:here');
    }

    function it_should_return_a_list_of_strikes_for_a_time_period()
    {
        $this->cql->request(Argument::that(function($prepared) {
            $values = $prepared->build()['values'];
            return $values[0]->value() == 123
                && round($values[1]->time(), 5) == round(strtotime('-90 days'), 5)
                && round($values[2]->time(), 5) == round(time(), 5);
        }))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'user_guid' => new Bigint(123),
                    'timestamp' => new Timestamp(1557226524000),
                    'reason_code' => new Tinyint(2),
                    'sub_reason_code' => new Decimal(5),
                    'report_urn' => 'urn:goes:here',
                ],
                [
                    'user_guid' => new Bigint(123),
                    'timestamp' => new Timestamp(1567226524000),
                    'reason_code' => new Tinyint(2),
                    'sub_reason_code' => new Decimal(3),
                    'report_urn' => 'urn:goes:here',
                ]
            ]);
        
        $strikes = $this->getList([
            'user_guid' => 123,
        ]);
        $strikes[0]->getUserGuid()
            ->shouldBe('123');
        $strikes[0]->getTimestamp()
            ->shouldBe(1557226524000);
        $strikes[0]->getReasonCode()
            ->shouldBe(2);
        $strikes[0]->getSubReasonCode()
            ->shouldBe(5);
        $strikes[0]->getReportUrn()
            ->shouldBe('urn:goes:here');

        $strikes[1]->getUserGuid()
            ->shouldBe('123');
        $strikes[1]->getTimestamp()
            ->shouldBe(1567226524000);
        $strikes[1]->getReasonCode()
            ->shouldBe(2);
        $strikes[1]->getSubReasonCode()
            ->shouldBe(3);
        $strikes[1]->getReportUrn()
           ->shouldBe('urn:goes:here');
    }

    function it_should_save_a_strike_to_the_database(Strike $strike)
    {
        $this->cql->request(Argument::that(function($prepared) {
            $values = $prepared->build()['values'];
            return $values[0]->value() == 123
                && $values[1]->time() == 1549451597000
                && $values[2]->value() == 2
                && $values[3]->value() == 3
                && $values[4] == 'urn:report:(urn:activity:123)-2-3-1549451597000';
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $strike->getUserGuid()
            ->shouldBeCalled()
            ->willReturn(123);

        $strike->getTimestamp()
            ->shouldBeCalled()
            ->willReturn(1549451597000);
        
        $strike->getReasonCode()
            ->shouldBeCalled()
            ->willReturn(2);

        $strike->getSubReasonCode()
            ->shouldBeCalled()
            ->willReturn(3);

        $strike->getReportUrn()
            ->shouldBeCalled()
            ->willReturn('urn:report:(urn:activity:123)-2-3-1549451597000');

        $this->add($strike)
            ->shouldReturn(true);
    }

}
