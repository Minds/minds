<?php
namespace Minds\Core\Rewards\Withdraw;

use Minds\Traits\MagicAttributes;

class Request
{

    use MagicAttributes;

    /** @var string $tx **/
    private $tx;

    /** @var string $address **/
    private $address;

    /** @var int $user_guid **/
    private $user_guid;

    /** @var double $gas **/
    private $gas;

    /** @var double $amount **/
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

    public function export() {
        return [
            'timestamp' => $this->timestamp * 1000,
            'amount' => $this->amount,
            'user_guid' => $this->user_guid,
            'completed' => $this->completed
        ];
    }
}
