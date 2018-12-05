<?php
/**
 * Plus Token Report
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

class PlusTokens extends BoostTokens
{
    /**
     * Contructor
     * @param Core\Config\Config $config
     */
    public function __construct($config = null) {
        $config = $config ?: Di::_()->get('Config');

        $blockchainConfig = $config->get('blockchain');

        $this->token_wallet = $blockchainConfig['contracts']['bonus']['wallet_address'];
    }
}
