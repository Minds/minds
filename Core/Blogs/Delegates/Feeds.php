<?php

/**
 * Minds Blogs Feeds
 *
 * @author emi
 */

namespace Minds\Core\Blogs\Delegates;

use Minds\Core\Blogs\Blog;
use Minds\Core\Feeds\FeedItem;
use Minds\Core\Feeds\Repository as FeedsRepository;
use Minds\Core\Queue\Client;
use Minds\Core\Queue\Interfaces\QueueClient;

class Feeds
{
    /** @var FeedsRepository */
    protected $feedsRepository;

    /** @var QueueClient */
    protected $queue;

    /**
     * Feeds constructor.
     * @param null $feedsRepository
     * @param null $queue
     * @throws \Exception
     */
    public function __construct(
        $feedsRepository = null,
        $queue = null
    )
    {
        $this->feedsRepository = $feedsRepository ?: new FeedsRepository();
        $this->queue = $queue ?: Client::build();
    }

    /**
     * Add a blog to the feeds
     * @param Blog $blog
     * @throws \Exception
     */
    public function index(Blog $blog)
    {
        foreach ($this->getFeedItems($blog) as $feedItem) {
            $this->feedsRepository->add($feedItem);
        }
    }

    /**
     * Remove a blog from the feeds
     * @param Blog $blog
     * @throws \Exception
     */
    public function remove(Blog $blog)
    {
        foreach ($this->getFeedItems($blog) as $feedItem) {
            $this->feedsRepository->delete($feedItem);
        }
    }

    /**
     * Dispatch the blog to the feeds
     * @param Blog $blog
     * @throws \Exception
     */
    public function dispatch(Blog $blog)
    {
        if (in_array($blog->getAccessId(), [2, 1, -1])) {
            $this->queue
                ->setQueue('FeedDispatcher')
                ->send([
                    'guid' => $blog->getGuid(),
                    'owner_guid' => $blog->getOwnerGuid(),
                    'type' => $blog->getType(),
                    'subtype' => $blog->getSubtype(),
                    'super_subtype' => '',
                ]);
        }
    }

    /**
     * Gets the Feed Items for Blogs
     * @param Blog $blog
     * @return FeedItem[]
     */
    public function getFeedItems(Blog $blog)
    {
        $feedItems = [];

        $feedItems[] = (new FeedItem())
            ->setType('object')
            ->setSubtype('blog')
            ->setContainerGuid($blog->getOwnerGuid())
            ->setFeed('user')
            ->setGuid($blog->getGuid());

        $feedItems[] = (new FeedItem())
            ->setType('object')
            ->setSubtype('blog')
            ->setContainerGuid($blog->getOwnerGuid())
            ->setFeed('network')
            ->setGuid($blog->getGuid());

        $feedItems[] = (new FeedItem())
            ->setType('object')
            ->setSubtype('blog')
            ->setContainerGuid($blog->getContainerGuid())
            ->setFeed('container')
            ->setGuid($blog->getGuid());

        return $feedItems;
    }
}
