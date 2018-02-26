<?php
namespace Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Entities\User;

class Balance
{

    /** @var Sums */
    private $sums;

    /** @var User */
    private $user;

    public function __construct($sums = null)
    {
        $this->sums = $sums ?: new Sums;
    }

    /**
     * Sets the user
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Return the balance
     * @return double
     */
    public function get()
    {
        return $this->sums
            ->setUser($this->user)
            ->getBalance();
    }

    public function getByContract($contract, $ts = null, $onlySpend = false)
    {
        return $this->sums
            ->setUser($this->user)
            ->setTimestamp($ts)
            ->getContractBalance($contract, $onlySpend);
    }

}
