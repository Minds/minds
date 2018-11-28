<?php

namespace Spec\Minds\Core\Experiments;

use Minds\Core\Experiments\Manager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Experiments\Sampler;
use Minds\Core\Experiments\Bucket;
use Minds\Core\Experiments\Hypotheses\Homepage121118;

class ManagerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Manager::class);
    }

    function it_should_return_a_list_of_experiments()
    {
        $this->getExperiments()->shouldHaveCount(1);
    }

    function it_should_return_bucket_for_experiment(
        Sampler $sampler,
        Homepage121118 $hypothesis
    )
    {
        $this->beConstructedWith($sampler);

        $sampler->setUser(null)
            ->shouldBeCalled();

        $sampler->setHypothesis(new Homepage121118)
            ->shouldBeCalled()
            ->willReturn($sampler);

        $bucket = new Bucket();
        $bucket->setId('variant1')
            ->setWeight(10);

        $sampler->getBucket()
            ->shouldBeCalled()
            ->willReturn($bucket);

        $this->getBucketForExperiment('Homepage121118')
            ->shouldReturn($bucket);
    }

}
