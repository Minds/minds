<?php

/**
 * Minds Withholding
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Wallets\OffChain\Withholding;

use Minds\Traits\MagicAttributes;

/**
 * Class Withholding
 * @package Minds\Core\Blockchain\Wallets\OffChain\Withholding
 * @method int getTimestamp()
 * @method Withholding setTimestamp(int $value)
 * @method string getTx()
 * @method Withholding setTx(string $value)
 * @method string getType()
 * @method Withholding setType(string $value)
 * @method string getWalletAddress()
 * @method Withholding setWalletAddress(string $value)
 * @method int getAmount()
 * @method Withholding setAmount(int $value)
 * @method int getTtl()
 * @method Withholding setTtl(int $value)
 */
class Withholding
{
    use MagicAttributes;

    /** @var int */
    protected $userGuid;

    /** @var int */
    protected $timestamp;

    /** @var string */
    protected $tx;

    /** @var string */
    protected $type;

    /** @var string */
    protected $walletAddress;

    /** @var string */
    protected $amount;

    /** @var int */
    protected $ttl;

    /**
     * @param $userGuid
     * @return $this
     */
    public function setUserGuid($userGuid)
    {
        if (is_object($userGuid)) {
            $userGuid = $userGuid->guid;
        }

        $this->userGuid = $userGuid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserGuid()
    {
        return $this->userGuid;
    }

    public function export()
    {
        return [
            'user_guid' => $this->userGuid,
            'timestamp' => $this->timestamp,
            'tx' => $this->tx,
            'type' => $this->type,
            'wallet_address' => $this->walletAddress,
            'amount' => $this->amount,
            'ttl' => $this->ttl
        ];
    }
}
