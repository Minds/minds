<?php
/**
 * Hot
 *
 * @author: Emiliano Balbuena <edgebal>
 */

namespace Minds\Core\Search\SortingAlgorithms;

class Hot implements SortingAlgorithm
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
                        'range' => [
                            "votes:up:{$this->period}:synced" => [
                                'gte' => strtotime("7 days ago", time()),
                            ],
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
            def up = doc['votes:up:{$this->period}'].value ?: 0;
            def down = doc['votes:down:{$this->period}'].value ?: 0;
            
            def age = (new Date().getTime() - doc['@timestamp'].value) / 1000.0;
            
            def votes = up - down;
            def sign = (votes > 0) ? 1 : (votes < 0 ? -1 : 0);
            def order = Math.log(Math.max(Math.abs(votes), 1));
            
            return (sign * order) - (age / 45000.0); 
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

    /**
     * @param array $doc
     * @return int|float
     */
    public function fetchScore($doc)
    {
        return $doc['_score'];
    }
}
