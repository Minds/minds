<?php

namespace Minds\Core\Rewards\Contributions;

use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Data\cache\factory as CacheFactory;


class DailyCollection
{

    /** @var array $contributions */
    protected $contributions = [];

    /** @var Sums */
    protected $sums;

    public function __construct($sums = null)
    {
        $this->sums = $sums ?: new Sums();
    }

    /**
     * Set contributions
     * @param array $contributions
     * @return $this
     */
    public function setContributions($contributions)
    {
        foreach ($contributions as $contribution) {
            array_push($this->contributions, $contribution);
        }
        return $this;
    }

    /**
     * Export contributions
     * @return array
     */
    public function export()
    {
        $export = [];

        foreach ($this->contributions as $contribution) {
            $timestamp = $contribution->getTimestamp();
            $metric = $contribution->getMetric();
            if (!isset($export[$timestamp])) {
                $export[$timestamp] = [
                    'timestamp' => $timestamp,
                    'metrics' => [],
                    'amount' => 0,
                    'score' => 0,
                    'share' => 0,
                ];
            }
            $export[$timestamp]['metrics'][$metric] = $contribution->export();
            $export[$timestamp]['amount'] += $contribution->getAmount();
            $export[$timestamp]['score'] += $contribution->getScore();

            $totalScore = 0;

            try {
                $totalScore = $this->getTotalScore($timestamp);
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }

            $export[$timestamp]['share'] += ($contribution->getScore() / $totalScore) * 100;
        }

        return array_values($export);
    }

    private function getTotalScore($timestamp)
    {
        /** @var abstractCacher $cacher */
        $cacher = CacheFactory::build();

        if ($totalScore = $cacher->get('total-daily-contribution:' . $timestamp)) {
            return $totalScore;
        }

        $this->sums->setTimestamp($timestamp);
        $totalScore = $this->sums->getScore();

        $cacher->set('total-daily-contributions:' . $timestamp, $totalScore, 3600);

        return $totalScore;
    }

}
