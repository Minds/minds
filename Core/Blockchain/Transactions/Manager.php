<?php
/**
 * Blockchain Transactions on Minds Manager
 */
namespace Minds\Core\Blockchain\Transactions;

use Minds\Core\Blockchain\Services\Ethereum;
use Minds\Core\Events\EventsDispatcher;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Queue;
use Minds\Core\Di\Di;

class Manager
{

    /** @var string $contract */
    private $contract;

    /** @var string $user_guid */
    private $user_guid;

    /** @var int $timestamp */
    private $timestamp;

    /** @var string $wallet_address */
    private $wallet_address;

    /** @var string $tx */
    private $tx;

    /** @var Ethereum $eth */
    private $eth;

    /** @var Repository $repo */
    private $repo;

    /** @var Queue\RabbitMQ\Client */
    private $queue;

    /** @var abstractCacher */
    private $cacher;

    /** @var EventsDispatcher */
    private $dispatcher;

    public function __construct($repo = null, $eth = null, $queue = null, $cacher = null, $dispatcher = null)
    {
        $this->repo = $repo ?: Di::_()->get('Blockchain\Transactions\Repository');
        $this->eth = $eth ?: Di::_()->get('Blockchain\Services\Ethereum');
        $this->queue = $queue ?: Queue\Client::build();
        $this->cacher = $cacher ?: Di::_()->get('Cache');
        $this->dispatcher = $dispatcher ?: Di::_()->get('EventsDispatcher');
    }

    /**
     * Set the user_guid
     * @param string $guid
     * @return $this
     */
    public function setUserGuid($user_guid)
    {
        $this->user_guid = $user_guid;
        return $this;
    }

    /**
     * Set the timestamp
     * @param int $timestamp
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * Set the wallet address
     * @param string $address
     * @return $this
     */
    public function setWalletAddress($address)
    {
        $this->wallet_address = $address;
        return $this;
    }

    /**
     * Set the tx
     * @param string $tx
     * @return $this
     */
    public function setTx($tx)
    {
        $this->tx = $tx;
        return $this;
    }

    /**
     * Act upon the transaction
     * @return void
     */
    public function run()
    {
        $result = $this->repo->getList([
            'user_guid' => $this->user_guid, 
            'timestamp' => [
                'eq' => $this->timestamp,
            ],
            'wallet_address' => $this->wallet_address,
            'tx' => $this->tx,
        ]);

        if (!$result || !$result['transactions']) {
            throw new \Exception("Transaction " . $this->tx . " not found");
        }

        $transaction = $result['transactions'][0];

        if ($transaction->isCompleted()) {
            throw new \Exception("Transaction already completed");
        }

        $receipt = $this->eth->request('eth_getTransactionReceipt', [ $transaction->getTx() ]);

        if (!$receipt || !isset($receipt['status'])) {
            //too soon? add back to queue
            $this->add($transaction);
            return;
        }

        $topics = $this->dispatcher->trigger('blockchain:listen', 'all', [], []);


        if ($receipt['status'] === '0x1') {
            $logs = $receipt['logs'];
        } else {
            $logs = [[ 'topics' => [ 'blockchain:fail' ] ]];
            $transaction->setFailed(true);
        }

        foreach ($logs as $log) {
            if (!isset($log['topics'])) {
                continue;
            }

            foreach ($log['topics'] as $topic) {
                if (!isset($topics[$topic])) {
                    continue;
                }

                foreach ($topics[$topic] as $topicHandlerClass) {
                    try {
                        (new $topicHandlerClass())->event($topic, $log, $transaction);
                        error_log("Tx[{$this->tx}][{$topicHandlerClass}] {$topic}... OK!");
                    } catch (\Exception $e) {
                        error_log("Tx[{$this->tx}][{$topicHandlerClass}] {$topic} threw " . get_class($e) . ": {$e->getMessage()}");
                    }
                }
            }
        }

        // destroy onchain balance cache
        $this->cacher->destroy("blockchain:balance:{$transaction->getWalletAddress()}");
        $transaction->setCompleted(true);
        $this->repo->add($transaction);
    }

    /**
     * Adds a transaction to the queue
     * @param $transaction
     */
    public function add($transaction)
    {
        $this->repo->add($transaction);
        $this->queue->setQueue("BlockchainTransactions")
            ->send([
                'user_guid' => $transaction->getUserGuid(),
                'timestamp' => $transaction->getTimestamp(),
                'wallet_address' => $transaction->getWalletAddress(),
                'tx' => $transaction->getTx(),
            ]);
    }

}
