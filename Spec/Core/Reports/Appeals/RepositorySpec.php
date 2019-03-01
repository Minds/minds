<?php

namespace Spec\Minds\Core\Reports\Appeals;

use Minds\Core\Reports\Appeals\Repository;
use Minds\Core\Reports\Appeals\Appeal;
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

    function it_should_add_an_appeal(Appeal $appeal)
    {
        $ts = microtime(true);
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
