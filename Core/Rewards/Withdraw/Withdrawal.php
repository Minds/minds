<?php
/**
 * Withdrawal class
 */
namespace Minds\Core\Rewards\Withdraw;

use Minds\Traits\MagicAttributes;

class Withdrawal
{
    use MagicAttributes;

    /** @var string $tx */
    private $tx;

    /** @var string $contract */
    private $contract;

    /** @var string $function */
    private $function;

    /** @var int $user_guid */
    private $user_guid;

    /** @var bool $completed */
    private $completed = false;

    /** @var int $data */
    private $data;

    /** @var int $timestamp */
    private $timestamp;

    public function export() {
        return [
            'type' => $this->type,
            'timestamp' => $this->timestamp,
            'amount' => $this->amount,
            'user' => $this->user,
            'completed' => $this->completed
        ];
    }

}
