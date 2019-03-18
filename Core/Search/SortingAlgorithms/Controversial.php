<?php
/**
 * Controversial
 *
 * @author: Emiliano Balbuena <edgebal>
 */
namespace Minds\Core\Search\SortingAlgorithms;

class Controversial implements SortingAlgorithm
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
                                'gte' => strtotime('7 days ago', time()),
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
            
            if (down <= 0 || up <= 0) {
                return 0;
            }
            
            def magnitude = up + down;
            def balance = (up > down) ? 1.0 * down / up : 1.0 * up / down;

            return Math.log(Math.pow(magnitude, balance));
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
