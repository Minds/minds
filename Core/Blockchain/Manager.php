<?php

/**
 * Blockchain Manager
 *
 * @author emi
 */

namespace Minds\Core\Blockchain;

use Minds\Core\Di\Di;

class Manager
{
    protected $config;
    protected $contracts = [];

    public function __construct($config = null)
    {
        $this->config = $config ?: Di::_()->get('Config');

        $this->initContracts();
    }

    protected function initContracts()
    {
        $blockchainConfig = $this->config->get('blockchain');

        if ($blockchainConfig['token_address']) {
            $this->contracts['token'] = Contracts\MindsToken::at($blockchainConfig['token_address']);
        }

        if ($blockchainConfig['wire_address']) {
            $this->contracts['wire'] = Contracts\MindsWire::at($blockchainConfig['wire_address']);
        }

        if ($blockchainConfig['boost_address']) {
            $this->contracts['boost'] = Contracts\MindsBoost::at($blockchainConfig['boost_address']);
        }
    }

    public function getContract($contract)
    {
        if (isset($this->contracts[$contract])) {
            return $this->contracts[$contract];
        }
        return null;
    }

    public function getPublicSettings()
    {
        $blockchainConfig = $this->config->get('blockchain');

        if (!$blockchainConfig) {
            return [];
        }

        return array_merge([
                'client_network' => $blockchainConfig['client_network'],
                'wallet_address' => $blockchainConfig['wallet_address'],
                'boost_wallet_address' => $blockchainConfig['boost_wallet_address'],
                'default_gas_price' => $blockchainConfig['default_gas_price'],
            ], $this->contracts
        );
    }

    public function getRate()
    {
        // how many points per MC
        return 10000;
    }
}
