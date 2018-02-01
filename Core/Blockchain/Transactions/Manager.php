<?php
/**
 * Blockchain Transactions on Minds Manager
 */
namespace Minds\Core\Blockchain\Transactions;

use Minds\Core\Queue;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Di\Di;

class Manager
{

    /** @var string $contract */
    private $contract;

    /** @var string $tx */
    private $tx;

    /** @var Ethereum $eth */
    private $eth;

    /** @var Repository $repo */
    private $repo;

    public function __construct($repo = null, $eth = null)
    {
        $this->repo = $repo ?: Di::_()->get('Blockchain\Transactions\Repository');
        $this->eth = $eth ?: Di::_()->get('Blockchain\Services\Ethereum');
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
        $transaction = $this->repo->get($this->tx);

        if (!$transaction) {
            throw new \Exception("Transaction " . $this->tx . " not found");
        }

        if ($transaction->isCompleted()) {
            throw new \Exception("Transaction already completed");
        }

        $receipt = $this->eth->request('eth_getTransactionReceipt', [ $transaction->getTx() ]);
        $topics = Dispatcher::trigger('blockchain:listen', 'all', [], []);        
        $logs = $receipt['logs'];

        if (!$logs) {
            //too soon? add back to queue
            return $this->add($transaction);
        }

        foreach ($logs as $log) {

            if (!isset($log['topics'])) {
                continue;
            }

            foreach ($log['topics'] as $topic) {
                if (isset($topics[$topic])) {
                    try {
                        (new $topics[$topic]())->event($topic, $log, $transaction);
                    } catch (\Exception $e) {
                        error_log("Tx[{$this->tx}] {$topic} threw " . get_class($e) . ": {$e->getMessage()}");
                        continue;
                    }
                }
            }
        }

        $transaction->setCompleted(true);
        $this->repo->add($transaction);
    }

    /**
     * 
     */
    public function add($transaction)
    {
        $this->repo->add($transaction);
        Queue\Client::build()
            ->setQueue("BlockchainTransactions")
            ->send([
                'tx' => $transaction->getTx()
            ]);
    }

}
