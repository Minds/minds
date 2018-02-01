<?php
/**
 * Transaction model
 */
namespace Minds\Core\Blockchain\Transactions;

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

    /** @var double $amount */
    private $amount;

    /** @var bool $completed */
    private $completed = false;

    /** @var int $data */
    private $data;

    /**
     * Export
     */
    public function export() {
        return [
            'user_guid' => $this->userGuid,
            'wallet_address' => $this->walletAddress,
            'tx' => $this->tx,
            'amount' => $this->amount,
            'timestamp' => $this->timestamp,
            'contract' => $this->contract,
        ];
    }

}