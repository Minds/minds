<?php

namespace Minds\Core\Helpdesk;

use Minds\Core\Di\Provider;

class HelpdeskProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Helpdesk\Question\Repository', function ($di) {
            return new Question\Repository();
        }, ['useFactory' => false]);

        $this->di->bind('Helpdesk\Category\Repository', function ($di) {
            return new Category\Repository();
        }, ['useFactory' => false]);
    }
}