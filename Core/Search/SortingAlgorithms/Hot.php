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
                                'gte' => strtotime("1 hour ago", time()),
                            ],
                        ],
                        /*'range' => [
                            "votes:up:{$this->period}" => [
                                'gte' => 1,
                            ],
                        ],*/
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
        $time = time();
        return "
            def up = doc['votes:up:{$this->period}'].value ?: 0;
            def down = doc['votes:down:{$this->period}'].value ?: 0;

            def age = $time - (doc['@timestamp'].value.millis / 1000) - 1546300800;

            def votes = up - down;
            def sign = (votes > 0) ? 1 : (votes < 0 ? -1 : 0);
            def order = Math.log(Math.max(Math.abs(votes), 1));

            return (sign * order) - (age / 43200);
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
