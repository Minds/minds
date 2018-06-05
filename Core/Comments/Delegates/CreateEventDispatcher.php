<?php

/**
 * Description
 *
 * @author emi
 */

namespace Minds\Core\Comments\Delegates;

use Minds\Core\Comments\Comment;
use Minds\Core\Di\Di;
use Minds\Core\Events\Dispatcher;

class CreateEventDispatcher
{
    /** @var Dispatcher */
    protected $eventsDispatcher;

    public function __construct($eventsDispatcher = null)
    {
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
    }

    public function dispatch(Comment $comment)
    {
        $this->eventsDispatcher->trigger('create', 'elgg/event/comment', $comment);
    }
}
