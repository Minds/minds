<?php

namespace Spec\Minds\Core\Feeds\Suggested;

use Minds\Core\EntitiesBuilder;
use Minds\Core\Feeds\Suggested\Manager;
use Minds\Core\Feeds\Suggested\Repository;
use Minds\Core\Trending\Aggregates\Aggregate;
use Minds\Core\Trending\EntityValidator;
use Minds\Entities\Activity;
use Minds\Entities\Entity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    private $repo;
    private $entityHashtagsRepo;
    private $agg;
    private $aggImages;
    private $validator;
    private $entitiesBuilder;

    function let(
        Repository $repo,
        \Minds\Core\Hashtags\Entity\Repository $entityHashtagsRepo,
        Aggregate $agg,
        Aggregate $aggImages,
        EntityValidator $validator,
        EntitiesBuilder $entitiesBuilder
    ) {
        $this->repo = $repo;
        $this->agg = $agg;
        $this->aggImages = $aggImages;
        $this->entityHashtagsRepo = $entityHashtagsRepo;
        $this->validator = $validator;
        $maps = [
            'newsfeed' => [
                'type' => 'activity',
                'subtype' => '',
                'aggregates' => [
                    $agg
                ]
            ],
            'images' => [
                'type' => 'object',
                'subtype' => 'image',
                'aggregates' => [
                    $aggImages
                ]
            ]
        ];
        $this->entitiesBuilder = $entitiesBuilder;

        $this->beConstructedWith($repo, $entityHashtagsRepo, $validator, $maps, $entitiesBuilder);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_get_an_empty_feed()
    {
        $this->repo->getFeed([
            'user_guid' => 100,
            'offset' => 0,
            'limit' => 12,
            'rating' => 2,
            'type' => 'activity',
            'all' => false
        ])
            ->shouldBeCalled()
            ->willReturn([]);

        $this->getFeed(['user_guid' => 100, 'type' => 'activity', 'rating' => 2, 'all' => false])->shouldReturn([]);
    }

    function it_should_get_the_suggested_feed(Activity $activity1, Activity $activity2)
    {
        $this->repo->getFeed([
            'user_guid' => 100,
            'offset' => 0,
            'limit' => 12,
            'rating' => 2,
            'type' => 'activity',
            'all' => false
        ])
            ->shouldBeCalled()
            ->willReturn([['guid' => 1], ['guid' => 2]]);

        $this->entitiesBuilder->get(['guids' => [1, 2]])
            ->shouldBeCalled()
            ->willReturn([$activity1, $activity2]);

        $this->getFeed(['user_guid' => 100, 'type' => 'activity', 'rating' => 2, 'all' => false])->shouldReturn([
            $activity1,
            $activity2
        ]);
    }

    function it_should_collect_and_store_suggested_entities()
    {
        $entities = [
            1 => (new Entity)
                ->set('guid', 10)
                ->set('type', 'activity')
                ->set('perma_url', 'https://minds.com/blog/view/1000001')
                ->set('message', '#hashtag')
                ->setRating(1),
            2 => (new Entity)
                ->set('guid', 20)
                ->set('type', 'activity')
                ->set('message', '#hashtag')
                ->setRating(1),
            3 => (new Entity)
                ->set('guid', 30)
                ->set('type', 'activity')
                ->set('message', '#hashtag')
                ->setRating(1),
        ];

        foreach ($entities as $entity) {
            $this->entitiesBuilder->single($entity->guid)
                ->willReturn($entity);
        }

        $this->validator->isValid(Argument::any())
            ->shouldBeCalledTimes(4)
            ->willReturn(true);

        $this->agg->setType('activity')->shouldBeCalled();
        $this->agg->setSubtype('')->shouldBeCalled();
        $this->agg->setFrom(Argument::any())->shouldBeCalled();
        $this->agg->setTo(Argument::any())->shouldBeCalled();
        $this->agg->setLimit(10000)->shouldBeCalled();
        $this->agg->get()
            ->shouldBeCalled()
            ->willReturn([
                10 => 5,
                20 => 10,
                30 => 10
            ]);
        $this->aggImages->setType('object')->shouldBeCalled();
        $this->aggImages->setSubtype('image')->shouldBeCalled();
        $this->aggImages->setFrom(Argument::any())->shouldBeCalled();
        $this->aggImages->setTo(Argument::any())->shouldBeCalled();
        $this->aggImages->setLimit(10000)->shouldBeCalled();
        $this->aggImages->get()
            ->shouldBeCalled()
            ->willReturn([
                10 => 5,
            ]);
        $this->repo->add(Argument::any())
            ->shouldBeCalledTimes(7);

        $this->run('all');
    }
}
