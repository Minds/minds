<?php

namespace Spec\Minds\Core\Reports\Jury;

use Minds\Core\Reports\Jury\Repository;
use Minds\Core\Data\Cassandra\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    private $client;
    private $reportsRepository;

    function let(Client $client)
    {
        $this->beConstructedWith($client);
        $this->client = $client;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    
    /*function it_should_return_reports_we_can_use_in_jury()
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
    }*/

}
