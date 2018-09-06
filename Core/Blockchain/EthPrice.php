<?php

/**
 * Eth Price
 *
 * @author Martin Alejandro Santangelo
 */

namespace Minds\Core\Blockchain;

use Minds\Core\Blockchain\Services\Poloniex;
use Minds\Traits\Interval;

class EthPrice
{
    use Interval;

    /** @var array */
    private $data = null;

    /** @var integer */
    private $resolution = 300;

    /** @var Poloniex */
    private $service = null;

    /** @var integer */
    private $lastDate;

    /** @var integer */
    private $firstDate;

    /**
     * Class contructor
     *
     * @param Poloniex $service
     */
    public function __construct(Poloniex $service = null)
    {
        $this->service = $service;
    }

    /**
     * Set the resolution in seconds for the data
     *
     * @param integer $resolution
     * @return $this
     */
    public function setResolution($resolution)
    {
        if (!in_array((int) $resolution, Poloniex::VALID_RESOLUTIONS)) {
            throw new \Exception("EthPrice: Invalid resolution $resolution");
        }
        $this->resolution = $resolution;
        return $this;
    }

    /**
     * Return the nearest price for given date
     *
     * (no array search so it's very efficient)
     *
     * @param integer $date unix timestamp
     * @return float
     * @throws \Exception
     */
    public function getNearestPrice($date)
    {
        if (!$this->data) throw new \Exception('EthPrice: no data loaded, call get() first');
        if ($date < $this->firstDate) {
            throw new \Exception("EthPrice: given date ($date) is not in price list");
        }
        // if the required date is bigger than the last one, we return the last date
        // fix (because poloniex do not return records for the last minutes)
        if ($date > $this->lastDate) {
            return $this->data[$this->lastDate];
        }

        $targetPosition = round(($date - $this->firstDate) / $this->resolution);

        return $this->data[$this->firstDate + $targetPosition * $this->resolution];
    }

    /**
     * Get date from service
     *
     * @return array
     * @throws \Exception
     */
    public function get()
    {
        if (!$this->from || !$this->to) throw new \Exception('EthPrice: set the date range before call get()');

        $this->data = array_column(
            $this->service->getChartData($this->from, $this->to, $this->resolution),
            'weightedAverage',
            'date'
        );

        $this->firstDate = key($this->data);
        end($this->data);
        $this->lastDate = key($this->data);
        reset($this->data);
        return $this->data;
    }
}