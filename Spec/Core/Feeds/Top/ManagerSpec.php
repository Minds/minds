<?php

namespace Spec\Minds\Core\Feeds\Top;

use Minds\Core\EntitiesBuilder;
use Minds\Core\Feeds\Top\CachedRepository;
use Minds\Core\Feeds\Top\Manager;
use Minds\Core\Feeds\Top\Repository;
use Minds\Core\Feeds\Top\ScoredGuid;
use Minds\Entities\Entity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    /** @var Repository */
    protected $repository;

    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    /** @var CachedRepository */
    protected $cachedRepository;

    function let(
        Repository $repository,
        EntitiesBuilder $entitiesBuilder,
        CachedRepository $cachedRepository
    )
    {
        $this->repository = $repository;
        $this->entitiesBuilder = $entitiesBuilder;
        $this->cachedRepository = $cachedRepository;
        $this->beConstructedWith($repository, $entitiesBuilder, $cachedRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    public function it_should_get_list(
        ScoredGuid $scoredGuid1,
        ScoredGuid $scoredGuid2,
        Entity $entity1,
        Entity $entity2
    )
    {
        $scoredGuid1->getGuid()
            ->shouldBeCalled()
            ->willReturn(5000);

        $scoredGuid1->getScore()
            ->shouldBeCalled()
            ->willReturn(500);

        $entity1->get('guid')
            ->shouldBeCalled()
            ->willReturn(5000);

        $scoredGuid2->getGuid()
            ->shouldBeCalled()
            ->willReturn(5001);

        $scoredGuid2->getScore()
            ->shouldBeCalled()
            ->willReturn(800);

        $entity2->get('guid')
            ->shouldBeCalled()
            ->willReturn(5001);

        $this->cachedRepository->setKey('phpspec')
            ->shouldBeCalled()
            ->willReturn($this->cachedRepository);

        $this->cachedRepository->getList(Argument::withEntry('cache_key', 'phpspec'))
            ->shouldBeCalled()
            ->willReturn([ $scoredGuid1, $scoredGuid2 ]);

        $this->entitiesBuilder->get([ 'guids' => [ 5000, 5001 ] ])
            ->shouldBeCalled()
            ->willReturn([ $entity1, $entity2 ]);

        $this
            ->getList([
                'cache_key' => 'phpspec',
            ])
            ->shouldReturn([ $entity2, $entity1 ]);
    }
}
