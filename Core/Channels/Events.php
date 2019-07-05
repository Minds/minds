<?php
/**
 * Minds Channels Events Listeners
 *
 * @author Mark
 */

namespace Minds\Core\Channels;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Events\Event;

class Events
{
    /** @var Manager */
    protected $manager;

    /** @var Dispatcher */
    protected $eventsDispatcher;

    /**
     * Events constructor.
     * @param Manager $manager
     * @param Dispatcher $eventsDispatcher
     */
    public function __construct($manager = null, $eventsDispatcher = null)
    {
        $this->manager = $manager ?: Di::_()->get('Channels\Manager');
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
    }

    public function register()
    {
        // User entity deletion
        $this->eventsDispatcher->register('entity:delete', 'user', function (Event $event) {
            $user = $event->getParameters()['entity'];

            $event->setResponse($this->manager->setUser($user)->delete());
        });
    }

}
