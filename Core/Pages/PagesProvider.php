<?php
/**
 * Minds Pages Provider
 */

namespace Minds\Core\Pages;

use Minds\Core;
use Minds\Core\Data;
use Minds\Core\Di\Provider;

class PagesProvider extends Provider
{

    public function register()
    {
        $this->di->bind('PagesManager', function($di){
            return new Manager(new Data\Call('entities_by_time'), new Data\Call('user_index_to_guid'));
        }, ['useFactory'=>true]);
    }

}
