<?php

namespace Spec\Minds\Core\Feeds\Firehose;

use PhpSpec\ObjectBehavior;
use Minds\Entities\User;
use Minds\Core\Feeds\Firehose\Manager;
use Minds\Common\Repository\Response;
use Minds\Entities\Activity;
use Minds\Entities\Entity;
use Minds\Core\Feeds\Top\Manager as TopFeedsManager;
use Minds\Core\Feeds\Firehose\ModerationCache;

class ManagerSpec extends ObjectBehavior
{
    /** @var User */
    protected $user;
    /** @var TopFeedsManager */
    protected $topFeedsManager;
    /** @var ModerationCache */
    protected $moderationCache;

    protected $guids = [
        '968599624820461570', '966142563226488850', '966145446911152135',
        '966146759803801618', '968594045251096596', '966031787253829640',
        '966032235331325967', '966030585254383635', '966020677003907088',
        '966042003450105868',
    ];

    public function let(
        User $user,
        TopFeedsManager $topFeedsManager,
        ModerationCache $moderationCache)
    {
        $this->user = $user;
        $this->topFeedsManager = $topFeedsManager;
        $this->moderationCache = $moderationCache;

        $this->user->getGUID()->willReturn('123');
        $this->beConstructedWith(
            $this->topFeedsManager,
            $this->moderationCache
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
        ])->shouldBeLike($response);
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

        $this->getList()->shouldBeLike($response);
    }

    public function it_should_save_moderated_activites(Entity $activity)
    {
        $time = time();
        $activity->getNsfw()->shouldBeCalled()->willReturn([]);
        $activity->getNsfwLock()->shouldBeCalled()->willReturn([]);
        $activity->getOwnerEntity()->shouldBeCalled()->willReturn(null);
        $activity->getContainerEntity()->shouldBeCalled()->willReturn(null);
        $activity->setNsfw([])->shouldBeCalled();
        $activity->setModeratorGuid('123')->shouldBeCalled();
        $activity->setTimeModerated($time)->shouldBeCalled();
        $activity->save()->shouldBeCalled();

        $this->save($activity, $this->user, null, null, $time);
    }

    public function it_should_save_reported_activites(Entity $activity)
    {
        $time = time();

        $activity->getNsfw()->shouldBeCalled()->willReturn([]);
        $activity->getNsfwLock()->shouldBeCalled()->willReturn([]);
        $activity->getOwnerEntity()->shouldBeCalled()->willReturn(null);
        $activity->getContainerEntity()->shouldBeCalled()->willReturn(null);
        $activity->setNsfw([])->shouldBeCalled();
        $activity->setModeratorGuid('123')->shouldBeCalled();
        $activity->setTimeModerated($time)->shouldBeCalled();
        $activity->save()->shouldBeCalled();

        $this->save($activity, $this->user, 1, 1, $time);
    }

    private function getMockActivities(bool $moderated = false)
    {
        $entities = [];

        $entity = new Activity();
        $entity->guid = 123;
        $entities[] = $entity;

        $entity = new Activity();
        $entity->guid = 456;
        $entities[] = $entity;

        return $entities;
    }
}
