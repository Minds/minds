<?php

/**
 * Count Cache Delegate
 *
 * @author emi
 */

namespace Minds\Core\Comments\Delegates;

use Minds\Core\Comments\Comment;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Di\Di;

class CountCache
{
    /** @var abstractCacher */
    protected $cache;

    /**
     * CountCache constructor.
     * @param abstractCacher $cache
     */
    public function __construct($cache = null)
    {
        $this->cache = $cache ?: Di::_()->get('Cache');
    }

    /**
     * @param Comment $comment
     */
    public function destroy(Comment $comment)
    {
        $this->cache->destroy("comments:count:{$comment->getEntityGuid()}");
    }
}