<?php

namespace Minds\Core\Blogs;

use Minds\Core\Di\Di;
use Minds\Core\Events\Event;
use Minds\Core\Events\EventsDispatcher;

class Events
{
    /** @var Legacy\Entity */
    protected $legacyEntity;

    /** @var Manager */
    protected $manager;

    /** @var EventsDispatcher */
    protected $eventsDispatcher;

    public function __construct($legacyEntity = null, $manager = null, $eventsDispatcher = null)
    {
        $this->legacyEntity = $legacyEntity ?: new Legacy\Entity();
        $this->manager = $manager ?: new Manager();
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
    }

    public function register()
    {
        // Entities Builder
        $this->eventsDispatcher->register('entities:map', 'all', function (Event $event) {
            $params = $event->getParameters();

            if ($params['row']->type == 'object' && $params['row']->subtype == 'blog') {
                $blog = $this->legacyEntity->build($params['row']);
                $blog->setEphemeral(false);

                $event->setResponse($blog);
            }
        });

        // Entity save

        $this->eventsDispatcher->register('entity:save', 'object:blog', function (Event $event) {
            $blog = $event->getParameters()['entity'];
            $event->setResponse($this->manager->update($blog));
        });
    }
}
