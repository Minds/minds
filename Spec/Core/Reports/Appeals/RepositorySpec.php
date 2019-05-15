<?php

namespace Spec\Minds\Core\Reports\Appeals;

use Minds\Core\Reports\Appeals\Repository;
use Minds\Core\Reports\Appeals\Appeal;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Repository as ReportsRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Data\Cassandra\Client;
use Cassandra\Bigint;
use Cassandra\Float_;
use Cassandra\Timestamp;
use Cassandra\Type;
use Cassandra\Type\Map;
use Cassandra\Type\Set;
use Cassandra\Boolean;

class RepositorySpec extends ObjectBehavior
{
    private $cql;
    private $reportsRepository;

    function let(Client $cql, ReportsRepository $reportsRepository)
    {
        $this->beConstructedWith($cql);
        $this->cql = $cql;
        //$this->reportsRepository = $reportsRepository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_return_a_list_of_appealable_reports()
    {
        $this->cql->request(Argument::that(function($prepared) {
            return true;
        }))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'entity_urn' => 'urn:activity:123',
                    'entity_owner_guid' => new Bigint(456),
                    'reason_code' => new Float_(2),
                    'sub_reason_code' => new Float_(5),
                    'timestamp' => new Timestamp(1549451597000),
                    'state' => 'initial_jury_decided',
                    'state_changes' => (new Map(Type::text(), Type::timestamp()))
                        ->set('reported', new Timestamp(1549451597000)),
                    'appeal_note' => null,
                    'reports' => (new Set(Type::bigint()))
                        ->add(new Bigint(789)),
                    'initial_jury' => (new Map(Type::bigint(), Type::boolean()))
                        ->set(new Bigint(101112), new Boolean(true)),
                    'appeal_jury' => (new Map(Type::bigint(), Type::boolean())),
                    'user_hashes' => (new Set(Type::text()))
                        ->add('hashFor789'),
                ],
                [
                    'entity_urn' => 'urn:activity:1234',
                    'entity_owner_guid' => new Bigint(456),
                    'reason_code' => new Float_(2),
                    'sub_reason_code' => new Float_(5),
                    'timestamp' => new Timestamp(1549451597000),
                    'state' => 'initial_jury_decided',
                    'state_changes' => (new Map(Type::text(), Type::timestamp()))
                        ->set('reported', new Timestamp(1549451597000)),
                    'appeal_note' => null,
                    'reports' => (new Set(Type::bigint()))
                        ->add(new Bigint(789)),
                    'initial_jury' => (new Map(Type::bigint(), Type::boolean()))
                        ->set(new Bigint(101112), new Boolean(true)),
                    'appeal_jury' => (new Map(Type::bigint(), Type::boolean())),
                    'user_hashes' => (new Set(Type::text()))
                        ->add('hashFor789'),
                ],
            ]);

        $response = $this->getList([ 'owner_guid' => 456 ]);
        $response->shouldHaveCount(2);
    }

    function it_should_return_a_list_of_appealed_reports()
    {
        $this->cql->request(Argument::that(function($prepared) {
            return true;
        }))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'entity_urn' => 'urn:activity:123',
                    'entity_owner_guid' => new Bigint(456),
                    'reason_code' => new Float_(2),
                    'sub_reason_code' => new Float_(5),
                    'timestamp' => new Timestamp(1549451597000),
                    'state' => 'initial_jury_decided',
                    'state_changes' => (new Map(Type::text(), Type::timestamp()))
                        ->set('reported', new Timestamp(1549451597000)),
                    'appeal_note' => 'hello world',
                    'reports' => (new Set(Type::bigint()))
                        ->add(new Bigint(789)),
                    'initial_jury' => (new Map(Type::bigint(), Type::boolean()))
                        ->set(new Bigint(101112), new Boolean(true)),
                    'appeal_jury' => (new Map(Type::bigint(), Type::boolean()))
                        ->set(new Bigint(101113), new Boolean(true)),
                    'user_hashes' => (new Set(Type::text()))
                        ->add('hashFor789'),
                ],
            ]);

        $response = $this->getList([
            'owner_guid' => 456,
            'showAppealed' => true,
        ]);
        $response->shouldHaveCount(1);
        $response[0]->getNote()
            ->shouldBe('hello world');
        $response[0]->getReport()
            ->getEntityUrn()
            ->shouldBe('urn:activity:123');
    }

    function it_should_add_an_appeal(Appeal $appeal)
    {
        $ts = (int) microtime(true);
        $this->cql->request(Argument::that(function($prepared) use ($ts) {
                $values = $prepared->build()['values'];
                return $values[0] == 'Should not be reported because this is a test'
                    && $values[1] == 'appealed'
                    && $values[3] == 'urn:activity:123'
                    && $values[4]->value() == 2
                    && $values[5]->value() == 5;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $appeal->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityUrn('urn:activity:123')
            ->setReasonCode(2)
            ->setSubReasonCode(5);

        $appeal->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $appeal->getNote()
            ->shouldBeCalled()
            ->willReturn('Should not be reported because this is a test');
        
        $this->add($appeal)
            ->shouldBe(true);
    }

}
