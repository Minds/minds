<?php
namespace Minds\Core\Rewards\Contributions;

class DailyCollection
{

    /** @var array $contributions **/
    protected $contributions = [];
    
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
    public function export() {
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
                ];
            }
            $export[$timestamp]['metrics'][$metric] = $contribution->export();
            $export[$timestamp]['amount'] += $contribution->getAmount();
            $export[$timestamp]['score'] += $contribution->getScore();
        }
        
        return array_values($export);
    }

}
