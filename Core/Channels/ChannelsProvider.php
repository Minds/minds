<?php
/**
 * Provider
 * @author edgebal
 */

namespace Minds\Core\Channels;

use Minds\Core\Di\Provider;

class ChannelsProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Channels\Manager', function ($di) {
            return new Manager();
        });

        $this->di->bind('Channels\Ban', function ($di) {
            return new Ban();
        });
    }
}
