<?php

namespace Spec\Minds\Core\Feeds\Firehose;

use PhpSpec\ObjectBehavior;
use Minds\Entities\User;
use Minds\Core\Feeds\Firehose\Manager;
use Minds\Common\Repository\Response;
use Minds\Entities\Activity;
use Minds\Entities\Entity;
use Minds\Core\Blogs\Blog;
use Minds\Entities\Image;
use Minds\Core\Feeds\Top\Manager as TopFeedsManager;
use Minds\Core\Feeds\Firehose\ModerationCache;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Data\Call;
use Minds\Core\Entities\Actions\Save;
use Minds\Core\Feeds\FeedSyncEntity;

class ManagerSpec extends ObjectBehavior
{
    /** @var User */
    protected $user;
    /** @var TopFeedsManager */
    protected $topFeedsManager;
    /** @var ModerationCache */
    protected $moderationCache;
    /** @var EntitiesBuilder */
    protected $entitiesBuilder;
    /** @var Call */
    protected $db;
    /** @var Save */
    protected $save;

    protected $guids = [
        '968599624820461570', '966142563226488850', '966145446911152135',
        '966146759803801618', '968594045251096596', '966031787253829640',
        '966032235331325967', '966030585254383635', '966020677003907088',
        '966042003450105868',
    ];

    public function let(
        User $user,
        TopFeedsManager $topFeedsManager,
        ModerationCache $moderationCache,
        EntitiesBuilder $entitiesBuilder,
        Call $db,
        Save $save
    ) {
        $this->user = $user;
        $this->topFeedsManager = $topFeedsManager;
        $this->moderationCache = $moderationCache;
        $this->entitiesBuilder = $entitiesBuilder;
        $this->db = $db;
        $this->save = $save;

        $this->user->getGUID()->willReturn(123);

        $this->beConstructedWith(
            $this->topFeedsManager,
            $this->moderationCache,
            $this->entitiesBuilder,
            $this->db,
            $this->save
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    public function it_should_return_results()
    {
        $activities = $this->getMockActivities();
        /** @var Response $response */
        $response = new Response($activities);

        $this->topFeedsManager->getList([
                'moderation_user' => $this->user,
                'exclude_moderated' => true,
                'moderation_reservations' => null,
            ])
            ->shouldBeCalled()
            ->willReturn($response);

        $this->moderationCache->getKeysLockedByOtherUsers($this->user)
            ->shouldBeCalled();

        $this->moderationCache->store('123', $this->user)
            ->shouldBeCalled();

        $this->moderationCache->store('456', $this->user)
            ->shouldBeCalled();

        $this->getList([
            'moderation_user' => $this->user,
        ])->shouldBeLike($response->map(function($entity) {
            return $entity->getEntity();
        }));
    }

    public function it_should_return_results_without_a_user()
    {
        $activities = $this->getMockActivities();
        /** @var Response $reponse */
        $response = new Response($activities);
        $activities = $this->getMockActivities($activities);

        $this->topFeedsManager->getList([
            'moderation_user' => null,
            'exclude_moderated' => true,
            'moderation_reservations' => null,
        ])
        ->shouldBeCalled()
        ->willReturn($response);

        $this->getList()->shouldBeLike($response->map(function($entity) {
            return $entity->getEntity();
        }));
    }

    public function it_should_save_moderated_activites(Entity $activity)
    {
        $time = time();
        $this->db->getRow('activity:entitylink:1')->shouldBeCalled()->willReturn([]);
        $activity->getType()->shouldBeCalled()->willReturn('activity');
        $activity->get('entity_guid')->shouldBeCalled()->willReturn(false);
        $activity->getGUID()->shouldBeCalled()->willReturn(1);
        $activity->setModeratorGuid('123')->shouldBeCalled();
        $activity->setTimeModerated($time)->shouldBeCalled();
        $this->save->setEntity($activity)->shouldBeCalled()->willReturn($this->save);
        $this->save->save()->shouldBeCalled();
        $this->save($activity, $this->user, $time);
    }

    public function it_should_save_reported_activites(Entity $activity)
    {
        $time = time();
        $this->db->getRow('activity:entitylink:1')->shouldBeCalled()->willReturn([]);
        $activity->getType()->shouldBeCalled()->willReturn('activity');
        $activity->get('entity_guid')->shouldBeCalled()->willReturn(false);
        $activity->getGUID()->shouldBeCalled()->willReturn(1);
        $activity->setTimeModerated($time)->shouldBeCalled();
        $activity->setModeratorGuid('123')->shouldBeCalled();
        $this->save->setEntity($activity)->shouldBeCalled()->willReturn($this->save);
        $this->save->save()->shouldBeCalled();
        $this->save($activity, $this->user, $time);
    }

    public function it_should_save_an_attachment(Entity $activity, Image $image)
    {
        $time = time();
        $image->setModeratorGuid(123)->shouldBeCalled();
        $image->setTimeModerated($time)->shouldBeCalled();
        $this->db->getRow('activity:entitylink:1')->shouldBeCalled()->willReturn([]);
        $this->entitiesBuilder->single(1)->shouldBeCalled()->willReturn($image);
        $activity->getType()->shouldBeCalled()->willReturn('activity');
        $activity->get('entity_guid')->shouldBeCalled()->willReturn(1);
        $activity->getGUID()->shouldBeCalled()->willReturn(1);
        $activity->setTimeModerated($time)->shouldBeCalled();
        $activity->setModeratorGuid(123)->shouldBeCalled();
        $this->save->setEntity($activity)->shouldBeCalled()->willReturn($this->save);
        $this->save->setEntity($image)->shouldBeCalled()->willReturn($this->save);
        $this->save->save()->shouldBeCalled();
        $this->save($activity, $this->user, $time);
    }

    public function it_should_save_a_blog(Blog $blog)
    {
        $time = time();
        $this->db->getRow('activity:entitylink:1')->shouldBeCalled()->willReturn([]);
        $blog->getType()->shouldBeCalled()->willReturn('object');
        $blog->getGuid()->shouldBeCalled()->willReturn(1);
        $blog->setTimeModerated($time)->shouldBeCalled();
        $blog->setModeratorGuid('123')->shouldBeCalled();
        $this->save->save()->shouldBeCalled();
        $this->save->setEntity($blog)->shouldBeCalled()->willReturn($this->save);
        $this->save($blog, $this->user, $time);
    }

    public function it_should_save_a_linked_entity(Entity $activity, Entity $parent)
    {
        $time = time();
        $parent->setTimeModerated($time)->shouldBeCalled();
        $parent->setModeratorGuid('123')->shouldBeCalled();
        $this->db->getRow('activity:entitylink:1')->shouldBeCalled()
            ->willReturn([2 => $parent]);
        $this->entitiesBuilder->single(2)->shouldBeCalled()->willReturn($parent);
        $activity->getType()->shouldBeCalled()->willReturn('activity');
        $activity->get('entity_guid')->shouldBeCalled()->willReturn(false);
        $activity->getGUID()->shouldBeCalled()->willReturn(1);
        $activity->setTimeModerated($time)->shouldBeCalled();
        $activity->setModeratorGuid('123')->shouldBeCalled();
        $this->save->setEntity($activity)->shouldBeCalled()->willReturn($this->save);
        $this->save->setEntity($parent)->shouldBeCalled()->willReturn($this->save);
        $this->save->save()->shouldBeCalled();
        $this->save($activity, $this->user, $time);
    }

    private function getMockActivities(bool $moderated = false)
    {
        $entities = [];

        $entity = new FeedSyncEntity();
        $activity = new Activity();
        $activity->guid = 123;
        $entities[] = $entity->setEntity($activity);

        $entity = new FeedSyncEntity();
        $activity = new Activity();
        $activity->guid = 456;
        $entities[] = $entity->setEntity($activity);

        return $entities;
    }
}
