<?php
/**
 * Token Sale Event Report
 *
 * @author Martin Alejandro Santangelo
 */

namespace Minds\Core\Blockchain\Reports;

use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;
use Minds\Core\Blockchain\EthPrice;
use Minds\Core\Blockchain\Services\Poloniex;
use Minds\Core\Blockchain\Services\Etherscan;
use Minds\Core\Blockchain\Services\EtherscanTransactionsByDate;

class TokenSaleEvent extends AbstractReport
{
    /** @var string */
    private $token_sale_event_wallet;

    /** @var array columns names */
    protected $columns = [
        'Date & Time',
        'Timestamp',
        'Block Number',
        'Tx Hash',
        'From',
        'ETH',
        'ETH Price',
        'USD'
    ];

    /** @var array allower parameters for the report */
    protected $params = ['from', 'to'];

    /** @var array required parameters for the report */
    protected $required = ['from', 'to'];

    /**
     * Contructor
     * @param Core\Config\Config $config
     */
    public function __construct($config = null) {
        $config = $config ?: Di::_()->get('Config');

        $blockchainConfig = $config->get('blockchain');

        $this->token_sale_event_wallet = $blockchainConfig['contracts']['token_sale_event']['wallet_address'];
    }

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

        $service = new EtherscanTransactionsByDate(new Etherscan);

        // fetch data from etherscan service
        $data = $service->setAddress($this->token_sale_event_wallet)
            ->setInternal(true)
            ->getRange($this->from, $this->to);

        // fetch prices for the date interval
        $ethPrice = new EthPrice(new Poloniex);
        $ethPrice
            ->setFrom($this->from)
            ->setTo($this->to)
            ->get();

        // format output
        return array_map(function($row) use($ethPrice) {
            $eth = BigNumber::fromPlain($row['value'], 18);
            $ethPriceVal = $ethPrice->getNearestPrice($row['timeStamp']);
            $usd = $eth->mul($ethPriceVal)->toString();
            return [
                date(DATE_ISO8601, $row['timeStamp']), // friendly time
                $row['timeStamp'],   // timestamp
                $row['blockNumber'], // block number
                $row['hash'],        // tx hash
                $row['from'],        // origin address
                $eth->toString(),    // ETH amount
                $ethPriceVal,
                $usd                 // USD amount
            ];
        }, $data);
    }
}
