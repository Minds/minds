<?php
/**
 * Report Interface
 *
 * @author Martin Alejandro Santangelo
 */

namespace Minds\Core\Blockchain\Reports;

interface ReportInterface
{
    /**
     * Get columns
     *
     * @return array
     */
    public function getColumns();

    /**
     * Set report parameters
     *
     * @param array $params
     * @return $this
     * @throws \Exception
     */
    public function setParams(array $params);

    /**
     * Get params
     *
     * @return array
     */
    public function getParams();

    /**
     * Get report result
     *
     * @return mixed
     */
    public function get();
}