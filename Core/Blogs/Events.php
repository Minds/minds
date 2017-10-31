<?php

namespace Minds\Core\Blogs;

use Minds\Core\Events\Dispatcher;
use Minds\Entities;

class Events
{
    public function register()
    {
        Dispatcher::register('entities:map', 'all', function ($event) {
            $params = $event->getParameters();

            if ($params['row']->type == 'object' && $params['row']->subtype == 'blog') {
                $event->setResponse(new Entities\Blog($params['row']));
            }
        });
    }
}
