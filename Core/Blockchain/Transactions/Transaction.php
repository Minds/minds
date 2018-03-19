<?php
/**
 * Transaction model
 */
namespace Minds\Core\Blockchain\Transactions;

use Minds\Entities\User;
use Minds\Traits\MagicAttributes;

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
            'timestamp' => $this->timestamp,
            'contract' => $this->contract
        ];
        if ($this->data['sender_guid']) {
            $export['sender'] = (new User($this->data['sender_guid']))->export();
        }
        return $export;
    }

}
