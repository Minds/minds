<?php
/**
 * Abstract Report
 *
 * @author Martin Alejandro Santangelo
 */

namespace Minds\Core\Blockchain\Reports;


abstract class AbstractReport implements ReportInterface
{
    /** @var array columns names */
    protected $columns = [];

    /** @var array allower parameters for the report */
    protected $params = [];

    /** @var array required parameters for the report */
    protected $required = [];

    /**
     * Get report result
     *
     * @return mixed
     */
    abstract public function get();

    /**
     * Check for required values
     *
     * @return $this
     * @throws \Exception
     */
    public function checkRequired()
    {
        foreach($this->required as $req) {
            if (!$this->$req) throw new \Exception('$req is required');
        }
        return $this;
    }

    /**
     * Get columns
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get params
     *
     * @return array
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Get params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
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
        foreach($params as $name => $value) {
            if (!in_array($name, $this->params)) throw new \Exception("paramenter $name is not valid in this report.");
            $this->$name = $value;
        }
        return $this;
    }
}