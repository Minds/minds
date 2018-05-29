<?php
/**
 * Pledge model
 */
namespace Minds\Core\Blockchain\Pledges;

use Minds\Core\Util\BigNumber;
use Minds\Entities\User;
use Minds\Traits\MagicAttributes;

/**
 * Class Pledge
 * @package Minds\Core\Blockchain\Pledges
 * @method string getPhoneNumberHash()
 * @method Pledge setPhoneNumberHash(string $value)
 * @method int getUserGuid()
 * @method Pledge setUserGuid(int $value)
 * @method string getWalletAddress()
 * @method Pledge setWalletAddress(string $value)
 * @method int getTimestamp()
 * @method Pledge setTimestamp(int $value)
 * @method string getAmount()
 * @method Pledge setAmount(string $value)
 * @method string getStatus()
 * @method Pledge setStatus(string $value)
 */
class Pledge implements \JsonSerializable
{
    use MagicAttributes;

    /** @var string $tx (PRIMARY KEY)*/
    private $phoneNumberHash;

    /** @var int $userGuid */
    private $userGuid;

    /** @var string $walletAddress */
    private $walletAddress;

    /** @var int $timestamp */
    private $timestamp;

    /** @var string $amount */
    private $amount;

    /** @var string */
    private $status;

    /**
     * Export
     */
    public function export($pii = false)
    {
        $export = [
            'user_guid' => $this->userGuid,
            'user' => (new User($this->userGuid))->export(),
            'wallet_address' => $this->walletAddress,
            'amount' => $this->amount,
            'eth_amount' => (float) BigNumber::fromPlain($this->amount, 18)->toString(),
            'timestamp' => $this->timestamp * 1000,
            'status' => $this->status,
        ];

        if ($pii) {
            $export['phone_number_hash'] = $this->phoneNumberHash;
        }

        return $export;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->export(false);
    }
}
