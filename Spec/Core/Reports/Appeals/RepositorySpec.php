<?php

namespace Spec\Minds\Core\Reports\Appeals;

use Minds\Core\Reports\Appeals\Repository;
use Minds\Core\Reports\Appeals\Appeal;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Repository as ReportsRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Data\ElasticSearch\Client;

class RepositorySpec extends ObjectBehavior
{
    private $es;
    private $reportsRepository;

    function let(Client $es, ReportsRepository $reportsRepository)
    {
        $this->beConstructedWith($es, $reportsRepository);
        $this->es = $es;
        $this->reportsRepository = $reportsRepository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_return_a_list_of_appealable_reports()
    {
        $this->reportsRepository->getList(Argument::that(function($opts) {
            return $opts['must_not'][1]['exists']['field'] === '@appeal_jury_decided_timestamp'
                && $opts['must_not'][2]['exists']['field'] === '@appeal_timestamp';
        }))
            ->shouldBeCalled()
            ->willReturn([
                new Report(),
                new Report(),
            ]);

        $response = $this->getList([ 'owner_guid' => 123 ]);
        $response->shouldHaveCount(2);
    }

    function it_should_return_a_list_of_appealed_reports()
    {
        $this->reportsRepository->getList(Argument::that(function($opts) {
            return $opts['must'][2]['exists']['field'] === '@appeal_timestamp';
        }))
            ->shouldBeCalled()
            ->willReturn([
                (new Report())
                    ->setAppealNote('first note'),
                (new Report())
                    ->setAppealNote('second note'),
            ]);

        $response = $this->getList([ 
            'owner_guid' => 123,
            'showAppealed' => true 
        ]);
        $response->shouldHaveCount(2);
        $response[0]->getNote()
            ->shouldBe('first note');
        $response[1]->getNote()
            ->shouldBe('second note');
    }

    function it_should_add_an_appeal(Appeal $appeal)
    {
        $ts = (int) microtime(true);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $doc = $query['body']['doc'];
                return $doc['@appeal_timestamp'] === $ts
                    && $doc['appeal_note'] === 'Should not be reported because this is a test'
                    && $query['id'] === 123;
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $appeal->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

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
