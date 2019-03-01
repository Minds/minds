<?php

namespace Spec\Minds\Core\Reports\Verdict;

use Minds\Core\Reports\Verdict\Repository;
use Minds\Core\Reports\Verdict\Verdict;
use Minds\Core\Reports\Report;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Data\ElasticSearch\Client;

class RepositorySpec extends ObjectBehavior
{
    private $es;

    function let(Client $es)
    {
        $this->beConstructedWith($es);
        $this->es = $es;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_add_a_verdict(Verdict $verdict)
    {
        $ts = microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $doc = $query['body']['doc'];
                return $doc['@initial_jury_decided_timestamp'] === $ts
                    && $doc['initial_jury_action'] === 'explicit'
                    && $query['id'] === 123;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $verdict->getAction()
            ->shouldBeCalled()
            ->willReturn('explicit');

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);
        
        $this->add($verdict)
            ->shouldBe(true);
    }

    function it_should_add_a_verdict_as_overturned(Verdict $verdict)
    {
        $ts = microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $doc = $query['body']['doc'];
                return $doc['@initial_jury_decided_timestamp'] === $ts
                    && $doc['initial_jury_action'] === 'overturned'
                    && $query['id'] === 123;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $verdict->getAction()
            ->shouldBeCalled()
            ->willReturn('overturned');

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);
        
        $this->add($verdict)
            ->shouldBe(true);
    }

    function it_should_add_an_appeal_verdict(Verdict $verdict)
    {
        $ts = microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $doc = $query['body']['doc'];
                return $doc['@appeal_jury_decided_timestamp'] === $ts
                    && $doc['appeal_jury_action'] === 'explicit'
                    && $query['id'] === 123;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $verdict->getAction()
            ->shouldBeCalled()
            ->willReturn('explicit');

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);
        
        $this->add($verdict)
            ->shouldBe(true);
    }

    function it_should_add_an_appeal_verdict_as_overturned(Verdict $verdict)
    {
        $ts = microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $doc = $query['body']['doc'];
                return $doc['@appeal_jury_decided_timestamp'] === $ts
                    && $doc['appeal_jury_action'] === 'overturned'
                    && $query['id'] === 123;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $verdict->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $verdict->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $verdict->getAction()
            ->shouldBeCalled()
            ->willReturn('overturned');

        $verdict->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);
        
        $this->add($verdict)
            ->shouldBe(true);
    }

    function it_should_get_a_single_verdict()
    {
        $this->es->request(Argument::that(function($prepared) {
                return true;
            }))
            ->shouldBeCalled()
            ->willReturn([
                'hits' => [
                    'hits' => [
                        [
                            '_index' => 'minds-moderation',
                            '_type' => 'reports',
                            '_id' => 123,
                            '_score' => 1,
                            '_source' => [
                                'entity_guid' => 123,
                            ],
                        ]
                    ],
                ],
                'aggregations' => [
                    'decisions' => [
                        'buckets' => [
                            'key' => 10,
                                'doc_count' => 1,
                                'initial_jury' => [
                                    'doc_count' => 3,
                                    'decision' => [
                                        'buckets' => [
                                            [
                                                'key' => 4,
                                                'doc_count' => 2,
                                                'action' => [
                                                    'buckets' => [
                                                        [
                                                            'key' => 'action',
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
            ]);

        $verdict = $this->get(123);

        $verdict->getReport()->getEntityGuid()
            ->shouldBe(123);
        $verdict->isAppeal()
            ->shouldBe(false);

        $decisions = $verdict->getDecisions();
        $decisions[0]->getJurorGuid()
                    ->shouldBe(4);
    }

    function it_should_get_a_single_verdict_as_an_appeal()
    {
        $this->es->request(Argument::that(function($prepared) {
                return true;
            }))
            ->shouldBeCalled()
            ->willReturn([
                'hits' => [
                    'hits' => [
                        [
                            '_index' => 'minds-moderation',
                            '_type' => 'reports',
                            '_id' => 123,
                            '_score' => 1,
                            '_source' => [
                                'entity_guid' => 123,
                                '@initial_jury_decided_timestamp' => 1551052800000,
                                'initial_jury_action' => 'explicit',
                            ],
                        ]
                    ],
                ],
                'aggregations' => [
                    'decisions' => [
                        'buckets' => [
                            'key' => 10,
                                'doc_count' => 1,
                                'appeal_jury' => [
                                    'doc_count' => 3,
                                    'decision' => [
                                        'buckets' => [
                                            [
                                                'key' => 4,
                                                'doc_count' => 2,
                                                'action' => [
                                                    'buckets' => [
                                                        [
                                                            'key' => 6,
                                                        ],
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
            ]);

        $verdict = $this->get(123);

        $verdict->getReport()->getEntityGuid()
            ->shouldBe(123);
        $verdict->isAppeal()
            ->shouldBe(true);
        $decisions = $verdict->getDecisions();
        $decisions[0]->getJurorGuid()
                    ->shouldBe(4);
    }

}
