<?php

/**
 * MindsCoin Provider
 *
 * @author emi
 */

namespace Minds\Core\Blockchain;

use Minds\Core\Di\Provider;

class BlockchainProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Blockchain\Manager', function () {
            return new Manager();
        });

        $this->di->bind('Blockchain\Pending', function () {
            return new Pending();
        });

        $this->di->bind('Blockchain\Token', function ($di) {
            return new Token($di->get('Blockchain\Manager'));
        });

        $this->di->bind('Blockchain\TokenDistributionEvent', function ($di) {
            return new TokenDistributionEvent();
        });

        $this->di->bind('Blockchain\Transactions\Manager', function ($di) {
            return new Transactions\Manager();
        });

        $this->di->bind('Blockchain\Transactions\Repository', function ($di) {
            return new Transactions\Repository();
        });

        $this->di->bind('Blockchain\Preregistrations', function ($di) {
            return new Preregistrations();
        });

        $this->di->bind('Blockchain\Services\Ethereum', function () {
            return new Services\Ethereum();
        }, [ 'useFactory' => true ]);

        $this->di->bind('Blockchain\Wallets\OffChain\Balance', function () {
            return new Wallets\OffChain\Balance();
        });

        $this->di->bind('Blockchain\Wallets\OffChain\TestnetBalance', function () {
            return new Wallets\OffChain\TestnetBalance();
        });

        $this->di->bind('Blockchain\Wallets\OffChain\Transactions', function () {
            return new Wallets\OffChain\Transactions();
        }, [ 'useFactory' => false ]);

        $this->di->bind('Blockchain\Wallets\OffChain\Cap', function () {
            return new Wallets\OffChain\Cap();
        });

        $this->di->bind('Blockchain\Wallets\OffChain\Withholding\Repository', function ($di) {
            return new Wallets\OffChain\Withholding\Repository();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Blockchain\Wallets\OffChain\Withholding\Sums', function ($di) {
            return new Wallets\OffChain\Withholding\Sums();
        }, [ 'useFactory'=> true ]);

        $this->di->bind('Blockchain\Wallets\OnChain\Balance', function () {
            return new Wallets\OnChain\Balance();
        });

        $this->di->bind('Blockchain\Rates', function () {
            return new Services\CoinMarketCap();
        });

        $this->di->bind('Blockchain\Purchase\Manager', function () {
            return new Purchase\Manager();
        });

        $this->di->bind('Blockchain\Purchase\Repository', function () {
            return new Purchase\Repository();
        });

        $this->di->bind('Blockchain\Purchase\Sums', function () {
            return new Purchase\Sums();
        });

        $this->di->bind('Blockchain\GasPrice', function () {
            return new GasPrice();
        });
    }
}
