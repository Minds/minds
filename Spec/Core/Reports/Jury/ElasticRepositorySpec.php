<?php

namespace Spec\Minds\Core\Reports\Jury;

use Minds\Core\Reports\Jury\ElasticRepository;
use Minds\Core\Reports\Jury\Decision;
use Minds\Core\Reports\Report;
use Minds\Core\Reports\Repository as ReportsRepository;
use Minds\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Minds\Core\Data\ElasticSearch\Client;

class ElasticRepositorySpec extends ObjectBehavior
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
        $this->shouldHaveType(ElasticRepository::class);
    }

    function it_should_return_reports_we_can_use_in_jury()
    {
        $user = new User();
        $user->set('guid', 123);
        $user->setPhoneNumberHash('phoneHash');
        
        $this->reportsRepository->getList(Argument::that(function($opts) {
            return $opts['must_not'][0]['match']['entity_owner_guid'] === 123 //Not self
                && $opts['must_not'][1]['exists']['field'] === '@initial_jury_decided_timestamp' // Not initial jury 
                && $opts['must_not'][2]['exists']['field'] === '@appeal_jury_decided_timestamp' // Not appeal jury
                && $opts['must_not'][3]['nested']['query']['bool']['must'][0]['match']['reports.reporter_guid'] === 123 // Not reported
                && $opts['must_not'][4]['nested']['query']['bool']['must'][0]['match']['initial_jury.juror_hash'] === 'phoneHash' // Not initial_jury
                && $opts['must_not'][5]['nested']['query']['bool']['must'][0]['match']['appeal_jury.juror_hash'] === 'phoneHash'; // Not appeal_jury
        }))
            ->shouldBeCalled()
            ->willReturn([
                [],
                [],
            ]);
        
        $response = $this->getList([ 
            'user' => $user,
            'juryType' => 'initial',
        ]);
        $response->shouldHaveCount(2);
    }

    function it_should_return_reports_we_can_use_in_appeal_jury()
    {
        $user = new User();
        $user->set('guid', 123);
        $user->setPhoneNumberHash('phoneHash');
        
        $this->reportsRepository->getList(Argument::that(function($opts) {
            return $opts['must_not'][0]['match']['entity_owner_guid'] === 123 //Not self
                && $opts['must'][0]['exists']['field'] === '@initial_jury_decided_timestamp' // must be initial jury 
                && $opts['must'][1]['exists']['field'] === '@appeal_timestamp' // must have appealed
                && $opts['must_not'][1]['exists']['field'] === '@appeal_jury_decided_timestamp' // Not appeal jury
                && $opts['must_not'][2]['nested']['query']['bool']['must'][0]['match']['reports.reporter_guid'] === 123 // Not reported
                && $opts['must_not'][3]['nested']['query']['bool']['must'][0]['match']['initial_jury.juror_hash'] === 'phoneHash' // Not initial_jury
                && $opts['must_not'][4]['nested']['query']['bool']['must'][0]['match']['appeal_jury.juror_hash'] === 'phoneHash'; // Not appeal_jury
        }))
            ->shouldBeCalled()
            ->willReturn([
                [],
                [],
            ]);
        
        $response = $this->getList([ 
            'user' => $user,
            'juryType' => 'appeal',
        ]);
        $response->shouldHaveCount(2);
    }

    function it_must_require_a_phone_hash()
    {
        $user = new User();
        $user->set('guid', 123);

        $response = $this->getList([ 
            'user' => $user,
            'juryType' => 'appeal',
        ])
            ->shouldBe(null);
    }

    function it_should_add_initial_jury_decision(Decision $decision)
    {
        $ts = (int) round(microtime(true) * 1000);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $params = $query['body']['script']['params']['decision'];
                return $params[0]['juror_guid'] === 456
                    && $params[0]['juror_hash'] === '0xw1k12'
                    && $params[0]['action'] === 'explicit'
                    && $params[0]['@timestamp'] === $ts
                    && $query['body']['upsert']['entity_guid'] === 123
                    && $query['body']['script']['inline'] === 'if (ctx._source.initial_jury === null) { 
                        ctx._source.initial_jury = [];
                    } 
                    ctx._source.initial_jury.add(params.decision)';
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $decision->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $decision->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $decision->getJurorGuid()
            ->shouldBeCalled()
            ->willReturn(456);

        $decision->getJurorHash()
            ->shouldBeCalled()
            ->willReturn('0xw1k12');

        $decision->getAction()
            ->shouldBeCalled()
            ->willReturn('explicit');

        $decision->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->add($decision)
            ->shouldBe(true);
    }

    function it_should_add_initial_jury_decision_as_overturned(Decision $decision)
    {
        $ts = (int) round(microtime(true) * 1000);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $params = $query['body']['script']['params']['decision'];
                return $params[0]['juror_guid'] === 456
                    && $params[0]['juror_hash'] === 'jurorHash'
                    && $params[0]['action'] === 'overturned'
                    && $params[0]['@timestamp'] === $ts
                    && $query['body']['upsert']['entity_guid'] === 123
                    && $query['body']['script']['inline'] === 'if (ctx._source.initial_jury === null) { 
                        ctx._source.initial_jury = [];
                    } 
                    ctx._source.initial_jury.add(params.decision)';
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $decision->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $decision->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
    
        $decision->getJurorGuid()
            ->shouldBeCalled()
            ->willReturn(456);

        $decision->getJurorHash()
            ->shouldBeCalled()
            ->willReturn('jurorHash');

        $decision->getAction()
            ->shouldBeCalled()
            ->willReturn('overturned');

        $decision->isAppeal()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->add($decision)
            ->shouldBe(true);
    }

    function it_should_add_appeal_jury_decision(Decision $decision)
    {
        $ts = (int) round(microtime(true) * 1000);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $params = $query['body']['script']['params']['decision'];
                return $params[0]['juror_guid'] === 456
                    && $params[0]['juror_hash'] === 'jurorHash'
                    && $params[0]['action'] === 'explicit'
                    && $params[0]['@timestamp'] === $ts
                    && $query['body']['upsert']['entity_guid'] === 123
                    && $query['body']['script']['inline'] === 'if (ctx._source.appeal_jury === null) { 
                        ctx._source.appeal_jury = [];
                    } 
                    ctx._source.appeal_jury.add(params.decision)';
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $decision->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $decision->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $decision->getJurorGuid()
            ->shouldBeCalled()
            ->willReturn(456);

        $decision->getJurorHash()
            ->shouldBeCalled()
            ->willReturn('jurorHash');

        $decision->getAction()
            ->shouldBeCalled()
            ->willReturn('explicit');

        $decision->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($decision)
            ->shouldBe(true);
    }

    function it_should_add_appeal_jury_decision_as_overturned(Decision $decision)
    {
        $ts = (int) round(microtime(true) * 1000);
        $this->es->request(Argument::that(function($prepared) use ($ts) {
                $query = $prepared->build();
                $params = $query['body']['script']['params']['decision'];
                return $params[0]['juror_guid'] === 456
                    && $params[0]['juror_hash'] === 'jurorHash'
                    && $params[0]['action'] === 'overturned'
                    && $params[0]['@timestamp'] === $ts
                    && $query['body']['upsert']['entity_guid'] === 123
                    && $query['body']['script']['inline'] === 'if (ctx._source.appeal_jury === null) { 
                        ctx._source.appeal_jury = [];
                    } 
                    ctx._source.appeal_jury.add(params.decision)';
            }))
            ->shouldBeCalled()
            ->willReturn(true);

        $decision->getTimestamp()
            ->shouldBeCalled()
            ->willReturn($ts);

        $report = new Report();
        $report->setEntityGuid(123);

        $decision->getReport()
            ->shouldBeCalled()
            ->willReturn($report);
        
        $decision->getJurorGuid()
            ->shouldBeCalled()
            ->willReturn(456);

        $decision->getJurorHash()
            ->shouldBeCalled()
            ->willReturn('jurorHash');

        $decision->getAction()
            ->shouldBeCalled()
            ->willReturn('overturned');

        $decision->isAppeal()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($decision)
            ->shouldBe(true);
    }
}
