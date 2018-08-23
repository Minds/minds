<?php
/**
 * Logout
 */
namespace Minds\Core\Channels\Delegates;

use Minds\Core\Data\Sessions;
use Minds\Entities\User;

class Logout
{

    /** @var Sessions $sessions */
    protected $session;

    public function __construct($sessions = null)
    {
        $this->sessions = $sessions ?: new Sessions();
    }

    /**
     * Logout a user
     * @param User $user
     */
    public function logout($user)
    {
        $this->sessions->destroyAll($user->guid);
    }
    
}