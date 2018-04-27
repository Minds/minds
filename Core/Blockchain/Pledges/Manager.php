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

    public function __construct($repo = null)
    {
        $this->repo = $repo ?: Di::_()->get('Blockchain\Pledges\Repository');
    }

    /**
     * Get the pledged amount from a user
     * @param User $user
     * @return string
     */
    public function getPledgedAmount($user)
    {
        if (!$user || !$phone_number_hash = $user->getPhoneNumberHash()) {
            return 0;
        }
        $pledge = $this->repo->get($phone_number_hash);
        return (string) $pledge->getAmount();
    }

    /**
     * Add a pledge to repository
     */
    public function add($pledge)
    {
        $this->repo->add($pledge);
    }

}
