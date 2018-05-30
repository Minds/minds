<?php
/**
 *  Token Pledges Manager
 */
namespace Minds\Core\Blockchain\Pledges;

use Minds\Core\Queue;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Di\Di;
use Minds\Entities\User;

class Manager
{

    /** @var Repository $repo */
    private $repo;

    /** @var Delegates\TokenSaleEventPledge */
    private $tokenSaleEventPledge;

    /** @var Delegates\NewPledgeNotification */
    private $newPledgeNotification;

    /** @var Delegates\ApprovedPledgeNotification */
    private $approvedPledgeNotification;

    /** @var Delegates\ApprovedPledgeEmail */
    private $approvedPledgeEmail;

    public function __construct(
        $repo = null,
        $tokenSaleEventPledge = null,
        $newPledgeNotification = null,
        $approvedPledgeNotification = null,
        $approvedPledgeEmail = null
    )
    {
        $this->repo = $repo ?: Di::_()->get('Blockchain\Pledges\Repository');
        $this->tokenSaleEventPledge = $tokenSaleEventPledge ?: new Delegates\TokenSaleEventPledge();
        $this->newPledgeNotification = $newPledgeNotification ?: new Delegates\NewPledgeNotification();
        $this->approvedPledgeNotification = $approvedPledgeNotification ?: new Delegates\ApprovedPledgeNotification();
        $this->approvedPledgeEmail = $approvedPledgeEmail ?: new Delegates\ApprovedPledgeEmail();
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

        $this->newPledgeNotification->notify($pledge);
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

        $this->approvedPledgeNotification->notify($pledge);
        $this->approvedPledgeEmail->send($pledge);

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
