<?php
namespace Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;

class Balance
{

    /** @var Sums */
    private $sums;

    /** @var User */
    private $user;

    /** @var Withholding\Sums */
    protected $withholdingSums;

    public function __construct($sums = null, $withholdingSums = null)
    {
        $this->sums = $sums ?: new Sums;
        $this->withholdingSums = $withholdingSums ?: Di::_()->get('Blockchain\Wallets\OffChain\Withholding\Sums');
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

    /**
     * @return string
     * @throws \Exception
     */
    public function getAvailable()
    {
        $balance = $this->get();
        $withholdTotal = $this->withholdingSums
            ->setUserGuid($this->user)
            ->get();

        $available = BigNumber::_($balance)->sub($withholdTotal);

        if ($available->lt(0)) {
            return '0';
        }

        return (string) $available;
    }

    public function getByContract($contract, $ts = null, $onlySpend = false)
    {
        return $this->sums
            ->setUser($this->user)
            ->setTimestamp($ts)
            ->getContractBalance($contract, $onlySpend);
    }

}
