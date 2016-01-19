<?php
/**
 * Minds Security Provider
 */

namespace Minds\Core\Security;

use Minds\Core;
use Minds\Core\Data;
use Minds\Core\Di\Provider;

class SecurityProvider extends Provider
{

    public function register()
    {
        $this->di->bind('Security\ACL\Block', function($di){
            return new ACL\Block(new Data\Call('entities_by_time'), Core\Data\cache\factory::build());
        }, ['useFactory'=>true]);
    }

}
