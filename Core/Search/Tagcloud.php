<?php
/**
 * Hashtags / Tag Cloud
 */
namespace Minds\Core\Search;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Hashtags\Trending\Repository;

class Tagcloud
{
    const LIMIT = 20;
    const CACHE_DURATION = 60 * 60;

    const CACHE_KEY = 'trending:hashtags';
    const TIME_CACHE_KEY = 'trending:hashtags:time';

    protected $repository;
    protected $cache;

    /**
     * Class constructor
     *
     * @param Hashtags\Trending\Repository $repository
     * @param Cache\Apcu $cache
     */
    public function __construct($repository = null, $cache = null)
    {
        $this->repository = $repository ?: Di::_()->get('Hashtags\Trending\Repository');
        $this->cache = $cache ?: Di::_()->get('Cache\Apcu');
    }

    /**
     * Get the trending hashtags
     *
     * @return array
     */
    public function get()
    {
        //if ($cached = $this->cache->get(static::CACHE_KEY)) {
        //    $result = json_decode($cached, true);
        //} else {
            $result = $this->repository->getList([ 'limit' => 25 ]);

            $this->cache->set(static::TIME_CACHE_KEY, time(), static::CACHE_DURATION);
            $this->cache->set(static::CACHE_KEY, json_encode($result), static::CACHE_DURATION);
        //}

        return $result;
    }

    /**
     * Get the cache age
     *
     * @return integer
     */
    public function getAge()
    {
        $time = $this->cache->get(static::TIME_CACHE_KEY);

        if (!$time) {
            return false;
        }

        return time() - $time;
    }

    /**
     * Hide a hashtag
     *
     * @param string $tag
     * @return boolean
     */
    public function hide($tag)
    {
        return $this->repository->hide($tag, Core\Session::getLoggedInUser()->guid);
    }

    /**
     * Unhide a hashtag
     *
     * @param string $tag
     * @return boolean
     */
    public function unhide($tag)
    {
        return $this->repository->unhide($tag, Core\Session::getLoggedInUser()->guid);
    }

    /**
     * Force rebuild hashtag trending cache
     *
     * @return void
     */
    public function rebuild()
    {
        $this->cache->destroy(static::TIME_CACHE_KEY);
        $this->cache->destroy(static::CACHE_KEY);

        $this->get(); // Rebuild cache
    }

    /**
     * Fetch trending tags
     *
     * @param integer $limit
     * @return array
     */
    public function fetch($limit = 20)
    {
        return $this->repository->getList();
    }

    /**
     * Fetch hidden tags
     *
     * @param integer $limit
     * @return array
     */
    public function fetchHidden($limit = 500)
    {
        return $this->repository->getHidden(['limit' => $limit]);
    }
}
