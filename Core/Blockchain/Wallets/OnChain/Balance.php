<?php
namespace Minds\Core\Blockchain\Wallets\OnChain;

use Minds\Core\Blockchain\Token;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Di\Di;
use Minds\Entities\User;

class Balance
{

    /** @var Token */
    private $token;

    /** @var abstractCacher */
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
     * @return string
     */
    public function get()
    {
        $address = $this->user->getEthWallet();

        if (!$address) {
            return 0;
        }

        $cacheKey = "blockchain:balance:{$address}";
        $balance = $this->cache->get($cacheKey);

        if ($balance)
            return unserialize($balance);

        $balance = $this->token->balanceOf($address);
        $this->cache->set($cacheKey, serialize($balance), 60);

        return $balance;
    }

}
