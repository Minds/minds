<?php

/**
 * Minds Blog Manager
 *
 * @author emi
 */

namespace Minds\Core\Blogs;

use Minds\Core\Di\Di;
use Minds\Core\Security\Spam;

class Manager
{
    /** @var Repository */
    protected $repository;

    /** @var Delegates\PaywallReview */
    protected $paywallReview;

    /** @var Delegates\Slug */
    protected $slug;

    /** @var Delegates\Feeds */
    protected $feeds;

    /** @var Spam **/
    protected $spam;

    /** @var Delegates\Search */
    protected $search;

    /**
     * Manager constructor.
     * @param null $repository
     * @param null $paywallReview
     * @param null $slug
     * @param null $feeds
     * @param null $spam
     * @param null $search
     * @throws \Exception
     */
    public function __construct(
        $repository = null,
        $paywallReview = null,
        $slug = null,
        $feeds = null,
        $spam = null,
        $search = null
    )
    {
        $this->repository = $repository ?: new Repository();
        $this->paywallReview = $paywallReview ?: new Delegates\PaywallReview();
        $this->slug = $slug ?: new Delegates\Slug();
        $this->feeds = $feeds ?: new Delegates\Feeds();
        $this->spam = $spam ?: Di::_()->get('Security\Spam');
        $this->search = $search ?: new Delegates\Search();
    }

    /**
     * Gets a blog. Migrates the GUID is necessary.
     * @param int $guid
     * @return Blog
     */
    public function get($guid)
    {
        if (strlen($guid) < 15) {
            $guid = (new \GUID())->migrate($guid);
        }

        return $this->repository->get($guid);
    }

    /**
     * Returns next blog.
     * @param Blog $blog
     * @param string $strategy
     * @return Blog|null
     * @throws \Exception
     */
    public function getNext(Blog $blog, $strategy = 'owner')
    {
        switch ($strategy) {
            case 'owner':
                $blogs = $this->repository->getList([
                    'gt' => $blog->getGuid(),
                    'limit' => 1,
                    'user' => $blog->getOwnerGuid(),
                    'reversed' => false,
                ]);
                break;

            default:
                throw new \Exception('Unknown next strategy');
        }

        if (!$blogs || !isset($blogs[0])) {
            return null;
        }

        return $blogs[0];
    }

    /**
     * Adds a blog
     * @param Blog $blog
     * @return int
     * @throws \Exception
     */
    public function add(Blog $blog)
    {
        if ($this->spam->check($blog)) {
            return false;
        }

        $blog
            ->setTimeCreated(time())
            ->setTimeUpdated(time())
            ->setLastUpdated(time())
            ->setLastSave(time());

        $this->slug->generate($blog);

        $saved = $this->repository->add($blog);

        if ($saved) {
            if (!$blog->isDeleted()) {
                $this->feeds->index($blog);
                $this->feeds->dispatch($blog);
                $this->search->index($blog);
            }

            $this->paywallReview->queue($blog);
        }

        return $saved;
    }

    /**
     * Updates a blog
     * @param Blog $blog
     * @return int
     * @throws \Exception
     */
    public function update(Blog $blog)
    {
        $shouldReindex = $blog->isDirty('deleted');

        $blog
            ->setTimeUpdated(time())
            ->setLastUpdated(time())
            ->setLastSave(time());

        $this->slug->generate($blog);

        $saved = $this->repository->update($blog);

        if ($saved) {
            if (!$blog->isDeleted()) {
                $this->search->index($blog);
            }

            if ($shouldReindex) {
                if (!$blog->isDeleted()) {
                    $this->feeds->index($blog);
                } else {
                    $this->feeds->remove($blog);
                    $this->search->prune($blog);
                }
            }

            $this->paywallReview->queue($blog);
        }

        return $saved;
    }

    /**
     * Deletes a blog
     * @param Blog $blog
     * @return bool
     * @throws \Exception
     */
    public function delete(Blog $blog)
    {
        $deleted = $this->repository->delete($blog);

        if ($deleted) {
            $this->feeds->remove($blog);
            $this->search->prune($blog);
        }

        return $deleted;
    }
}
