<?php
namespace Minds\Core\Rewards\Withdraw;

use Minds\Entities\User;
use Minds\Traits\MagicAttributes;

class Request
{

    use MagicAttributes;

    /** @var string $tx **/
    private $tx;

    /** @var string $completed_tx **/
    private $completed_tx;

    /** @var string $address **/
    private $address;

    /** @var int $user_guid **/
    private $user_guid;

    /** @var double $gas **/
    private $gas;

    /** @var string $amount **/
    private $amount;

    /** @var bool $completed **/
    private $completed;

    /** @var int $timestamp **/
    private $timestamp;

    public function setUserGuid($user_guid)
    {
        $this->user_guid = $user_guid;
        return $this;
    }

    public function getUserGuid()
    {
        return $this->user_guid;
    }

    public function setCompletedTx($completed_tx)
    {
        $this->completed_tx = $completed_tx;
        return $this;
    }

    public function getCompletedTx()
    {
        return $this->completed_tx;
    }

    public function export() {
        return [
            'timestamp' => $this->timestamp,
            'amount' => $this->amount,
            'user_guid' => $this->user_guid,
            'tx' => $this->tx,
            'completed' => $this->completed,
            'completed_tx' => $this->completed_tx
        ];
    }
}
