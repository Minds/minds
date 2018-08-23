<?php
/**
 * Minds Channels Events Listeners
 *
 * @author Mark
 */

namespace Minds\Core\Comments;

use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;
use Minds\Core\Events\Event;
use Minds\Core\Votes\Vote;

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
        $this->manager = $manager ?: new Manager();
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
    }

    public function register()
    {
        // Entity save
        $this->eventsDispatcher->register('entity:delete', 'user', function (Event $event) {
            $user = $event->getParameters()['entity'];

            $event->setResponse($this->manager->delete($comment));
        });
    }

}