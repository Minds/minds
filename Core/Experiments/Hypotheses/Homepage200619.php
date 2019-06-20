<?php
/**
 * 
 */
namespace Minds\Core\Experiments\Hypotheses;

use Minds\Core\Experiments\Bucket;

class Homepage200619 implements HypothesisInterface
{

    /**
     * Return the id for the hypothesis
     * @return string
     */
    public function getId()
    {
        return "Homepage200619";
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
                ->setId('variant1')
                ->setWeight(25), //25 pct of users will be in this bucket
        ];
    }

}