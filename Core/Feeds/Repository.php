<?php

/**
 * Minds Feeds Repository
 *
 * @author emi
 */

namespace Minds\Core\Feeds;

use Minds\Common\Repository\Response;

class Repository
{
    /** @var Legacy\Repository */
    protected $legacyRepository;

    /**
     * Repository constructor.
     * @param null $legacyRepository
     */
    public function __construct($legacyRepository = null)
    {
        $this->legacyRepository = $legacyRepository ?: new Legacy\Repository();
    }

    /**
     * Gets a list of Feed Items
     * @param array $opts
     * @return Response
     * @throws \Exception
     */
    public function getList(array $opts = [])
    {
        return $this->legacyRepository->getList($opts);
    }

    /**
     * @param FeedItem $feedItem
     * @return bool
     * @throws \Exception
     */
    public function add(FeedItem $feedItem)
    {
        return $this->legacyRepository->add($feedItem);
    }

    /**
     * @param FeedItem $feedItem
     * @return bool
     * @throws \Exception
     */
    public function update(FeedItem $feedItem)
    {
        return $this->legacyRepository->update($feedItem);
    }

    /**
     * @param FeedItem $feedItem
     * @return bool
     * @throws \Exception
     */
    public function delete(FeedItem $feedItem)
    {
        return $this->legacyRepository->delete($feedItem);
    }
}
