<?php

namespace Spec\Minds\Core\Feeds\Suggested;

use Minds\Core\Feeds\Suggested\Repository;
use Minds\Entities\Activity;
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

    function it_should_throw_an_exception_when_getting_the_feed_if_user_guid_isnt_set()
    {
        $this->shouldThrow(new \Exception('user_guid must be provided'))->during('getFeed');
    }

    function it_should_throw_an_exception_when_getting_the_feed_if_type_isnt_set()
    {
        $this->shouldThrow(new \Exception('type must be provided'))->during('getFeed', [['user_guid' => '100']]);
    }

    function it_should_get_the_suggested_feed(Activity $activity, \PDOStatement $statement)
    {
        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute([
            100,
            'activity',
            1,
            12,
            0
        ])
            ->shouldBeCalled();

        $statement->fetchAll(\PDO::FETCH_ASSOC)
            ->shouldBeCalled()
            ->willReturn([$activity]);


        $this->getFeed(['user_guid' => 100, 'type' => 'activity'])->shouldReturn([$activity]);
    }

    function it_should_get_the_suggested_feed_filtering_by_hashtag(Activity $activity, \PDOStatement $statement)
    {
        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute([
            'hashtag',
            'activity',
            1,
            12,
            0
        ])
            ->shouldBeCalled();

        $statement->fetchAll(\PDO::FETCH_ASSOC)
            ->shouldBeCalled()
            ->willReturn([$activity]);


        $this->getFeed(['user_guid' => 100, 'type' => 'activity', 'hashtag' => 'hashtag'])->shouldReturn([$activity]);
    }

    function it_should_get_the_suggested_feed_ignoring_user_selected_hashtags(
        Activity $activity,
        \PDOStatement $statement
    ) {
        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute([
            'activity',
            1,
            12,
            0
        ])
            ->shouldBeCalled();

        $statement->fetchAll(\PDO::FETCH_ASSOC)
            ->shouldBeCalled()
            ->willReturn([$activity]);


        $this->getFeed(['user_guid' => 100, 'type' => 'activity', 'all' => true])->shouldReturn([$activity]);
    }

    function it_should_fail_to_add_add_an_entry_to_suggested_if_entity_guid_isnt_set()
    {
        $this->shouldThrow(new \Exception('entity_guid must be provided'))->during('add',
            [['score' => 100, 'rating' => 1]]);
    }

    function it_should_fail_to_add_add_an_entry_to_suggested_if_score_isnt_set()
    {
        $this->shouldThrow(new \Exception('score must be provided'))->during('add',
            [['entity_guid' => 100, 'rating' => 1]]);
    }

    function it_should_fail_to_add_add_an_entry_to_suggested_if_type_isnt_set()
    {
        $this->shouldThrow(new \Exception('type must be provided'))->during('add',
            [['entity_guid' => 100, 'score' => 100]]);
    }


    function it_should_fail_to_add_add_an_entry_to_suggested_if_rating_isnt_set()
    {
        $this->shouldThrow(new \Exception('rating must be provided'))->during('add',
            [['entity_guid' => 100, 'score' => 100, 'type' => 'image']]);
    }

    function it_should_add_an_entry_to_suggested(\PDOStatement $statement)
    {
        $this->db->prepare(Argument::any())
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute(Argument::any())
            ->shouldBeCalled()
            ->willReturn(true);

        $this->add(['entity_guid' => 100, 'score' => 100, 'rating' => 1, 'type' => 'image'])->shouldReturn(true);
    }

    function it_should_fail_to_remove_from_suggested_table_if_type_isnt_set()
    {
        $this->shouldThrow(new \Exception('type must be provided'))->during('removeAll', [null]);
    }

    function it_should_truncate_suggested_table(\PDOStatement $statement)
    {
        $this->db->prepare("TRUNCATE suggested")
            ->shouldBeCalled()
            ->willReturn($statement);

        $statement->execute()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->removeAll('all')->shouldReturn(true);
    }

    function it_should_delete_all_entities_with_a_specific_type_from_the_suggested_table(
        \PDOStatement $statement1,
        \PDOStatement $statement2
    ) {
        $this->db->prepare("SELECT suggested.guid AS guid
                        FROM suggested
                        JOIN entity_hashtags
                          ON suggested.guid = entity_hashtags.guid
                        WHERE suggested.type = ?")
            ->shouldBeCalled()
            ->willReturn($statement1);

        $statement1->execute(['activity'])
            ->shouldBeCalled();

        $statement1->fetchAll(\PDO::FETCH_ASSOC)
            ->shouldBeCalled()
            ->willReturn([['guid' => 1], ['guid' => 2]]);

        $this->db->prepare("DELETE FROM suggested WHERE guid IN (?,?)")
            ->shouldBeCalled()
            ->willReturn($statement2);

        $statement2->execute([1, 2])
            ->shouldBeCalled()
            ->willReturn(true);

        $this->removeAll('activity')->shouldReturn(true);
    }
}
