<?php

namespace Minds\Core\Boost;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Payments;
use Minds\Entities\User;

class Payment
{
    /** @var Core\Blockchain\Wallets\OffChain\Transactions */
    protected $offchainTransactions;

    /** @var Payments\Stripe\Stripe */
    protected $stripePayments;

    /** @var Config */
    protected $config;

    /** @var Core\Blockchain\Transactions\Manager */
    protected $txManager;

    /** @var Core\Blockchain\Transactions\Repository */
    protected $txRepository;

    /** @var Pending */
    protected $boostPending;

    public function __construct(
        $offchainTransactions = null,
        $stripePayments = null,
        $eth = null,
        $txManager = null,
        $txRepository = null,
        $config = null
    ) {
        $this->offchainTransactions = $offchainTransactions ?: Di::_()->get('Blockchain\Wallets\OffChain\Transactions');
        $this->stripePayments = $stripePayments ?: Di::_()->get('StripePayments');
        $this->eth = $eth ?: Di::_()->get('Blockchain\Services\Ethereum');
        $this->txManager = $txManager ?: Di::_()->get('Blockchain\Transactions\Manager');
        $this->txRepository = $txRepository ?: Di::_()->get('Blockchain\Transactions\Repository');
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * @param \Minds\Entities\Boost\Network $boost
     * @param $paymentMethodNonce
     * @return null
     * @throws \Exception
     */
    public function pay($boost, $paymentMethodNonce)
    {
        $currency = method_exists($boost, 'getMethod') ?
            $boost->getMethod() : $boost->getBidType();

        switch ($currency) {
            case 'points':
                // return Wallet::createTransaction($boost->getOwner()->guid, 0 - $boost->getBid(), $boost->getGuid(), "Boost");

            case 'offchain':
                $this->offchainTransactions
                    ->setUser($boost->getOwner())
                    ->setType('boost')
                    ->setAmount($this->offchainTransactions->toWei(-$boost->getBid()));

                return $this->offchainTransactions->create();

            case 'usd':
            case 'money':
                $customer = (new Payments\Customer())
                    ->setUser($boost->getOwner());

                $source = $paymentMethodNonce;

                if (!$customer->getId()) {
                    $customer->setPaymentToken($paymentMethodNonce);
                    $customer = $this->stripePayments->createCustomer($customer);

                    // Token already consumed to set default payment method, let's use the
                    // customer itself
                    $source = $customer->getId();
                }

                $sale = (new Payments\Sale())
                    ->setOrderId('boost-' . $boost->getGuid())
                    ->setAmount($boost->getBid())
                    ->setCustomerId($customer->getId())
                    ->setCustomer($customer)
                    ->setSource($source)
                    ->setSettle(false);

                if ($boost->getOwner()->referrer) {
                    $referrer = new User($boost->getOwner()->referrer);
                    $sale->setMerchant($referrer)
                        ->setFee(0.75); //payout 25% to referrer
                }

                return $this->stripePayments->setSale($sale);

            case 'tokens':
                $transaction = new Core\Blockchain\Transactions\Transaction();
                $transaction
                    ->setUserGuid($boost->getOwner()->guid)
                    ->setWalletAddress($paymentMethodNonce['address'])
                    ->setContract('boost')
                    ->setTx($paymentMethodNonce['txHash'])
                    ->setAmount(-$boost->getBid())
                    ->setTimestamp(time())
                    ->setCompleted(false)
                    ->setData([
                        'amount' => (string) $boost->getBid(),
                        'guid' => (string) $boost->getGuid(),
                    ]);
                $this->txManager->add($transaction);
                return $paymentMethodNonce['txHash'];
        }

        throw new \Exception('Unsupported Bid Type');
    }

    public function charge($boost)
    {
        $currency = method_exists($boost, 'getMethod') ?
            $boost->getMethod() : $boost->getBidType();

        switch ($currency) {
            case 'points':
            case 'offchain':
                return true; // Already charged

            case 'usd':
            case 'money':
                $sale = (new Payments\Sale())
                    ->setId($boost->getTransactionId());

                if ($boost->getOwner()->referrer) {
                    $referrer = new User($boost->getOwner()->referrer);
                    $sale->setMerchant($referrer);
                }

                return $this->stripePayments->chargeSale($sale);

            case 'tokens':
                //if (isset($boost->subtype) && $boost->subtype == 'network') {
                //    $this->boostPending->approve($boost);
                //}

                return true;
        }

        throw new \Exception('Unsupported Bid Type');
    }

    public function refund($boost)
    {
        $currency = method_exists($boost, 'getMethod') ?
            $boost->getMethod() : $boost->getBidType();

        switch ($currency) {
            case 'points':
                throw new \Exception('Points are no longer supported');
            // return Wallet::createTransaction($boost->getOwner()->guid, $boost->getBid(), $boost->getGuid(), "Boost Refund");

            case 'offchain':
                $this->offchainTransactions
                    ->setUser($boost->getOwner())
                    ->setType('boost_refund')
                    ->setAmount($this->offchainTransactions->toWei($boost->getBid()));

                return $this->offchainTransactions->create();

            case 'usd':
            case 'money':
                $sale = (new Payments\Sale())
                    ->setId($boost->getTransactionId());

                if ($boost->getOwner()->referrer) {
                    $referrer = new User($boost->getOwner()->referrer);
                    $sale->setMerchant($referrer);
                }

                return $this->stripePayments->voidOrRefundSale($sale, true);

            case 'tokens':

                //get the transaction
                $boostTransaction = $this->txRepository->get($boost->getOwner()->guid, $boost->getTransactionId());

                //send the tokens back to the booster
                $res = $this->eth->sendRawTransaction($this->config->get('blockchain')['boost_wallet_pkey'], [
                    'from' => $this->config->get('blockchain')['boost_wallet_address'],
                    'to' => $this->config->get('blockchain')['boost_address'],
                    'gasLimit' => Core\Blockchain\Util::toHex(200000),
                    'data' => $this->eth->encodeContractMethod('reject(uint256)', [
                        Core\Blockchain\Util::toHex($boost->getGuid())
                    ])
                ]);

                $refundTransaction = new Core\Blockchain\Transactions\Transaction();
                $refundTransaction
                    ->setUserGuid($boost->getOwner()->guid)
                    ->setWalletAddress($boostTransaction->getWalletAddress())
                    ->setContract('boost')
                    ->setTx($boost->getTransactionId())
                    ->setAmount($boostTransaction->getAmount())
                    ->setTimestamp(time())
                    ->setCompleted(false)
                    ->setData([
                        'amount' => (string) $boost->getBid(),
                        'guid' => (string) $boost->getGuid(),
                    ]);

                $this->txManager->add($refundTransaction);

                return true;
        }

        throw new \Exception('Unsupported Bid Type');
    }

}
