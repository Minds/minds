<?php

namespace Spec\Minds\Core\Security\SpamBlocks;

use Minds\Core\Security\SpamBlocks\Manager;
use Minds\Core\Security\SpamBlocks\Repository;
use Minds\Core\Security\SpamBlocks\SpamBlock;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{

    private $repository;

    function let(Repository $repository)
    {
        $this->beConstructedWith($repository);
        $this->repository = $repository;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_return_true_if_spam_record_exists()
    {
        $model = new SpamBlock;
        $model->setKey('k1')
            ->setValue('v1');

        $this->repository->get('k1', 'v1')
            ->shouldBeCalled()
            ->willReturn($model);
        
        $this->isSpam($model)->shouldReturn(true);
    }

    function it_should_return_false_if_spam_record_does_not_exists()
    {
        $model = new SpamBlock;
        $model->setKey('k1')
            ->setValue('v1');

        $this->repository->get('k1', 'v1')
            ->shouldBeCalled()
            ->willReturn(false);
        
        $this->isSpam($model)->shouldReturn(false);
    }

    function it_should_add_a_record_to_the_repository()
    {
        $model = new SpamBlock;
        $model->setKey('k1')
            ->setValue('v1');

        $this->repository->add($model)
            ->shouldBeCalled();

        $this->add($model);
    }
}
