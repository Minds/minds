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

        if ($blockchainConfig['withdraw_address']) {
            $this->contracts['withdraw'] = Contracts\MindsWithdraw::at($blockchainConfig['withdraw_address']);
        }

        if ($blockchainConfig['token_distribution_event_address']) {
            $this->contracts['token_distribution_event'] = Contracts\MindsTokenSaleEvent::at($blockchainConfig['token_distribution_event_address']);
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
        $blockchainConfig = $this->config->get('blockchain') ?: [];

        return array_merge([
            'network_address' => $blockchainConfig['network_address'],
            'client_network' => $blockchainConfig['client_network'],
            'wallet_address' => $blockchainConfig['wallet_address'],
            'boost_wallet_address' => $blockchainConfig['boost_wallet_address'],
            'token_distribution_event_address' => $blockchainConfig['token_distribution_event_address'],
            'default_gas_price' => $blockchainConfig['default_gas_price'],
            'overrides' => $this->getOverrides(),
        ], $this->contracts);
    }

    public function getOverrides()
    {
        $baseConfig = $this->config->get('blockchain') ?: [];
        $overrides = $this->config->get('blockchain_override') ?: [];
        $result = [];

        foreach ($overrides as $key => $override) {
            $blockchainConfig = array_merge($baseConfig, $override);

            $result[$key] = [
                'network_address' => $blockchainConfig['network_address'],
                'client_network' => $blockchainConfig['client_network'],
                'wallet_address' => $blockchainConfig['wallet_address'],
                'boost_wallet_address' => $blockchainConfig['boost_wallet_address'],
                'token_distribution_event_address' => $blockchainConfig['token_distribution_event_address'],
                'default_gas_price' => $blockchainConfig['default_gas_price'],
            ];
        }

        return $result;
    }

    public function getRate()
    {
        // how many units per token
        return 1000;
    }
}
