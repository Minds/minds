<?php

namespace Spec\Minds\Core\Experiments;

use Minds\Core\Experiments\Sampler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Minds\Core\Data\Cassandra\Client;
use Minds\Core\Experiments\Bucket;
use Minds\Core\Experiments\Hypotheses\HypothesisInterface;

class SamplerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType(Sampler::class);
    }

    function it_should_set_a_hypothesis(
        HypothesisInterface $hypothesis
    )
    {
        $hypothesis->getBuckets()
            ->willReturn([
                (new Bucket)
                    ->setId('base')
                    ->setWeight(80),
                (new Bucket)
                    ->setId('variant1')
                    ->setWeight(20),
            ]);
        $this->setHypothesis($hypothesis);
    }

    function it_should_not_allow_more_than_100_pct_bucket_weighting_in_hypothesis(
        HypothesisInterface $hypothesis
    )
    {
        $hypothesis->getBuckets()
            ->willReturn([
                (new Bucket)
                    ->setId('base')
                    ->setWeight(90),
                (new Bucket)
                    ->setId('variant1')
                    ->setWeight(20),
            ]);
        $this->shouldThrow('\Exception')
            ->duringSetHypothesis($hypothesis);
    }

    function it_should_return_already_assigned_loggedout_bucket(
        Client $cql,
        HypothesisInterface $hypothesis
    )
    {
        $this->beConstructedWith($cql);
        $hypothesis->getId()
            ->willReturn('spectestExp');
        $hypothesis->getBuckets()
            ->willReturn([
                (new Bucket)
                    ->setId('variant1')
                    ->setWeight(20),
            ]);

        $_COOKIE['mexp'] = 'spectest';

        $cql->request(Argument::that(function($query) {
            $statement = $query->build();
            return $statement['values'][0] == 'spectestExp'
                && $statement['values'][1] == "loggedout:spectest";
        }))
            ->shouldBeCalled()
            ->willReturn([
                [
                    'bucket' => 'base'
                ]
            ]);

        $this->setHypothesis($hypothesis);

        $this->getBucket()
            ->shouldHaveType(Bucket::class);
    }

    function it_should_return_and_and_assign_unassigned_loggedout_bucket(
        Client $cql,
        HypothesisInterface $hypothesis
    )
    {
        $this->beConstructedWith($cql);
        $hypothesis->getId()
            ->willReturn('spectestExp');
        $hypothesis->getBuckets()
            ->willReturn([
                (new Bucket)
                    ->setId('variant1')
                    ->setWeight(20),
            ]);

        $_COOKIE['mwa'] = 'spectest';

        $cql->request(Argument::that(function($query) {
            $statement = $query->build();
            if ($statement['string'] != "SELECT * FROM experiments WHERE id=? AND key=?") {
                return false;
            }
            return $statement['values'][0] == 'spectestExp'
                && $statement['values'][1] == "loggedout:spectest";
        }))
            ->shouldBeCalledTimes(1)
            ->willReturn([ ]);

        $cql->request(Argument::that(function($query) {
            $statement = $query->build();
            if ($statement['string'] != "SELECT count(*) as total FROM experiments WHERE id=?") {
                return false;
            }
            return $statement['values'][0] == 'spectestExp';
        }))
            ->shouldBeCalledTimes(1)
            ->willReturn([ 
                [
                    'total' => 100
                ]
            ]);

        $cql->request(Argument::that(function($query) {
            $statement = $query->build();
            if ($statement['string'] != "SELECT count(*) as total FROM experiments WHERE id=? and bucket=?") {
                return false;
            }
            return $statement['values'][0] == 'spectestExp' && $statement['values'][1] == 'base';
        }))
            ->shouldBeCalledTimes(1)
            ->willReturn([ 
                [
                    'total' => 85
                ]
            ]);

        $cql->request(Argument::that(function($query) {
            $statement = $query->build();
            if ($statement['string'] != "INSERT INTO experiments (id, bucket, key) VALUES (?,?,?)") {
                return false;
            }
            return $statement['values'][0] == 'spectestExp' 
                && $statement['values'][1] == 'variant1'
                && $statement['values'][2] == 'loggedout:spectest';
        }), true)
            ->shouldBeCalledTimes(1)
            ->willReturn([]);

        $this->setHypothesis($hypothesis);

        $bucket = $this->getBucket();

        $bucket
            ->shouldHaveType(Bucket::class);

        $bucket->getId()
            ->shouldReturn('variant1');
    }
    

}
