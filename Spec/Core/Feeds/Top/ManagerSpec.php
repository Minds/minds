<?php

namespace Spec\Minds\Core\Feeds\Top;

use Minds\Common\Repository\Response;
use Minds\Core\EntitiesBuilder;
use Minds\Core\Feeds\Top\Manager;
use Minds\Core\Feeds\Top\Repository;
use Minds\Core\Feeds\Top\ScoredGuid;
use Minds\Core\Search\Search;
use Minds\Entities\Entity;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    /** @var Repository */
    protected $repository;

    /** @var EntitiesBuilder */
    protected $entitiesBuilder;

    /** @var Search */
    protected $search;

    function let(
        Repository $repository,
        EntitiesBuilder $entitiesBuilder,
        Search $search
    )
    {
        $this->repository = $repository;
        $this->entitiesBuilder = $entitiesBuilder;
        $this->search = $search;
        $this->beConstructedWith($repository, $entitiesBuilder, $search);
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

        $scoredGuid1->getTimestamp()
            ->shouldBeCalled()
            ->willReturn(2);

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
            ->willReturn(1001);

        $scoredGuid2->getTimestamp()
            ->shouldBeCalled()
            ->willReturn(1);

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
            ->shouldBeAResponse([$entity2, $entity1]);
    }

    public function it_should_get_list_by_query(
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

        $scoredGuid1->getTimestamp()
            ->shouldBeCalled()
            ->willReturn(2);

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
            ->willReturn(1001);

        $scoredGuid2->getTimestamp()
            ->shouldBeCalled()
            ->willReturn(1);

        $entity2->get('guid')
            ->shouldBeCalled()
            ->willReturn(5001);

        $this->repository->getList(Argument::withEntry('query', 'activity with hashtags'))
            ->shouldBeCalled()
            ->willReturn([$scoredGuid1, $scoredGuid2]);

        $this->entitiesBuilder->get(['guids' => [5000, 5001]])
            ->shouldBeCalled()
            ->willReturn([$entity1, $entity2]);

        $this
            ->getList([
                'query' => 'Activity with #hashtags',
            ])
            ->shouldBeAResponse([$entity2, $entity1]);
    }

    function getMatchers()
    {
        $matchers = [];

        $matchers['beAResponse'] = function ($subject, $elements = null) {
            if (!($subject instanceof Response)) {
                throw new FailureException("Subject should be a Response");
            }

            if ($elements !== null && $elements !== $subject->toArray()) {
                throw new FailureException("Subject elements don't match");
            }

            return true;
        };

        return $matchers;
    }
}
