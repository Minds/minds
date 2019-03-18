<?php

namespace Spec\Minds\Core\Feeds\Top;

use Minds\Core\EntitiesBuilder;
use Minds\Core\Feeds\Top\CachedRepository;
use Minds\Core\Feeds\Top\Manager;
use Minds\Core\Feeds\Top\Repository;
use Minds\Core\Feeds\Top\ScoredGuid;
use Minds\Core\Search\Search;
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

    /** @var Search */
    protected $search;

    function let(
        Repository $repository,
        EntitiesBuilder $entitiesBuilder,
        CachedRepository $cachedRepository,
        Search $search
    )
    {
        $this->repository = $repository;
        $this->entitiesBuilder = $entitiesBuilder;
        $this->cachedRepository = $cachedRepository;
        $this->search = $search;
        $this->beConstructedWith($repository, $entitiesBuilder, $cachedRepository, $search);
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

        $scoredGuid1->getOwnerGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $entity1->get('guid')
            ->shouldBeCalled()
            ->willReturn(5000);

        $scoredGuid2->getGuid()
            ->shouldBeCalled()
            ->willReturn(5001);

        $scoredGuid2->getScore()
            ->shouldBeCalled()
            ->willReturn(800);

        $scoredGuid2->getOwnerGuid()
            ->shouldBeCalled()
            ->willReturn(1000);

        $entity2->get('guid')
            ->shouldBeCalled()
            ->willReturn(5001);

        $this->repository->getList(Argument::withEntry('cache_key', 'phpspec'))
            ->shouldBeCalled()
            ->willReturn([$scoredGuid1, $scoredGuid2]);

        $this->entitiesBuilder->get(['guids' => [5000, 5001]])
            ->shouldBeCalled()
            ->willReturn([$entity1, $entity2]);

        $this
            ->getList([
                'cache_key' => 'phpspec',
            ])
            ->shouldReturn([$entity2, $entity1]);
    }
}
