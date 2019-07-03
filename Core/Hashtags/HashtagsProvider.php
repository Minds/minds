<?php

namespace Minds\Core\Hashtags;


use Minds\Core\Di\Provider;

class HashtagsProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Hashtags\User\Repository', function ($di) {
            return new User\Repository();
        });

        $this->di->bind('Hashtags\User\Manager', function ($di) {
            return new User\Manager();
        });

        $this->di->bind('Hashtags\Trending\Repository', function ($di) {
            return new Trending\Repository();
        });
    }
}
