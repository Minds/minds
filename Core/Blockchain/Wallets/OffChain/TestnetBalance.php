<?php
namespace Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;

class TestnetBalance
{

    /** @var Sums */
    private $sums;

    /** @var User */
    private $user;

    public function __construct($sums = null)
    {
        $this->sums = $sums ?: new TestnetSums;
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
}
