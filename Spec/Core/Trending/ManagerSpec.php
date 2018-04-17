<?php

namespace Spec\Minds\Core\Trending;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Trending\Maps;
use Minds\Core\Trending\Aggregates\Aggregate;
use Minds\Core\Trending\EntityValidator;
use Minds\Core\Trending\Repository;
use Minds\Core\EntitiesBuilder;
use Minds\Entities\Entity;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Minds\Core\Trending\Manager');
    }

    function it_should_collect_and_store_trending_entities(
        Repository $repo,
        Aggregate $agg,
        Aggregate $aggImages,
        EntityValidator $validator,
        EntitiesBuilder $entitiesBuilder
    )
    {
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
        $this->beConstructedWith($repo, $validator, $maps, $entitiesBuilder);

        $entities = [
            1 => (new Entity)
                ->set('guid', 10)
                ->set('type', 'activity')
                ->set('perma_url', 'https://minds.com/blog/view/1000001')
                ->setRating(1),
            2 => (new Entity)
                ->set('guid', 20)
                ->setRating(1),
            3 => (new Entity)
                ->set('guid', 30)
                ->setRating(1),
        ];

        foreach ($entities as $entity) {
            $entitiesBuilder->single($entity->guid)
                ->willReturn($entity);
        }

        $validator->isValid($entities[1], 1)
            ->shouldBeCalled()
            ->willReturn(true);

        $validator->isValid($entities[2], 1)
            ->shouldBeCalled()
            ->willReturn(false);
        
        $validator->isValid($entities[3], 1)
            ->shouldBeCalled()
            ->willReturn(true);

        $validator->isValid($entities[1], 2)
            ->shouldBeCalled()
            ->willReturn(true);

        $validator->isValid($entities[2], 2)
            ->shouldBeCalled()
            ->willReturn(false);
        
        $validator->isValid($entities[3], 2)
            ->shouldBeCalled()
            ->willReturn(true);
        
        $agg->setType('activity')->shouldBeCalled();
        $agg->setSubtype('')->shouldBeCalled();
        $agg->setFrom(Argument::any())->shouldBeCalled();
        $agg->setTo(Argument::any())->shouldBeCalled();
        $agg->setLimit(500)->shouldBeCalled();

        $agg->get()
            ->shouldBeCalled()
            ->willReturn([
                10 => 5,
                20 => 10,
                30 => 10
            ]);

        $aggImages->setType('object')->shouldBeCalled();
        $aggImages->setSubtype('image')->shouldBeCalled();
        $aggImages->setFrom(Argument::any())->shouldBeCalled();
        $aggImages->setTo(Argument::any())->shouldBeCalled();
        $aggImages->setLimit(500)->shouldBeCalled();

        $aggImages->get()
            ->shouldBeCalled()
            ->willReturn([
                10 => 5,
            ]);

        $repo->add('newsfeed', [ 30, 10 ], 1)
            ->shouldBeCalled();

        $repo->add('newsfeed', [ 30, 10 ], 2)
            ->shouldBeCalled();

        $repo->add('images', [ 10 ], 1)
            ->shouldBeCalled();

        $repo->add('images', [ 10 ], 2)
            ->shouldBeCalled();
        
        $this->run();
    }
}
