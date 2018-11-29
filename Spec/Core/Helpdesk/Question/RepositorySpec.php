<?php

namespace Spec\Minds\Core\Helpdesk\Question;

use Minds\Core\Helpdesk\Question\Question;
use Minds\Core\Helpdesk\Question\Repository;
use Minds\Common\Repository\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    private $db;
    private $categoryRepo;

    function let(\PDO $db, \Minds\Core\Helpdesk\Category\Repository $categoryRepo)
    {
        $this->db = $db;
        $this->categoryRepo = $categoryRepo;

        $this->beConstructedWith($db, $categoryRepo);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_get_list_of_questions(\PDOStatement $statement)
    {
        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $statement->fetchAll(\PDO::FETCH_ASSOC)
            ->shouldBeCalled()
            ->willReturn([
                [
                    'uuid' => 'uuid1',
                    'question' => 'Is this a test?',
                    'answer' => 'no',
                    'category_uuid' => null,
                    'user_guids' => '{}',
                    'thumbs_up_count' => 0,
                    'thumbs_down_count' => 0
                ],
                [
                    'uuid' => 'uuid2',
                    'question' => 'Is this a test?',
                    'answer' => 'no',
                    'category_uuid' => null,
                    'user_guids' => '{}',
                    'thumbs_up_count' => 0,
                    'thumbs_down_count' => 0
                ],
            ]);

        $this->getList()->shouldBeAnInstanceOf(Response::class);
        $this->getList()[0]->shouldBeAnInstanceOf(Question::class);
    }

    function it_should_return_a_single_question(\PDOStatement $statement)
    {
        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $statement->fetch(\PDO::FETCH_ASSOC)
            ->shouldBeCalled()
            ->willReturn([
                    'uuid' => 'uuid1',
                    'question' => 'Is this a test?',
                    'answer' => 'yes',
                    'category_uuid' => null,
                    'voted' => 'up',
                ]);

        $question = $this->get('uuid1', 'user_guid1');
        
        $question->shouldBeAnInstanceOf(Question::class);
        $question->getUuid()
            ->shouldBe('uuid1');
        $question->getQuestion()
            ->shouldBe('Is this a test?');
        $question->getAnswer()
            ->shouldBe('yes');
    }

}
