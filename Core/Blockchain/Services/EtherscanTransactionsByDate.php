<?php
namespace Minds\Core\Blockchain\Services;

use Minds\Core\Di\Di;
use Minds\Core\Config;
/**
 * Etherscan transactions by date
 *
 * @author Martin Santangelo <martin@minds.com>
 */
class EtherscanTransactionsByDate {
    /** @var intenger $estimationBoundary size of the chunk/2 */
    private $estimationBoundary = 1500;
    /** @var Etherscan $service */
    private $service;
    /** @var float $avgBlockTime */
    private $avgBlockTime = 14.7;
    /** @var string $address */
    private $address = '';
    /** @var boolean $fetchInternal */
    private $fetchInternal = false;

    /**
     * Constructor
     *
     * @param Etherscan $service
     */
    function __construct(Etherscan $service, Config $config = null)
    {
        $config = $config ?: Di::_()->get('Config');

        $this->avgBlockTime = $config->get('blockchain')['etherscan']['average_block_time'] ?: 14.7;
        $this->service = $service;

    }

    /**
     * Set address
     *
     * @param string $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;
        $this->service->setAddress($this->address);
        return $this;
    }

    /**
     * Set internal
     * if true the internal transactions will be fetched from the apis
     *
     * @param boolean $value
     * @return $this
     */
    public function setInternal($value)
    {
        $this->fetchInternal = $value;

        return $this;
    }

    /**
     * Optimized binary search
     *
     * @param integer $timestamp
     * @param array $array
     * @param integer $start
     * @param integer $end
     * @return integer returns the next block to the given timestamp
     */
    private function blockBinarySearch(int $timestamp, array $array, int $start, int $end)
    {
        if($end < $start) return $start - 1;

        $middle = floor( ($end + $start) / 2 );

        if ($array[$middle]['timeStamp'] < $timestamp) {
            return $this->blockBinarySearch($timestamp, $array, $start, $middle - 1);
        } else {
            return $this->blockBinarySearch($timestamp, $array, $middle + 1, $end);
        }
    }

    /**
     * Fetch transactions from the api
     *
     * @param string $from
     * @param string $to
     * @return void
     */
    private function fetch($from, $to)
    {
        if ($this->fetchInternal) {
            return $this->service->getInternalTransactions($from, $to);
        } else {
            return $this->service->getTransactions($from, $to);
        }
    }

    /**
     * Search the next block of given timestamp
     *
     * @param integer $timestamp
     * @param array $data
     * @return void
     */
    public function search($timestamp, array $data) {
        return $this->blockBinarySearch($timestamp, $data, 0, sizeof($data) - 1);
    }

    /**
     * Estimate block number by given timestamp
     *
     * @param integer $timestamp
     * @return void
     */
    public function estimateBlock($timestamp)
    {
        $lastNumber = $this->service->getLastBlockNumber();
        $dateDelta = time() - $timestamp;
        $numDelta = round($dateDelta / $this->avgBlockTime);
        return ($lastNumber - $numDelta);
    }

    /**
     * Get transaction by timestamp rage
     *
     * @param integer $from
     * @param integer $to
     * @return array
     * @throws \Exception
     */
    public function getRange($from, $to)
    {
        $data = $this->searchBegining($from);
        $lastBlockNumber = $data[0]['blockNumber'];

        while (!$this->isInRange($data, $to) == 0) {
            $moreData = $this->fetch($lastBlockNumber + 1, $lastBlockNumber + 8001);
            // we stop if there is no more data
            if (!$moreData || empty($moreData)) {
                break;
            }
            $lastBlockNumber += 8001;
            $data = array_merge($moreData, $data);
        }

        $first = $this->search($to, $data);
        $data = array_slice($data, $first + ($data[$first]['timeStamp'] != $to ? 1 : 0));

        return $data;
    }

    /**
     * Verify if the timestamp is contained in the data
     * or if it is newer or older
     *
     * @param array $data
     * @param integer $timestamp
     * @return integer 0 contained, -1 older, 1 newer
     */
    public function isInRange(array $data, $timestamp)
    {
        if ($timestamp > $data[0]['timeStamp']) return 1;
        if ($timestamp < $data[sizeof($data)-1]['timeStamp']) return -1;
        return 0;
    }

    /**
     * Search the first chunk of blocks
     * that contain the given timestamp
     *
     * @param integer $timestamp
     * @return array returns data array
     * @throws \Exception
     */
    public function searchBegining($timestamp)
    {
        $estimatedNumb = $this->estimateBlock($timestamp);
        $direction = null;
        $attemps = 0;
        do {
            $attemps++;
            $data = $this->fetch($estimatedNumb - $this->estimationBoundary, $estimatedNumb + $this->estimationBoundary);

            $in = $this->isInRange($data, $timestamp);

            switch ($in) {
                case 0:
                    return array_slice($data, 0, $this->search($timestamp, $data) + 1);
                case 1: // we should fetch newest blocks
                    $estimatedNumb += (2 * $this->estimationBoundary);
                    $direction = 1;
                    break;
                case -1: // we should fetch older blocks
                    if ($direction == 1 ) return $data;
                    $estimatedNumb -= (2 * $this->estimationBoundary);
                    $direction = -1;
                    break;
            }
        } while($attemps < 3);

        throw new \Exception("Estimation failed! Can't find the block for: $timestamp");
    }
}
