<?php

namespace Spec\Minds\Core\Hashtags\User;

use Minds\Core\Hashtags\HashtagEntity;
use Minds\Core\Hashtags\User\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RepositorySpec extends ObjectBehavior
{
    private $db;

    function let(\PDO $db)
    {
        $this->db = $db;

        $this->beConstructedWith($db);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_should_throw_an_exception_when_getting_hashtags_if_user_guid_isnt_set()
    {
        $this->shouldThrow(new \Exception('user_guid must be provided'))->during('getAll');
    }

    function it_should_get_all_hashtags(\PDOStatement $statement)
    {
        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute([100])
            ->shouldBeCalled();

        $statement->fetchAll(\PDO::FETCH_ASSOC)
            ->shouldBeCalled()
            ->willReturn([['hashtag' => 'hashtag1']]);

        $this->getAll(['user_guid' => 100])->shouldReturn([['hashtag' => 'hashtag1']]);
    }

    function it_should_add_a_user_hashtag(\PDOStatement $statement, HashtagEntity $hashtag)
    {
        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute([100, 'hashtag1'])
            ->shouldBeCalled()
            ->willReturn(true);

        $hashtag->getGuid()
            ->shouldBeCalled()
            ->willReturn(100);

        $hashtag->getHashtag()
            ->shouldBeCalled()
            ->willReturn('hashtag1');

        $this->add([$hashtag])->shouldReturn(true);
    }

    function it_should_try_to_add_any_hashtags_if_array_is_empty()
    {
        $this->add([])->shouldReturn(false);
    }

    function it_should_remove_a_user_hashtag(\PDOStatement $statement)
    {
        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute([100, 'hashtag1'])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->remove(100, ['hashtag1'])->shouldReturn(true);
    }
}
