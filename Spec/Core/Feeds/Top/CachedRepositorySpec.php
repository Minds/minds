<?php

namespace Spec\Minds\Core\Feeds\Top;

use Generator;
use Minds\Common\Repository\Response;
use Minds\Core\Data\SortedSet;
use Minds\Core\Feeds\Top\CachedRepository;
use Minds\Core\Feeds\Top\Repository;
use Minds\Core\Feeds\Top\ScoredGuid;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CachedRepositorySpec extends ObjectBehavior
{
    /** @var Repository */
    protected $repository;

    /** @var SortedSet */
    protected $sortedSet;

    function let(
        Repository $repository,
        SortedSet $sortedSet
    )
    {
        $this->repository = $repository;
        $this->sortedSet = $sortedSet;

        $this->beConstructedWith($repository, $sortedSet);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CachedRepository::class);
    }

    function it_should_get_list_ignoring_cache()
    {
        $this->repository->getList([
            'limit' => 2,
            'type' => 'spec',
            'period' => '1d',
            'algorithm' => 'test',
            'rating' => 1
        ])
            ->shouldBeCalled()
            ->willReturn([1000]);

        $this->sortedSet->setkey(Argument::cetera())
            ->shouldNotBeCalled();

        $this
            ->getList([
                'limit' => 2,
                'type' => 'spec',
                'period' => '1d',
                'algorithm' => 'test',
                'rating' => 1
            ])
            ->shouldReturn([1000]);
    }

    function it_should_get_list_and_save_to_cache_when_no_offset(
        ScoredGuid $scoredGuid1,
        ScoredGuid $scoredGuid2
    )
    {
        $this->sortedSet->setKey(Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($this->sortedSet);

        $this->sortedSet->setThrottle(Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn();

        $this->sortedSet->isThrottled()
            ->shouldBeCalled()
            ->willReturn(false);

        $this->sortedSet->clean()
            ->shouldBeCalled()
            ->willReturn($this->sortedSet);

        $this->repository->getList([
            'limit' => 1000,
            'offset' => 0,
            'type' => 'spec',
            'period' => '1d',
            'algorithm' => 'test',
            'rating' => 1
        ])
            ->shouldBeCalled()
            ->willReturn([$scoredGuid1, $scoredGuid2]);

        $this->sortedSet->lazyAdd(0, Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->sortedSet->lazyAdd(1, Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->sortedSet->flush(Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(true);

        $this->sortedSet->flush()
            ->shouldBeCalled()
            ->willReturn(true);

        $this->sortedSet->expiresIn(Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn(true);

        $this
            ->setKey('phpspec')
            ->getList([
                'limit' => 2,
                'type' => 'spec',
                'period' => '1d',
                'algorithm' => 'test',
                'rating' => 1
            ])
            ->shouldReturn([
                $scoredGuid1,
                $scoredGuid2
            ]);
    }

    function it_should_get_list_from_cache_when_there_is_an_offset(Response $response)
    {
        $this->sortedSet->setKey(Argument::type('string'))
            ->shouldBeCalled()
            ->willReturn($this->sortedSet);

        $this->sortedSet->setThrottle(Argument::type('int'))
            ->shouldBeCalled()
            ->willReturn();

        $this->sortedSet->isThrottled()
            ->willReturn(false);

        $this->sortedSet->fetch(2, 2)
            ->shouldBeCalled()
            ->willReturn($response);

        $response->toArray()
            ->shouldBeCalled()
            ->willReturn([
                '1000:1',
                '1001:2'
            ]);

        $this
            ->setKey('phpspec')
            ->getList([
                'limit' => 2,
                'offset' => 2,
                'type' => 'spec',
                'period' => '1d',
                'algorithm' => 'test',
                'rating' => 1
            ])
            ->shouldReturnArrayOfScoredGuids();
    }

    public function getMatchers()
    {
        return [
            'returnArrayOfScoredGuids' => function ($array) {

                if (!is_array($array)) {
                    return false;
                }

                foreach ($array as $item) {
                    if (!($item instanceof ScoredGuid)) {
                        return false;
                    }
                }

                return true;
            }
        ];
    }
}
