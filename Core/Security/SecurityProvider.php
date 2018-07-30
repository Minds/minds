<?php
/**
 * Minds Security Provider
 */

namespace Minds\Core\Security;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Di\Provider;

class SecurityProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Security\ACL\Block', function ($di) {
            return new ACL\Block(
              Di::_()->get('Database\Cassandra\Indexes'),
              Di::_()->get('Database\Cassandra\Cql'),
              Core\Data\cache\factory::build()
            );
        }, ['useFactory'=>true]);

        $this->di->bind('Security\Captcha', function ($di) {
            return new Captcha(Di::_()->get('Config'));
        }, ['useFactory'=>true]);

        $this->di->bind('Security\ReCaptcha', function ($di) {
            return new ReCaptcha(Di::_()->get('Config'));
        }, ['useFactory'=>true]);

        $this->di->bind('Security\TwoFactor', function ($di) {
            return new TwoFactor();
        }, ['useFactory'=>false]);

        $this->di->bind('Security\LoginAttempts', function ($di) {
            return new LoginAttempts();
        }, ['useFactory' => false]);

        $this->di->bind('Security\Password', function ($di) {
            return new Password();
        }, ['useFactory' => false]);

        $this->di->bind('Security\Spam', function ($di) {
            return new Spam();
        }, ['useFactory' => true]);
    }
}
