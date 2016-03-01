<?php
/**
 * Minds Boost Provider
 */

namespace Minds\Core\Boost;

use Minds\Core\Di\Provider;

class BoostProvider extends Provider
{

    public function register()
    {
        $this->di->bind('Boost\Network', function($di){
            return new Network(Data\Client::build('MongoDB'), new Data\Call('entities_by_time'));
        }, ['useFactory'=>true]);
        $this->di->bind('Boost\Newsfeed', function($di){
            return new Newsfeed(Data\Client::build('MongoDB'), new Data\Call('entities_by_time'));
        }, ['useFactory'=>true]);
        $this->di->bind('Boost\Content', function($di){
            return new Content(Data\Client::build('MongoDB'), new Data\Call('entities_by_time'));
        }, ['useFactory'=>true]);

        $this->di->bind('Boost\Peer', function($di){
            return new Peer();
        }, ['useFactory'=>true]);
    }

}
