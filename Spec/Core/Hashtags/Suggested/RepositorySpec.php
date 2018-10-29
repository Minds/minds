<?php

namespace Spec\Minds\Core\Hashtags\Suggested;

use Minds\Core\Hashtags\Suggested\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    protected $db;

    function let(\PDO $db)
    {
        $this->db = $db;

        $this->beConstructedWith($db);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_throw_an_exception_when_getting_the_suggested_hashtags_if_user_guid_isnt_set()
    {
        $this->shouldThrow(new \Exception('user_guid must be provided'))->during('getAll');
    }

    function it_should_get_the_suggested_hashtags(\PDOStatement $statement)
    {
        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute([date('c', strtotime('24 hours ago')), 100])
            ->shouldBeCalled();

        $statement->fetchAll(\PDO::FETCH_ASSOC)
            ->shouldBeCalled()
            ->willReturn([
                ['value' => 'hashtag1', 'selected' => true],
                ['value' => 'hashtag2', 'selected' => false]
            ]);

        $this->getAll(['user_guid' => 100])->shouldReturn([
            ['value' => 'hashtag1', 'selected' => true],
            ['value' => 'hashtag2', 'selected' => false]
        ]);
    }
}
