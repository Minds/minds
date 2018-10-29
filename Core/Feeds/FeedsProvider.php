<?php

namespace Minds\Core\Feeds;

use Minds\Core\Di\Provider;

class FeedsProvider extends Provider {
    public function register() {
        $this->di->bind('Feeds\Suggested\Repository', function($di) {
           return new Suggested\Repository();
        });

        $this->di->bind('Feeds\Suggested\Manager', function($di) {
            return new Suggested\Manager();
        });
    }
}