<?php

namespace Spec\Minds\Core\Suggestions;

use Minds\Common\Repository\Response;
use Minds\Core\Data\ElasticSearch\Prepared\Search as Prepared;
use Minds\Core\Suggestions\Repository;
use Minds\Core\Suggestions\Suggestion;
use Minds\Core\Data\ElasticSearch\Client;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_return_a_list_of_suggestions(Client $es)
    {
        $this->beConstructedWith($es);

        $es->request(Argument::that(function($prepared) {
            $query = $prepared->build();
            $must = $query['body']['query']['bool']['must'];
            $must_not = $query['body']['query']['bool']['must_not'];

            return $must[0]['terms']['user_guid.keyword']['id'] == 123
                && $must_not[0]['terms']['entity_guid.keyword']['id'] == 123
                && $must_not[2]['terms']['entity_guid.keyword']['id'] == 123;
        }))
            ->shouldBeCalled()
            ->willReturn([
                'aggregations' => [
                    'subscriptions' => [
                        'buckets' => [
                            [
                                'doc_count' => 2,
                                'key' => 123,
                            ],
                            [
                                'doc_count' => 5,
                                'key' => 456,
                            ],
                        ],
                    ],
                ],
            ]);

        $response = $this->getList([
            'limit' => 5,
            'offset' => 0,
            'user_guid' => 123,
        ]);

        $response[0]->getEntityGuid()
            ->shouldBe(123);
        $response[0]->getEntityType()
            ->shouldBe('user');
        $response[0]->getConfidenceScore()
            ->shouldBe(2);
        
        $response[1]->getEntityGuid()
            ->shouldBe(456);
        $response[1]->getEntityType()
            ->shouldBe('user');
        $response[1]->getConfidenceScore()
            ->shouldBe(5);
    }

}
