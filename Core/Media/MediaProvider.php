<?php
/**
 * Minds Media Provider.
 */

namespace Minds\Core\Media;

use Minds\Core;
use Minds\Core\Di\Provider;

class MediaProvider extends Provider
{
    public function register()
    {
        $this->di->bind('Media\Albums', function ($di) {
            return new Albums(new Core\Data\Call('entities_by_time'));
        }, ['useFactory' => true]);

        $this->di->bind('Media\Feeds', function ($di) {
            return new Feeds(new Core\Data\Call('entities_by_time'), new Core\Data\Call('entities'));
        }, ['useFactory' => true]);

        $this->di->bind('Media\Repository', function ($di) {
            return new Repository();
        }, ['useFactory' => true]);

        $this->di->bind('Media\Thumbnails', function ($di) {
            return new Thumbnails($di->get('Config'));
        }, ['useFactory' => true]);

        $this->di->bind('Media\Recommended', function ($di) {
            return new Recommended();
        }, ['useFactory' => true]);

        // Proxy

        $this->di->bind('Media\Proxy\Download', function ($di) {
            return new Proxy\Download();
        }, ['useFactory' => true]);

        $this->di->bind('Media\Proxy\Resize', function ($di) {
            return new Proxy\Resize();
        }, ['useFactory' => true]);

        $this->di->bind('Media\Proxy\MagicResize', function ($di) {
            return new Proxy\MagicResize();
        }, ['useFactory' => true]);

        // Imagick

        $this->di->bind('Media\Imagick\Autorotate', function ($di) {
            return new Imagick\Autorotate();
        }, ['useFactory' => true]);

        $this->di->bind('Media\Imagick\Resize', function ($di) {
            return new Imagick\Resize();
        }, ['useFactory' => true]);

        $this->di->bind('Media\Imagick\Manager', function ($di) {
            return new Imagick\Manager();
        }, ['useFactory' => false]);
    }
}
