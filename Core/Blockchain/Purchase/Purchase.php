<?php
/**
 * Purchase model
 */

namespace Minds\Core\Blockchain\Purchase;

use Minds\Core\Util\BigNumber;
use Minds\Entities\User;
use Minds\Traits\MagicAttributes;

/**
 * Class Purchase
 * @package Minds\Core\Blockchain\Purchase
 * @method string getPhoneNumberHash()
 * @method Purchase setPhoneNumberHash(string $value)
 * @method int getUserGuid()
 * @method Purchase setUserGuid(int $value)
 * @method string getWalletAddress()
 * @method Purchase setWalletAddress(string $value)
 * @method int getTimestamp()
 * @method Purchase setTimestamp(int $value)
 * @method string getAmount()
 * @method Purchase setAmount(string $value)
 * @method string getStatus()
 * @method Purchase setStatus(string $value)
 * @method string getTx()
 * @method Purchase setTx(string $value)
 * @method int getRequestedAmount()
 * @method Purchase setRequestedAmount(BigNumber $value)
 * @method int getIssuedAmount()
 * @method Purchase setIssuedAmount(int $value)
 */
class Purchase implements \JsonSerializable
{
    use MagicAttributes;

    /** @var string $tx (PRIMARY KEY) */
    private $phoneNumberHash;

    /** @var int $userGuid */
    private $userGuid;

    /** @var string $walletAddress */
    private $walletAddress;

    /** @var int $timestamp */
    private $timestamp;

    /** @var int $requestedAmount */
    private $requestedAmount = 0;

    /** @var int $issued */
    private $issuedAmount = 0;

    /** @var string */
    private $tx;

    /** @var string */
    private $status;

    /**
     * Return the amount of unissued tokens
     * @return BigNumber
     * @throws \Exception
     */
    public function getUnissuedAmount()
    {
        return BigNumber::_($this->requestedAmount)
            ->sub(BigNumber::_($this->issuedAmount));
    }

    /**
     * Export
     * @param bool $pii
     * @return array
     * @throws \Exception
     */
    public function export($pii = false)
    {
        $export = [
            'user_guid' => $this->userGuid,
            'user' => (new User($this->userGuid))->export(),
            'tx' => $this->tx,
            'wallet_address' => $this->walletAddress,
            'requested_amount' => $this->requestedAmount,
            'issued_amount' => $this->issuedAmount,
            //'eth_amount' => (float) BigNumber::fromPlain($this->requested_amount, 18)->toString(),
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
     * @throws \Exception
     */
    public function jsonSerialize()
    {
        return $this->export(false);
    }
}
