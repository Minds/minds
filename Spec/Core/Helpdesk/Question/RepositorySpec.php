<?php

namespace Spec\Minds\Core\Helpdesk\Question;

use Cassandra\Set;
use Cassandra\Type;
use Cassandra\Uuid;
use Minds\Core\Data\Cassandra;
use Minds\Core\Data\ElasticSearch;
use Minds\Core\Helpdesk\Question\Question;
use Minds\Core\Helpdesk\Question\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Spec\Minds\Mocks\Cassandra\Rows;

class RepositorySpec extends ObjectBehavior
{
    /** @var Cassandra\Client */
    private $cassandraClient;
    /** @var ElasticSearch\Client */
    private $esClient;
    /** @var \Minds\Core\Helpdesk\Category\Repository */
    private $categoryRepo;

    function let(
        Cassandra\Client $cassandraClient,
        ElasticSearch\Client $esClient,
        \Minds\Core\Helpdesk\Category\Repository $categoryRepo
    )
    {
        $this->cassandraClient = $cassandraClient;
        $this->esClient = $esClient;
        $this->categoryRepo = $categoryRepo;

        $this->beConstructedWith($cassandraClient, $esClient, $categoryRepo);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_get_list_of_questions()
    {
        $this->cassandraClient->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn(new Rows([
                [
                    'uuid' => new Uuid('f990a87d-1255-42e5-a78e-4a2256569e8a'),
                    'question' => 'Is this a test?',
                    'answer' => 'no',
                    'category_uuid' => null,
                    'thumbs_up' => new Set(Type::varint()),
                    'thumbs_down' => new Set(Type::varint()),
                    'score' => 1,
                ],
                [
                    'uuid' => new Uuid('f990a87d-1255-42e5-a78e-4a2256569e8b'),
                    'question' => 'Is this a test?',
                    'answer' => 'no',
                    'category_uuid' => null,
                    'thumbs_up' => new Set(Type::varint()),
                    'thumbs_down' => new Set(Type::varint()),
                    'score' => 1,
                ],
            ], ''));

        $this->getList()[0]->shouldBeAnInstanceOf(Question::class);
    }

    function it_should_return_a_single_question()
    {
        $this->cassandraClient->request(Argument::any())
            ->shouldBeCalled()
            ->willReturn(new Rows([[
                'uuid' => new Uuid('f990a87d-1255-42e5-a78e-4a2256569e8a'),
                'question' => 'Is this a test?',
                'answer' => 'yes',
                'category_uuid' => null,
                'votes_up' => new Set(Type::varint()),
                'votes_down' => new Set(Type::varint()),
            ]], ''));

        $question = $this->get('f990a87d-1255-42e5-a78e-4a2256569e8a', 'user_guid1');

        $question->shouldBeAnInstanceOf(Question::class);
        $question->getUuid()
            ->shouldBe('f990a87d-1255-42e5-a78e-4a2256569e8a');
        $question->getQuestion()
            ->shouldBe('Is this a test?');
        $question->getAnswer()
            ->shouldBe('yes');
    }

}
