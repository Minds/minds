<?php

namespace Spec\Minds\Core\Boost\Network;

use Minds\Core\Boost\Network\ElasticRepository;
use Minds\Core\Boost\Network\Boost;
use Minds\Core\Data\ElasticSearch\Client as Elastic;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElasticRepositorySpec extends ObjectBehavior
{
    private $es;

    function let(Elastic $es)
    {
        $this->beConstructedWith($es);
        $this->es = $es;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ElasticRepository::class);
    }

    function it_should_add()
    {
        $boost = (new Boost())
            ->setCreatedTimestamp(time() * 1000)
            ->setCompletedTimestamp(time() * 1000)
            ->setReviewedTimestamp(time() * 1000)
            ->setRevokedTimestamp(time() * 1000)
            ->setRejectedTimestamp(time() * 1000)
            ->setBid(10 * (10**18))
            ->setBidType('tokens')
            ->setEntityGuid(123)
            ->setImpressions(10000)
            ->setImpressionsMet(10)
            ->setOwnerGuid(456)
            ->setRating(1)
            ->setType('newsfeed')
            ->setPriority(false);

        $this->es->request(Argument::that(function($prepared) {
            $body = $prepared->build()['body'];
            
            return round($body['doc']['@timestamp'], -5) === round(time() * 1000, -5)
                && round($body['doc']['@completed'], -5) === round(time() * 1000, -5)
                && round($body['doc']['@reviewed'], -5) === round(time() * 1000, -5)
                && round($body['doc']['@revoked'], -5) === round(time() * 1000, -5)
                && round($body['doc']['@rejected'], -5) === round(time() * 1000, -5)
                && $body['doc']['bid'] == 10
                && $body['doc']['bid_type'] === 'offchain'
                && $body['doc']['entity_guid'] === 123
                && $body['doc']['impressions'] === 10000
                && $body['doc']['impressions_met'] === 10
                && $body['doc']['owner_guid'] === 456
                && $body['doc']['type'] === 'newsfeed'
                && $body['doc']['priority'] === false;
        }))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add($boost)
            ->shouldReturn(true);
    }

}
