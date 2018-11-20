<?php
/**
 * 
 */
namespace Minds\Core\Experiments\Hypotheses;

use Minds\Core\Experiments\Bucket;

class Homepage121118 implements HypothesisInterface
{

    /**
     * Return the id for the hypothesis
     * @return string
     */
    public function getId()
    {
        return "Homepage121118";
    }

    /**
     * Return the buckets for the hypothesis
     * @return Bucket[]
     */
    public function getBuckets()
    {
        return [
            (new Bucket)
                ->setId('base')
                ->setWeight(75),
            (new Bucket)
                ->setId('variant2')
                ->setWeight(25), //25 pct of users will be in this bucket
        ];
    }

}