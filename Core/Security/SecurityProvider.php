<?php
/**
 * Minds Security Provider
 */

namespace Minds\Core\Security;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
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
    }
}
