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

        $this->di->bind('Blockchain\Services\Ethereum', function () {
            return new Services\Ethereum();
        });
    }
}
