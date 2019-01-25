<?php
/**
 * Top
 *
 * @author: Emiliano Balbuena <edgebal>
 */
namespace Minds\Core\Search\SortingAlgorithms;

class Top implements SortingAlgorithm
{
    protected $period;

    /**
     * @param string $period
     * @return $this
     */
    public function setPeriod($period)
    {
        $this->period = $period;
        return $this;
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        return [
            'bool' => [
                'must' => [
                    [
                        'exists' => [
                            'field' => "votes:up:{$this->period}",
                        ],
                    ],
                ],
            ]
        ];
    }

    /**
     * @return string
     */
    public function getScript()
    {
        return "
            def up = (doc['votes:up:{$this->period}'].value ?: 0) * 1.0;
            def down = (doc['votes:down:{$this->period}'].value ?: 0) * 1.0;
            def magnitude = up + down;
            
            def was_synced = (doc['votes:up:{$this->period}:synced'].value + 43200) > (new Date().getTime() / 1000);
            
            if (magnitude <= 0 || !was_synced) {
                return 0;
            }
            
            def score = ((up + 1.9208) / (up + down) - 1.96 * Math.sqrt((up * down) / (up + down) + 0.9604) / (up + down)) / (1 + 3.8416 / (up + down));
            
            return score;
        ";
    }

    /**
     * @return array
     */
    public function getSort()
    {
        return [
            '_score' => [
                'order' => 'desc'
            ]
        ];
    }
}
