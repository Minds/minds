<?php

/**
 * Minds Comments Events Listeners
 *
 * @author emi
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

    /** @var Votes\Manager */
    protected $votesManager;

    /**
     * Events constructor.
     * @param Manager $manager
     * @param Dispatcher $eventsDispatcher
     */
    public function __construct($manager = null, $eventsDispatcher = null, $votesManager = null)
    {
        $this->manager = $manager ?: new Manager();
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
        $this->votesManager = $votesManager ?: new Votes\Manager();
    }

    public function register()
    {
        // Entity resolver

        $this->eventsDispatcher->register('entity:resolve', 'comment', function (Event $event) {
            $luid = $event->getParameters()['luid'];

            $event->setResponse($this->manager->getByLuid($luid));
        });

        // Entity save

        $this->eventsDispatcher->register('entity:save', 'comment', function (Event $event) {
            $comment = $event->getParameters()['entity'];

            $event->setResponse($this->manager->update($comment));
        });

        // Votes Module

        $this->eventsDispatcher->register('vote:action:has', 'comment', function (Event $event) {
            $event->setResponse(
                $this->votesManager
                    ->setVote($event->getParameters()['vote'])
                    ->has()
            );
        });

        $this->eventsDispatcher->register('vote:action:cast', 'comment', function (Event $event) {
            $event->setResponse(
                $this->votesManager
                    ->setVote($event->getParameters()['vote'])
                    ->cast()
            );
        });

        $this->eventsDispatcher->register('vote:action:cancel', 'comment', function (Event $event) {
            $event->setResponse(
                $this->votesManager
                    ->setVote($event->getParameters()['vote'])
                    ->cancel()
            );
        });
    }
}