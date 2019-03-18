<?php
/**
 * Chronological
 *
 * @author: Emiliano Balbuena <edgebal>
 */

namespace Minds\Core\Search\SortingAlgorithms;

class Chronological implements SortingAlgorithm
{
    /**
     * @param string $period
     * @return $this
     */
    public function setPeriod($period)
    {
        // No effects
        return $this;
    }

    /**
     * @return array
     */
    public function getQuery()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getScript()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getSort()
    {
        return [
            'time_created' => [
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
        return $doc['_source']['time_created'];
    }
}
