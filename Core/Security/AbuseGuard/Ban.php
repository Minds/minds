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
    private $recover;
    private $events = true;

    public function __construct($sessions = null, $recover = null, $events = true)
    {
        $this->sessions = $sessions ?: new Core\Data\Sessions();
        $this->recover = $recover ?: new Recover();
        $this->events = $events;
    }

    public function setAccused($accused)
    {
        $this->accused = $accused;
        return $this;
    }

    public function ban()
    {
        $user = $this->accused->getUser();
        //if already banned, skip
        if ($user->banned == 'yes') {
            return true;
        }

        echo "\n$user->guid now banned ({$this->accused->getScore()}) \n";

        $user->ban_reason = 'spam';
        $user->banned = 'yes';
        $user->code = '';
        $success = (bool) $user->save();

        $this->sessions->destroyAll($user->guid);

        //@todo make this a dependency too
        Dispatcher::trigger('ban', 'user', $user);

        $this->recover->setAccused($this->accused)
            ->recover();
        echo "\n$user->guid recovered";

        if ($this->events) {
            $event = new Core\Analytics\Metrics\Event();
            $event->setType('action')
                ->setAction('ban')
                ->setProduct('platform')
                ->setUserGuid(0)
                ->setEntityGuid((string) $user->guid)
                ->setUserPhoneNumberHash(Core\Session::getLoggedInUser()->getPhoneNumberHash())
                ->setEntityType('user')
                ->setAbuseGuardScore($this->accused->getScore())
                ->push();
        }

        return $success;
    }

}
