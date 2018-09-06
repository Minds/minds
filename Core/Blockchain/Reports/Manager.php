<?php
/**
 * Blockchain Reports Manager
 *
 * @author Martin Alejandro Santangelo
 */

namespace Minds\Core\Blockchain\Reports;

class Manager implements ReportInterface
{
    /** @var AbstractReprot $report */
    private $report = null;

    /**
     * Set report
     *
     * @param mixed $report
     * @return $this
     */
    public function setReport($report)
    {
        if ($report instanceof AbstrsctReport) {
            $this->report = $report;
        } else {
            $this->report = new $report;
        }
        return $this;
    }

    /**
     * Get Report
     *
     * @return AbstrsctReport
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Get columns
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->report->getColumns();
    }

    /**
     * Get params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->report->getParams();
    }

    /**
     * Set report parameters
     *
     * @param array $params
     * @return $this
     * @throws \Exception
     */
    public function setParams(array $params)
    {
        return $this->report->setParams($params);
    }

    /**
     * Get report result
     *
     * @return mixed
     */
    public function get()
    {
        // check for required parameters
        $this->report->checkRequired();
        // get the result
        return $this->report->get();
    }
}