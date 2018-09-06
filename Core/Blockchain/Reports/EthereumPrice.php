<?php
/**
 * Ethereum Price List Report
 *
 * @author Martin Alejandro Santangelo
 */

namespace Minds\Core\Blockchain\Reports;

use Minds\Core\Blockchain\Services\Poloniex;

class EthereumPrice extends AbstractReport
{
    /** @var array columns names */
    protected $columns = [
        'Timestamp',
        'High',
        'Low',
        'Open',
        'Close',
        'Volume',
        'Quote Volume',
        'Weighted Average',
    ];

    /** @var array allower parameters for the report */
    protected $params = ['from', 'to', 'resolution'];

    /** @var array required parameters for the report */
    protected $required = ['from', 'to'];

    /** @var integer resolution */
    protected $resolution = 300;

    /**
     * Get report result
     *
     * @return array
     */
    public function get()
    {
        if ($this->from > $this->to) {
            throw new \Exception('Wrong interval');
        }

        // fetch prices for the date interval
        $service = new Poloniex;

        return $service->getChartData($this->from, $this->to, $this->resolution);
    }
}