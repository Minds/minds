<?php
/**
 * SortingAlgorithm
 *
 * @author: Emiliano Balbuena <edgebal>
 */
namespace Minds\Core\Search\SortingAlgorithms;

interface SortingAlgorithm
{
    /**
     * @param string $period
     * @return $this
     */
    public function setPeriod($period);

    /**
     * @return array
     */
    public function getQuery();

    /**
     * @return string
     */
    public function getScript();

    /**
     * @return array
     */
    public function getSort();
}
