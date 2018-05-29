<?php
/**
 *  Token Pledges Manager
 */
namespace Minds\Core\Blockchain\Pledges;

use Minds\Core\Queue;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Di\Di;

class Manager
{

    /** @var Repository $repo */
    private $repo;

    /** @var Delegates\TokenSaleEventPledge */
    private $tokenSaleEventPledge;

    public function __construct($repo = null, $tokenSaleEventPledge = null)
    {
        $this->repo = $repo ?: Di::_()->get('Blockchain\Pledges\Repository');
        $this->tokenSaleEventPledge = $tokenSaleEventPledge ?: new Delegates\TokenSaleEventPledge();
    }

    public function getPledge($user)
    {
        if (!$user || !($phone_number_hash = $user->getPhoneNumberHash())) {
            return null;
        }

        return $this->repo->get($phone_number_hash);
    }

    /**
     * Get the pledged amount from a user
     * @param User $user
     * @return string
     */
    public function getPledgedAmount($user)
    {
        $pledge = $this->getPledge($user);

        if (!$pledge) {
            return 0;
        }

        return (string) $pledge->getAmount();
    }

    /**
     * Add a pledge to repository
     */
    public function add($pledge)
    {
        $this->repo->add($pledge);
    }

    /**
     * @param Pledge $pledge
     * @return bool
     * @throws \Exception
     */
    public function approve(Pledge $pledge)
    {
        $this->tokenSaleEventPledge->add($pledge);

        $pledge->setStatus('approved');
        $this->repo->add($pledge);

        return true;
    }

    /**
     * @param Pledge $pledge
     * @return bool
     */
    public function reject(Pledge $pledge)
    {
        $pledge->setStatus('rejected');
        $this->repo->add($pledge);

        return true;
    }
}
