<?php
namespace Minds\Core\Blockchain\Wallets\OnChain;

use Minds\Entities\User;
use Minds\Core\Di\Di;

class Balance
{

    /** @var Token */
    private $token;

    /** @var Cache */
    private $cache;

    /** @var User */
    private $user;

    public function __construct(
        $token = null,
        $cache = null
    )
    {
        $this->token = $token ?: Di::_()->get('Blockchain\Token');
        $this->cache = $cache ?: Di::_()->get('Cache');
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
        $address = $this->user->getEthWallet();

        if (!$address) {
            return (double) 0;
        }

        $cacheKey = "blockchain:balance:{$address}";
        $balance = $this->cache->get($cacheKey);

        if ($balance)
            return (double) $balance;

        $balance = $this->token->balanceOf($address);
        $this->cache->set($cacheKey, $balance, 60);

        return (double) $balance;
    }

}
