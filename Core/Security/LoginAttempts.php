<?php


namespace Minds\Core\Security;


use Minds\Core\Security\Exceptions\UserNotSetupException;
use Minds\Entities\User;

class LoginAttempts
{
    /** @var User */
    protected $user;

    /**
     * Sets the user
     * @param $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Logs a failed login attempt
     * @return bool
     * @throws UserNotSetupException
     */
    public function logFailure()
    {
        if (!$this->user) {
            throw new UserNotSetupException();
        }
        $user_guid = (int) $this->user->guid;

        if ($user_guid) {
            $fails = (int) $this->user->getPrivateSetting("login_failures");
            $fails++;

            $this->user->setPrivateSetting("login_failures", $fails);
            $this->user->setPrivateSetting("login_failure_$fails", time());
            return true;
        }

        return false;
    }

    /**
     * Check if the user has exceeded the limit of attempts
     * @return bool
     * @throws UserNotSetupException
     */
    public function checkFailures()
    {
        if (!$this->user) {
            throw new UserNotSetupException();
        }
        // 5 failures in 1 minute causes temporary block on logins
        $limit = 5;
        $user_guid = (int) $this->user->guid;

        if ($user_guid) {
            $fails = (int) $this->user->getPrivateSetting("login_failures");
            if ($fails >= $limit) {
                $cnt = 0;
                $time = time();
                for ($n = $fails; $n > 0; $n--) {
                    $f = $this->user->getPrivateSetting("login_failure_$n");
                    if ($f > $time - (60)) {
                        $cnt++;
                    }

                    if ($cnt == $limit) {
                        // Limit reached
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Resets failures attempts
     * @return bool
     * @throws UserNotSetupException
     */
    public function resetFailuresCount()
    {
        if (!$this->user) {
            throw new UserNotSetupException();
        }
        $user_guid = (int) $this->user->guid;

        if ($user_guid) {
            $fails = (int) $this->user->getPrivateSetting("login_failures");

            if ($fails) {
                for ($n = 1; $n <= $fails; $n++) {
                    $this->user->removePrivateSetting("login_failure_" . $n);
                }

                $this->user->removePrivateSetting("login_failures");

                return true;
            }

            // nothing to reset
            return true;
        }

        return false;
    }
}