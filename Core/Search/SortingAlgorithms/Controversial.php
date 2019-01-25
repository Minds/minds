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
            def up = doc['votes:up:{$this->period}'].value ?: 0;
            def down = doc['votes:down:{$this->period}'].value ?: 0;
            
            def was_synced = (doc['votes:up:{$this->period}:synced'].value + 43200) > (new Date().getTime() / 1000);
            
            if (down <= 0 || up <= 0 || !was_synced) {
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
}
