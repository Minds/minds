<?php
/**
 * Transaction model
 */
namespace Minds\Core\Blockchain\Transactions;

use Minds\Traits\MagicAttributes;

class Transaction
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

}