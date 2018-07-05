<?php

namespace Minds\Core\Media;

use Minds\Core;
use Minds\Entities;

class Events
{
    public function register()
    {
        Core\Di\Di::_()->get('EventsDispatcher')->register('entities:map', 'all', function ($event) {
            $params = $event->getParameters();

            if ($params['row']->type == 'object') {
                switch ($params['row']->subtype) {
                    case 'video':
                    case 'kaltura_video':
                        $event->setResponse(new Entities\Video($params['row']));
                        break;

                    case 'audio':
                        $event->setResponse(new Entities\Audio($params['row']));
                        break;

                    case 'image':
                        $event->setResponse(new Entities\Image($params['row']));
                        break;

                    case 'album':
                        $event->setResponse(new Entities\Album($params['row']));
                        break;
                }
            }
        });
    }
}
