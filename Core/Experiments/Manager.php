<?php
/**
 * Experiments manager
 */
namespace Minds\Core\Experiments;

use Minds\Interfaces\ModuleInterface;

class Manager
{

    /** @param Sampler $sampler */
    private $sampler;

    /** @param User $user */
    private $user;

    /** @param array $experiments */
    private $experiments = [
        'Homepage121118' => Hypotheses\Homepage121118::class,
    ];

    public function __construct($sampler = null)
    {
        $this->sampler = $sampler ?: new Sampler;
    }

    /**
     * Set the user who will view experiments
     * @param User $user
     * @return Manager
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Return a list of experiments
     * @param HypothesisInterface[]
     */
    public function getExperiments()
    {
        return $this->experiments;
    }

    /**
     * Return the bucket for an experiment
     * @param string $experimentId
     * @return Bucket
     */
    public function getBucketForExperiment($experimentId)
    {
        if (!$this->experiments[$experimentId]) {
            throw new \Exception("$experimentId not found");
        }

        $hypothesis = new $this->experiments[$experimentId];

        $this->sampler
            ->setHypothesis($hypothesis)
            ->setUser($this->user);

        return $this->sampler->getBucket();
    }

}