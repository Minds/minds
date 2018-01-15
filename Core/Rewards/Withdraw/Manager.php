<?php
/**
 * Manages reward withdrawals to the blockchain
 */
namespace Minds\Core\Rewards\Withdraw;

use Minds\Core\Blockchain\Pending;
use Minds\Core\Blockchain\Util;
use Minds\Core\Di\Di;
use Minds\Entities\User;

class Manager
{

    /** @var Pending $pending */
    protected $pending;

    /** @var Transactions $transactions */
    protected $transactions;

    /** @var Config $config */
    protected $config;

    public function __construct(
        $pending = null,
        $transactions = null,
        $config = null,
        $eth = null
    )
    {
        $this->pending = $pending ?: Di::_()->get('Blockchain\Pending');
        $this->transactions = $transactions ?: Di::_()->get('Rewards\Transactions');
        $this->config = $config ?: Di::_()->get('Config');
        $this->eth = $eth ?: Di::_()->get('Blockchain\Services\Ethereum');
    }

    /**
     * Create a request
     * @param Request $request
     * @return void
     */
    public function request($request)
    {
        $this->pending->add([
            'type' => 'withdraw',
            'tx_id' => $request->getTx(),
            'sender_guid' => $request->getUserGuid(),
            'data' => [
                'amount' => $request->getAmount(),
                'gas' => $request->getGas(),
                'address' => $request->getAddress(),
            ],
        ]);
    }

    /**
     * Complete the requested transaction
     * @param Request $request
     * @return void
     */
    public function complete($request)
    {
        $pending = $this->pending->get('withdrawal', $request->getTx());

        if ($request->getUserGuid() != $pending['sender_guid']) {
            throw new \Exception('The user who requested this operation does not match the transaction');
        }

        if ($request->getAddress() != $pending['data']['address']) {
            throw new \Exception('The address does not match the transaction');
        }

        if ($request->getAmount() != $pending['data']['amount']) {
            throw new \Exception('The amount request does not match the transaction');
        }

        if ($request->getGas() != $pending['data']['gas']) {
            throw new \Exception('The gas requested does not match the transaction');
        }

        //remove from pending now we are interacting with
        $this->pending->delete('withdrawal', $pending['tx_id']);

        //debit the users balance
        $user = new User;
        $user->guid = $request->getUserGuid();
        $this->transactions
            ->setUser($user)
            ->setType('withdrawal')
            ->setTx($request->getTx())
            ->setAmount(0 - $request->getAmount())
            ->create();

        //now issue the transaction
        $this->eth->sendRawTransaction($this->config->get('blockchain')['rewards_wallet_pkey'], [
            'from' => $this->config->get('blockchain')['rewards_wallet_address'],
            'to' => $this->config->get('blockchain')['withdraw_address'],
            'gasLimit' => Util::toHex(200000),
            'data' => $this->eth->encodeContractMethod('complete(address, uint256, uint256, uint256)', [
                $request->getAddress(),
                Util::toHex($request->getUserGuid()),
                Util::toHex($request->getGas()),
                Util::toHex($request->getAmount()),
            ])
        ]);
    }

}
