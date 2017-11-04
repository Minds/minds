<?php
/**
 * Abuse Guard Ban
 */
namespace Minds\Core\Security\AbuseGuard;

use Minds\Core;
use Minds\Core\Events\Dispatcher;
use Minds\Entities;

class Ban
{

    private $accused;

    public function __construct($sessions = null)
    {
        $this->sessions = $sessions ?: new Core\Data\Sessions();
    }

    public function setAccused($accused)
    {
        $this->accused = $accused;
        return $this;
    }

    public function ban()
    {
        $user = $this->accused->getUser();
        $user->ban_reason = 'spam';
        $user->banned = 'yes';
        $user->code = '';
        $success = (bool) $user->save();

        $this->sessions->destroyAll($user->guid);

        //@todo make this a dependency too
        Dispatcher::trigger('ban', 'user', $user);

        return $success;
    }

}
