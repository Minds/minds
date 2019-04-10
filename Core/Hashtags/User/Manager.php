<?php

namespace Minds\Core\Hashtags\User;

use Minds\Core\Config;
use Minds\Core\Data\cache\abstractCacher;
use Minds\Core\Di\Di;
use Minds\Core\Hashtags\HashtagEntity;
use Minds\Core\Hashtags\Trending\Repository as TrendingRepository;
use Minds\Entities\User;

class Manager
{

    /** @var User $user */
    private $user;

    /** @var Repository $repository */
    private $repository;

    /** @var TrendingRepository */
    private $trendingRepository;

    /** @var abstractCacher */
    private $cacher;

    /** @var Config $config */
    private $config;

    public function __construct($repository = null, $trendingRepository = null, $cacher = null, $config = null)
    {
        $this->repository = $repository ?: new Repository;
        $this->trendingRepository = $trendingRepository ?: new TrendingRepository;
        $this->cacher = $cacher ?: Di::_()->get('Cache');
        $this->config = $config ?: Di::_()->get('Config');
    }

    /**
     * Set the user
     * @param User $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Return user hashtags alongside some suggestions
     * @param array $opts
     * @return array
     * @throws \Exception
     */
    public function get($opts)
    {
        $opts = array_merge([
            'limit' => 10,
            'trending' => false,
            'defaults' => true,
            'user_guid' => $this->user ? $this->user->getGuid() : null
        ], $opts); // Merge in our defaults

        // User hashtags

        $selected = [];

        if ($this->user) {
            $cached =  $this->cacher->get($this->getCacheKey());

            if ($cached !== false) {
                $selected = json_decode($cached, true);
            } else {
                $response = $this->repository->getAll($opts);

                if ($response) {
                    $selected = $response->map(function ($row) {
                        return $row->toArray();
                    })->toArray();

                    $this->cacher->set($this->getCacheKey(), json_encode($selected), 7 * 24 * 60 * 60); // 1 week (busted on changes)
                }
            }
        }

        // Trending hashtags

        $trending = [];

        if ($opts['trending']) {
            $cached = $this->cacher->get($this->getCacheKey('trending'));

            if ($cached !== false) {
                $trending = json_decode($cached, true);
            } else {
                $results = $this->trendingRepository->getList($opts);

                if ($results) {
                    $trending = $results;
                    $this->cacher->set($this->getCacheKey('trending'), json_encode($trending), 15 * 60 * 60); // 15 minutes
                }
            }
        }

        // Default hashtags

        if ($opts['defaults']) {
            $defaults = $opts['defaults'] ? $this->config->get('tags') : [];
        }

        // Merge and output

        $output = [];

        foreach ($selected as $row) {
            $tag = $row['hashtag'];

            $output[$tag] = [
                'selected' => true,
                'value' => $tag,
                'type' => 'user',
            ];
        }

        foreach ($trending as $tag) {
            if (isset($output[$tag])) {
                continue;
            }

            $output[$tag] = [
                'selected' => false,
                'value' => $tag,
                'type' => 'trending',
            ];
        }

        foreach ($defaults as $tag) {
            if (isset($output[$tag])) {
                continue;
            }

            $output[$tag] = [
                'selected' => false,
                'value' => $tag,
                'type' => 'default',
            ];
        }

        return array_slice(array_values($output), 0, count($selected) + $opts['limit']);
    }

    /**
     * @param HashtagEntity[] $hashtags
     * @return bool
     */
    public function add(array $hashtags)
    {
        $success = $this->repository->add($hashtags);

        if ($success) {
            $this->cacher->destroy($this->getCacheKey());
        }

        return $success;
    }

    /**
     * @param HashtagEntity[] $hashtags
     * @return bool
     */
    public function remove(array $hashtags)
    {
        $success = $this->repository->remove($hashtags);

        if ($success) {
            $this->cacher->destroy($this->getCacheKey());
        }

        return $success;
    }

    /**
     * @return string
     */
    public function getCacheKey($extra = '')
    {
        return "user-selected-hashtags:{$this->user->getGuid()}" . ($extra ? ":{$extra}" : '');
    }
}
