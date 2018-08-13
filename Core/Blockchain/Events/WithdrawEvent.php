<?php

/**
 * Blockchain Withdraw Events
 *
 * @author mark
 */

namespace Minds\Core\Blockchain\Events;

use Minds\Core\Blockchain\Contracts\MindsToken;
use Minds\Core\Blockchain\Transactions\Manager;
use Minds\Core\Blockchain\Util;
use Minds\Core\Di\Di;
use Minds\Core\Rewards\Withdraw;
use Minds\Core\Util\BigNumber;

class WithdrawEvent implements BlockchainEventInterface
{
    /** @var array $eventsMap */
    public static $eventsMap = [
        '0x317c0f5ab60805d3e3fb6aaa61ccb77253bbb20deccbbe49c544de4baa4d7f8f' => 'onRequest',
        'blockchain:fail' => 'withdrawFail',
    ];

    /** @var Manager $manager */
    private $manager;

    /** @var Repository $repository **/
    protected $txRepository;

    /** @var Config $config */
    private $config;

    public function __construct($manager = null, $txRepository = null, $config = null)
    {
        $this->txRepository = $txRepository ?: Di::_()->get('Blockchain\Transactions\Repository');
        $this->manager = $manager ?: Di::_()->get('Rewards\Withdraw\Manager');
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * @return array
     */
    public function getTopics()
    {
        return array_keys(static::$eventsMap);
    }

    /**
     * @param $topic
     * @param array $log
     * @throws \Exception
     */
    public function event($topic, array $log, $transaction)
    {
        $method = static::$eventsMap[$topic];

        if ($log['address'] != $this->config->get('blockchain')['contracts']['withdraw']['contract_address']) {
            throw new \Exception('Event does not match address');
        }

        if (method_exists($this, $method)) {
            $this->{$method}($log, $transaction);
        } else {
            throw new \Exception('Method not found');
        }
    }

    public function onRequest($log, $transaction)
    {
        $address = $log['address'];

        if ($address != $this->config->get('blockchain')['contracts']['withdraw']['contract_address']) {
            $this->withdrawFail($log, $transaction);
            throw new \Exception('Incorrect address sent the withdraw event');
        }

        $tx = $log['transactionHash'];
        list($address, $user_guid, $gas, $amount) = Util::parseData($log['data'], [Util::ADDRESS, Util::NUMBER, Util::NUMBER, Util::NUMBER]);
        $user_guid = BigNumber::fromHex($user_guid)->toInt();
        $gas = (string) BigNumber::fromHex($gas);
        $amount = (string) BigNumber::fromHex($amount);

        //double check the details of this transaction match with what the user actually requested
        $request = new Withdraw\Request();

        $request
            ->setTx($tx)
            ->setAddress($address)
            ->setUserGuid($user_guid)
            ->setGas($gas)
            ->setTimestamp($transaction->getTimestamp())
            ->setAmount($amount);

        try {        
            $this->manager->complete($request, $transaction);
        } catch (\Exception $e) {
            var_dump($e);
            error_log(print_r($e, true));
        }

    }

    public function withdrawFail($log, $transaction) {
        if ($transaction->getContract() !== 'withdraw') {
            throw new \Exception("Failed but not a withdrawal");
            return;
        }

        $transaction->setFailed(true);

        $this->txRepository->update($transaction, [ 'failed' ]);
    }
}
