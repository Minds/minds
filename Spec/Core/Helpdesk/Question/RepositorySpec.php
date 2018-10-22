<?php

namespace Spec\Minds\Core\Helpdesk\Question;

use Minds\Core\Helpdesk\Entities\Question;
use Minds\Core\Helpdesk\Question\Repository;
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

    function it_should_get_all(\PDOStatement $statement)
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

        $this->getAll()->shouldBeArray();
        $this->getAll()[0]->shouldBeAnInstanceOf(Question::class);
    }
}
