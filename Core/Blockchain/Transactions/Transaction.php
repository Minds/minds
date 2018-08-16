<?php
/**
 * Transaction model
 */
namespace Minds\Core\Blockchain\Transactions;

use Minds\Entities\User;
use Minds\Traits\MagicAttributes;

/**
 * Class Transaction
 * @package Minds\Core\Blockchain\Transactions
 * @method string getTx()
 * @method Transaction setTx(string $value)
 * @method int getUserGuid()
 * @method Transaction setUserGuid(int $value)
 * @method string getWalletAddress()
 * @method Transaction setWalletAddress(string $value)
 * @method int getTimestamp()
 * @method Transaction setTimestamp(int $value)
 * @method string getContract()
 * @method Transaction setContract(string $value)
 * @method string getAmount()
 * @method Transaction setAmount(string)
 * @method bool isCompleted()
 * @method bool getCompleted()
 * @method Transaction setCompleted(bool $value)
 * @method bool getFailed()
 * @method Transaction setFailed(bool $value)
 * @method array getData()
 * @method Transaction setData(array $value)
 */
class Transaction
{
    use MagicAttributes;

    /** @var string $tx (PRIMARY KEY)*/
    private $tx;

    /** @var int $userGuid (PRIMARY KEY)*/
    private $userGuid;

    /** @var string $walletAddress (PRIMARY KEY)*/
    private $walletAddress;

    /** @var int $timestamp (PRIMARY KEY)*/
    private $timestamp;

    /** @var string $contract */
    private $contract;

    /** @var string $amount */
    private $amount;

    /** @var bool $completed */
    private $completed = false;

    /** @var bool $failed */
    private $failed = false;

    /** @var int $data */
    private $data;

    /**
     * Export
     */
    public function export()
    {
        $export = [
            'user_guid' => $this->userGuid,
            'user' => (new User($this->userGuid))->export(),
            'wallet_address' => $this->walletAddress,
            'tx' => $this->tx,
            'amount' => $this->amount,
            'failed' => $this->failed,
            'timestamp' => $this->timestamp,
            'contract' => $this->contract
        ];
        if ($this->data['sender_guid']) {
            $export['sender'] = (new User($this->data['sender_guid']))->export();
        }
        if ($this->data['receiver_guid']) {
            $export['receiver'] = (new User($this->data['receiver_guid']))->export();
        }
        return $export;
    }

}
