<?php
/**
 * Created by Marcelo.
 * Date: 03/07/2017
 */

namespace Minds\Core\Wire;

use Minds\Core;
use Minds\Core\Blockchain\Services\RatesInterface;
use Minds\Core\Guid;
use Minds\Core\Data;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Util\BigNumber;
use Minds\Core\Wire\Exceptions\WalletNotSetupException;
use Minds\Entities;
use Minds\Entities\User;

class Manager
{

    /** @var Data\cache\Redis */
    protected $cache;

    /** @var Repository */
    protected $repository;

    /** @var SubscriptionsManager $subscriptionsManager */
    protected $subscriptionsManager;

    /** @var Core\Blockchain\Transactions\Manager */
    protected $txManager;

    /** @var Core\Blockchain\Transactions\Repository */
    protected $txRepo;

    /** @var Entities\User $sender */
    protected $sender;

    /** @var Entities\User $receiver */
    protected $receiver;

    /** @var Entities\Entity $entity */
    protected $entity;

    /** @var double $amount */
    protected $amount;

    /** @var bool $recurring */
    protected $recurring;

    /** @var array $payload */
    protected $payload;

    /** @var Core\Config */
    protected $config;


    public function __construct(
        $cache = null,
        $repository = null,
        $subscriptionsManager = null,
        $txManager = null,
        $txRepo = null,
        $stripe = null,
        $config = null,
        $queue = null
    ) {
        $this->cache = $cache ?: Di::_()->get('Cache');
        $this->repository = $repository ?: Di::_()->get('Wire\Repository');
        $this->subscriptionsManager = $subscriptionsManager ?: Di::_()->get('Wire\Subscriptions\Manager');
        $this->txManager = $txManager ?: Di::_()->get('Blockchain\Transactions\Manager');
        $this->txRepo = $txRepo ?: Di::_()->get('Blockchain\Transactions\Repository');
        $this->stripe = $stripe ?: Di::_()->get('StripePayments');
        $this->config = $config ?: Di::_()->get('Config');
        $this->queue = $queue ?: Core\Queue\Client::build();
    }

    /**
     * Set the sender of the wire
     * @param User $sender
     * @return $this
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Set the entity of the wire - will also set the receiver
     * @param Entity $entity
     * @return $this
     */
    public function setEntity($entity)
    {
        if (!is_object($entity)) {
            $entity = Entities\Factory::build($entity);
        }

        $this->receiver = $entity->type != 'user' ?
            Entities\Factory::build($entity->owner_guid) :
            $entity;

        $this->entity = $entity;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function setRecurring($recurring)
    {
        $this->recurring = $recurring;
        return $this;
    }

    /**
     * Set the payload of the transaction
     * @param array $payload
     * @return $this
     */
    public function setPayload($payload = [])
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return bool
     * @throws WalletNotSetupException
     * @throws \Exception
     */
    public function create()
    {
        if ($this->payload['method'] == 'onchain' && (!$this->receiver->getEthWallet() || $this->receiver->getEthWallet() != $this->payload['receiver'])) {
            throw new WalletNotSetupException();
        }

        switch ($this->payload['method']) {
            case 'onchain':
                //add transaction to the senders transaction log
                $transaction = new Core\Blockchain\Transactions\Transaction();
                $transaction
                    ->setUserGuid($this->sender->guid)
                    ->setWalletAddress($this->payload['address'])
                    ->setContract('wire')
                    ->setTx($this->payload['txHash'])
                    ->setAmount((string) BigNumber::_($this->amount)->neg())
                    ->setTimestamp(time())
                    ->setCompleted(false)
                    ->setData([
                        'amount' => (string) $this->amount,
                        'receiver_address' => $this->payload['receiver'],
                        'sender_address' => $this->payload['address'],
                        'receiver_guid' => (string) $this->receiver->guid,
                        'sender_guid' => (string) $this->sender->guid,
                        'entity_guid' => (string) $this->entity->guid,
                    ]);
                $this->txManager->add($transaction);

                break;

            case 'offchain':
                /** @var Core\Blockchain\Wallets\OffChain\Cap $cap */
                $cap = Di::_()->get('Blockchain\Wallets\OffChain\Cap')
                    ->setUser($this->sender)
                    ->setContract('boost');

                if (!$cap->isAllowed($this->amount)) {
                    throw new \Exception('You are not allowed to spend that amount of coins.');
                }

                $txData = [
                    'amount' => (string) $this->amount,
                    'sender_guid' => (string) $this->sender->guid,
                    'receiver_guid' => (string) $this->receiver->guid,
                    'entity_guid' => (string) $this->entity->guid,
                ];

                $sendersTx = new Core\Blockchain\Wallets\OffChain\Transactions();
                $sendersTx
                    ->setAmount((string) BigNumber::_($this->amount)->neg())
                    ->setType('wire')
                    ->setUser($this->sender)
                    ->setData($txData)
                    ->create();

                $receiversTx = new Core\Blockchain\Wallets\OffChain\Transactions();
                $receiversTx
                    ->setAmount($this->amount)
                    ->setType('wire')
                    ->setUser($this->receiver)
                    ->setData($txData)
                    ->create();

                $wire = new Wire();
                $wire
                    ->setSender($this->sender)
                    ->setReceiver($this->receiver)
                    ->setEntity($this->entity)
                    ->setAmount($this->amount)
                    ->setTimestamp(time());
                $this->repository->add($wire);

                $this->sendNotification($wire);

                $this->clearWireCache($wire);

                break;

            case 'creditcard':
                //charge the card
                $customer = (new Core\Payments\Customer())
                    ->setUser($this->sender);
                    
                $nonce = $this->payload['token'];
                // if customer doesn't exist on Stripe, create it
                if (!$this->stripe->getCustomer($customer) || !$customer->getId()) {
                    $customer->setPaymentToken($nonce);
                    $customer = $this->stripe->createCustomer($customer);
                    $nonce = $customer->getId();
                }

                $currencyId = Di::_()->get('Config')->get('blockchain')['token_symbol'];

                /** @var RatesInterface $rates */
                $rates = Di::_()->get('Blockchain\Rates');
                $exRate = $rates
                    ->setCurrency($currencyId)
                    ->get();

                $usd = BigNumber::fromPlain($this->amount, 18)
                    ->mul($exRate)
                    ->mul(100)
                    ->toDouble(); //*100 for $ -> cents

                $usd = round($usd);

                $sale = new Core\Payments\Sale();
                $sale->setOrderId('wire-' . $this->entity->guid)
                    ->setAmount($usd)
                    ->setCustomer($customer)
                    ->setSource($nonce)
                    ->capture();

                Core\Events\Dispatcher::trigger('invoice:email', 'all', [
                    'user'=>$this->sender(),
                    'amount'=>$this->amount,
                    'description'=> 'Wire' . $this->recurring ? ' (recurring)' : '' . ' for @' . $this->receiver->username
                ]);

                $tx = 'creditcard:' . $this->stripe->setSale($sale);

                $sendersTx = new Core\Blockchain\Transactions\Transaction();
                $sendersTx
                    ->setTx($tx)
                    ->setContract('wire')
                    ->setWalletAddress('creditcard')
                    ->setAmount((string) BigNumber::_($this->amount)->neg())
                    ->setTimestamp(time())
                    ->setUserGuid($this->sender->guid)
                    ->setCompleted(true)
                    ->setData([
                        'amount' => (string) $this->amount,
                        'receiver_address' => 'offchain',
                        'sender_address' => 'creditcard',
                        'receiver_guid' => (string) $this->receiver->guid,
                        'sender_guid' => (string) $this->sender->guid,
                        'entity_guid' => (string) $this->entity->guid,
                    ]);
                $this->txManager->add($sendersTx);

                // what the receiver gets
                $receiversTx = new Core\Blockchain\Transactions\Transaction();
                $receiversTx
                    ->setTx($tx)
                    ->setContract('wire')
                    ->setWalletAddress('offchain')
                    ->setAmount($this->amount)
                    ->setTimestamp(time())
                    ->setUserGuid($this->receiver->guid)
                    ->setCompleted(false)
                    ->setData([
                        'amount' => (string) $this->amount,
                        'receiver_address' => 'offchain',
                        'sender_address' => 'creditcard',
                        'receiver_guid' => (string) $this->receiver->guid,
                        'sender_guid' => (string) $this->sender->guid,
                        'entity_guid' => (string) $this->entity->guid,
                    ]);
                $this->txManager->add($receiversTx);

                $withholding = new Core\Blockchain\Wallets\OffChain\Withholding\Withholding();
                $withholding
                    ->setUserGuid($this->receiver)
                    ->setTimestamp(time())
                    ->setTx($tx)
                    ->setType('wire')
                    ->setWalletAddress('offchain')
                    ->setAmount($this->amount)
                    ->setTtl($this->config->get('blockchain')['offchain']['withholding']['wire']);

                Di::_()->get('Blockchain\Wallets\OffChain\Withholding\Repository')
                    ->add($withholding);

                $wire = new Wire();
                $wire
                    ->setSender($this->sender)
                    ->setReceiver($this->receiver)
                    ->setEntity($this->entity)
                    ->setAmount($this->amount)
                    ->setTimestamp(time());
                $this->repository->add($wire);

                $this->clearWireCache($wire);

                break;
        }
        
        //is this a subscription?
        if ($this->recurring) {
            $this->subscriptionsManager
                ->setAmount($this->amount)
                ->setSender($this->sender)
                ->setReceiver($this->receiver)
                ->create();
        }

        // send wire email
        Dispatcher::trigger('wire:email', 'wire', [
            'receiver' => $this->receiver
        ]);

        return true;
    }
    
    /**
     * Confirmationof wire from the blockchain
     * @param Wire $wire
     * @param Transaction $transaction - the transaction from the blockchain
     */
    public function confirm($wire, $transaction)
    {
        if ($wire->getSender()->guid != $transaction->getUserGuid()) {
            throw new \Exception('The user who requested this operation does not match the transaction');
        }

        if ($wire->getAmount() != $transaction->getData()['amount']) {
            throw new \Exception('The amount request does not match the transaction');
        }

        $wire->setGuid(Guid::build());
        $success = $this->repository->add($wire);

        //create a new transaction for receiver
        $data = $transaction->getData();
        $transaction
            ->setUserGuid($wire->getReceiver()->guid)
            ->setWalletAddress($data['receiver_address'])
            ->setAmount($wire->getAmount())
            ->setCompleted(true);
        $this->txRepo->add($transaction);

        /*Dispatcher::trigger('wire-payment-email', 'object', [
            'charged' => false,
            'amount' => $wire->getAmount,
            'unit' => 'tokens',
            'description' => 'Wire',
            'user' => $wire->getReceiver(),
        ]);*/

        $this->sendNotification($wire);

        $this->clearWireCache($wire);

        return $success;
    }

    /**
     * @param Wire $wire
     */
    protected function clearWireCache(Wire $wire)
    {
        $this->cache->destroy(Counter::getIndexName($wire->getEntity()->guid, null, 'tokens', null, true));
    }

    protected function sendNotification(Wire $wire = null)
    {
        $this->queue->setQueue("WireNotification")
            ->send([
                "amount" => $wire ? 
                    (string) BigNumber::_($wire->getAmount())->div(10 ** 18)
                    : (string) BigNumber::_($this->amount)->div(10 ** 18),
                "sender" => serialize($wire ? $wire->getSender() : $this->sender),
                "entity" => serialize($wire ? $wire->getEntity() : $this->entity),
                "subscribed" => $wire ? $wire->isRecurring() : $this->recurring,
            ]);
    }

}
