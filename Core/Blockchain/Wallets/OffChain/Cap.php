<?php

/**
 * OffChain Wallet Cap
 *
 * @author emi
 */

namespace Minds\Core\Blockchain\Wallets\OffChain;

use Minds\Core\Config;
use Minds\Core\Di\Di;
use Minds\Core\Util\BigNumber;
use Minds\Entities\User;

class Cap
{
    /** @var Config */
    protected $config;

    /** @var Balance */
    protected $offChainBalance;

    /** @var User */
    protected $user;

    /** @var string */
    protected $contract;

    public function __construct($config = null, $offchainBalance = null)
    {
        $this->config = $config ?: Di::_()->get('Config');
        $this->offChainBalance = $offchainBalance ?: Di::_()->get('Blockchain\Wallets\OffChain\Balance');

    }

    /**
     * @param int|User $user
     * @return Cap
     */
    public function setUser($user)
    {
        if (is_numeric($user)) {
            $user = new User($user);
        }

        $this->user = $user;
        return $this;
    }

    /**
     * @param string $contract
     * @return Cap
     */
    public function setContract($contract)
    {
        $this->contract = $contract;
        return $this;
    }

    /**
     * Returns the amount of tokens a user is allowed to spend
     * @return string
     * @throws \Exception
     */
    public function allowance()
    {
        $contract = 'offchain:' . $this->contract;

        $this->offChainBalance->setUser($this->user);
        $offChainBalanceVal = $this->offChainBalance->getByContract($contract, strtotime('today 00:00'), true);

        $todaySpendBalance = BigNumber::_($offChainBalanceVal)->neg();
        $cap = BigNumber::toPlain($this->config->get('blockchain')['offchain']['cap'] ?: 0, 18)->sub($todaySpendBalance);

        return (string) $cap;
    }

    /**
     * Returns true if the user is allowed to spend amount.
     * @param $amount
     * @return bool
     * @throws \Exception
     */
    public function isAllowed($amount)
    {
        return BigNumber::_($this->allowance())->gte($amount);
    }
}
