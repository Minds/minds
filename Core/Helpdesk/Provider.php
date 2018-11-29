<?php

namespace Minds\Core\Helpdesk;

use Minds\Core\Di\Provider as DiProvider;

class Provider extends DiProvider
{
    public function register()
    {
        $this->di->bind('Helpdesk\Question\Repository', function ($di) {
            return new Question\Repository();
        }, ['useFactory' => false]);

        $this->di->bind('Helpdesk\Question\Manager', function ($di) {
            return new Question\Manager();
        }, ['useFactory' => false]);

        $this->di->bind('Helpdesk\Category\Repository', function ($di) {
            return new Category\Repository();
        }, ['useFactory' => false]);

        $this->di->bind('Helpdesk\Category\Manager', function ($di) {
            return new Category\Manager();
        }, ['useFactory' => false]);

        $this->di->bind('Helpdesk\Question\Votes\Manager', function ($di) {
            return new Question\Votes\Manager();
        }, ['useFactory' => false]);

        $this->di->bind('Helpdesk\Category', function ($di) {
            return new Category\Category();
        }, ['useFactory' => false]);

        $this->di->bind('Helpdesk\Question', function ($di) {
            return new Question\Question();
        }, ['useFactory' => false]);

        $this->di->bind('Helpdesk\Search', function ($di) {
            return new Search();
        }, ['useFactory' => false]);
    }
}