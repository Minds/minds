<?php
/**
 * Minds Payments Provider
 */

namespace Minds\Core\Payments;

use Minds\Core;
use Minds\Core\Data;
use Minds\Core\Di\Provider;

use Braintree_ClientToken;
use Braintree_Configuration;
use Braintree_Transaction;
use Braintree_TransactionSearch;
use Braintree_MerchantAccount;

class PaymentsProvider extends Provider
{

    public function register()
    {
        $this->di->bind('BraintreePayments', function($di){
            $config = $di->get('Config');
            $braintree = new Braintree\Braintree(
              new Braintree_Configuration(),
              new Braintree_ClientToken(),
              Braintree_Transaction::factory([]),
              new Braintree_TransactionSearch(),
              Braintree_MerchantAccount::factory([])
            );
            $braintree->setConfig([
              'environment' => $config->payments['braintree']['environment'] ?: 'sandbox',
              'merchant_id' => $config->payments['braintree']['merchant_id'],
              'master_merchant_id' => $config->payments['braintree']['master_merchant_id'],
              'public_key' => $config->payments['braintree']['public_key'],
              'private_key' => $config->payments['braintree']['private_key']
            ]);
            return $braintree;
        }, ['useFactory'=>true]);
    }

}
