<?php
/**
 * Pledge model
 */
namespace Minds\Core\Blockchain\Pledges;

use Minds\Entities\User;
use Minds\Traits\MagicAttributes;

class Pledge
{
    use MagicAttributes;

    /** @var string $tx (PRIMARY KEY)*/
    private $phone_number_hash;

    /** @var int $userGuid */
    private $userGuid;

    /** @var string $walletAddress */
    private $walletAddress;

    /** @var int $timestamp */
    private $timestamp;

    /** @var string $amount */
    private $amount;

    /**
     * Export
     */
    public function export()
    {
        $export = [
            'user_guid' => $this->userGuid,
            'user' => (new User($this->userGuid))->export(),
            'wallet_address' => $this->walletAddress,
            //'phone_number_hash' => $this->phone_number_hash,
            'amount' => $this->amount,
            'timestamp' => $this->timestamp,
            'contract' => $this->contract
        ];
        return $export;
    }

}
