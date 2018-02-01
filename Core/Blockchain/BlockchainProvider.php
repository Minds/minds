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
        });

        $this->di->bind('Blockchain\Wallets\OffChain\Balance', function () {
            return new Wallets\OffChain\Balance();
        });

        $this->di->bind('Blockchain\Wallets\OffChain\Transactions', function () {
            return new Wallets\OffChain\Transactions();
        }, [ 'useFactory' => false ]);

        $this->di->bind('Blockchain\Wallets\OnChain\Balance', function () {
            return new Wallets\OnChain\Balance();
        });
    }
}
