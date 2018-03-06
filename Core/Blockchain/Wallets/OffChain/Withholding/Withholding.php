<?php

/**
 * Minds Withholding
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Wallets\OffChain\Withholding;

use Minds\Traits\MagicAttributes;

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
