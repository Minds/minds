<?php
/**
 * Experiments sampler handler
 * ! Currently only supports AB testing !
 */
namespace Minds\Core\Experiments;

use Minds\Interfaces\ModuleInterface;
use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Prepared;

class Sampler
{

    /** @param Client $cql */
    private $cql;

    /** @param User $user */
    private $user;

    /** @param HypthosesInterface $hypothesis */
    private $hypothesis;

    /** @param array $buckets */
    private $buckets = [ ];

    public function __construct($cql = null)
    {
        $this->cql = $cql ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * Set the user
     * @param User $user
     * @return Sampler
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Set the hypothesis to sample
     * @param HypothesisInterface $hypothesis
     * @return Sampler
     */
    public function setHypothesis($hypothesis)
    {
        $this->hypothesis = $hypothesis;
        $this->buckets = $this->hypothesis->getBuckets();

        $pct = 0;
        foreach ($this->hypothesis->getBuckets() as $bucket) {
            $this->buckets[$bucket->getId()] = $bucket;
            $pct += $bucket->getWeight();
        }

        if (!isset($this->buckets['base'])) {
            $this->buckets['base'] = (new Bucket())
                ->setId('base')
                ->setWeight(100 - $pct);
         }

        if ($pct > 100) {
            throw new \Exception("Your sample weightings for " . get_class($this->hypothesis) . " are over 100");
        }

        return $this;
    }

    /**
     * Return the bucket for a user
     * @return Bucket
     */
    public function getBucket()
    {
        $key = "";
        if (!$this->user) { // Logged out
            if (!isset($_COOKIE['mexp'])) {
                throw new \Exception("Logged out user require mexp cookie");
            }
            $key = "loggedout:{$_COOKIE['mexp']}";
        } else {
            $key = $this->user->getGuid();
        }

        $query = new Prepared\Custom();
        $query->query(
            "SELECT * FROM experiments WHERE id=? AND key=?",
            [ $this->hypothesis->getId(), $key ]
        );
        $result = $this->cql->request($query);

        if ($result && $result[0]) {
            foreach ($this->buckets as $bucket) {
                if ($bucket->getId() == $result[0]['bucket']) {
                    return $bucket;
                }
            }
        }

        $total = $this->getTotalSampleCount();
        $base = $this->getBaseSampleCount();
        $pct = ($base / $total) * 100;
        $bucket = $this->getBestBucket($pct);
        $this->saveBucket($bucket, $key);
        return $bucket;
    }

    /**
     * Return the total count for all experiment buckets
     * @return int
     */
    protected function getTotalSampleCount()
    {
        $query = new Prepared\Custom();
        $query->query("SELECT count(*) as total FROM experiments WHERE id=?", [ $this->hypothesis->getId() ]);

        $result = $this->cql->request($query);
        return (int) $result[0]['total'];
    }

    /**
     * Return the sample count for the base bucket for an experiment
     * @return int
     */
    protected function getBaseSampleCount()
    {
        $query = new Prepared\Custom();
        $query->query(
            "SELECT count(*) as total FROM experiments WHERE id=? and bucket=?", 
            [ $this->hypothesis->getId(), 'base' ]
        );

        $result = $this->cql->request($query);
        return (int) $result[0]['total'];
    }

    /**
     * Return the best bucket
     * @param int $pct
     * @return Bucket
     */
    protected function getBestBucket($pct)
    {
        if ($pct > $this->buckets['base']->getWeight()) {
            foreach ($this->buckets as $bucket) {
                if ($bucket->getId() == 'base') {
                    continue;
                }
                return $bucket;
            }
        }
        return $this->buckets['base'];
    }

    /**
     * Save the bucket to the key
     * @param Bucket $bucket
     * @param string $key
     * @return void
     */
    protected function saveBucket($bucket, $key)
    {
        $query = new Prepared\Custom();
        $query->query(
            "INSERT INTO experiments (id, bucket, key) VALUES (?,?,?)", 
            [ $this->hypothesis->getId(), $bucket->getId(), $key ]
        );

        $this->cql->request($query, true);
    }

}
