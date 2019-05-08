<?php
/**
 * Search
 *
 * @author edgebal
 */

namespace Minds\Core\Blogs\Delegates;

use Minds\Core\Blogs\Blog;
use Minds\Core\Di\Di;
use Minds\Core\Events\EventsDispatcher;

class Search
{
    /** @var EventsDispatcher */
    protected $eventsDispatcher;

    /**
     * Search constructor.
     * @param EventsDispatcher $eventsDispatcher
     */
    public function __construct(
        $eventsDispatcher = null
    )
    {
        $this->eventsDispatcher = $eventsDispatcher ?: Di::_()->get('EventsDispatcher');
    }

    /**
     * @param Blog $blog
     */
    public function index(Blog $blog)
    {
        $this->eventsDispatcher->trigger('search:index', 'object:blog', [
            'entity' => $blog,
        ]);
    }

    /**
     * @param Blog $blog
     */
    public function prune(Blog $blog)
    {
        $this->eventsDispatcher->trigger('search:cleanup', 'object:blog', [
            'entity' => $blog,
        ]);
    }
}
