<?php
/**
 * CachedRepository
 *
 * @author: Emiliano Balbuena <edgebal>
 */

namespace Minds\Core\Feeds\Top;


use Minds\Core\Data\SortedSet;
use Minds\Helpers\Text;

class CachedRepository
{
    /** @var Repository */
    protected $repository;

    /** @var SortedSet */
    protected $sortedSet;

    /** @var string */
    protected $key;

    public function __construct($repository = null, $sortedSet = null)
    {
        $this->repository = $repository ?: new Repository();
        $this->sortedSet = $sortedSet ?: new SortedSet();
    }

    /**
     * @param string $key
     * @return CachedRepository
     */
    public function setKey($key)
    {
        $this->key = (string) $key;
        return $this;
    }

    /**
     * @param array $opts
     * @return ScoredGuid[]
     * @throws \Exception
     */
    public function getList(array $opts = [])
    {
        if (!$this->key) {
            // Passthru without cache
            return $this->repository->getList($opts);
        }

        $this->sortedSet
            ->setKey($this->buildCacheKeyBasedOnOpts($opts))
            ->setThrottle(60);

        // Check if cache refresh is needed

        if (!($opts['offset'] ?? null) && !$this->sortedSet->isThrottled()) {
            $repoOpts = $opts; // Clone

            $repoOpts['limit'] = 1000; // Batches of 1000
            $repoOpts['offset'] = 0;

            // Initialize
            $this->sortedSet->clean();

            $firstPage = [];

            $index = -1;
            foreach ($this->repository->getList($repoOpts) as $scoredGuid) {
                $this->sortedSet->lazyAdd(++$index, implode(':', [$scoredGuid->getGuid(), $scoredGuid->getScore()]));
                $this->sortedSet->flush(500); // Flush every 500 items

                if ($index < $opts['limit']) {
                    $firstPage[] = $scoredGuid;
                }
            }

            $this->sortedSet->flush(); // Force flushing
            $this->sortedSet->expiresIn(14400); // 4 hours

            return $firstPage;
        }

        // Read from cache

        $response = $this->sortedSet->fetch($opts['limit'], $opts['offset']);

        return array_map(function ($row) {
            $fragments = explode(':', $row);

            return (new ScoredGuid())
                ->setGuid($fragments[0])
                ->setScore((float) $fragments[1]);
        }, $response->toArray());
    }

    protected function buildCacheKeyBasedOnOpts(array $opts)
    {
        $key = ['FeedsTopCache', $this->key, $opts['type'], $opts['period'], $opts['algorithm'], $opts['rating']];

        $key[] = isset($opts['container_guid']) && $opts['container_guid'] ? $this->buildCacheKeyFragment($opts['container_guid']) : '';

        $key[] = isset($opts['custom_type']) && $opts['custom_type'] ? $this->buildCacheKeyFragment($opts['custom_type']) : '';

        // TODO: Find a better way to hash (sort array + map to lowercase?)
        $key[] = isset($opts['hashtags']) && $opts['hashtags'] ? md5($this->buildCacheKeyFragment($opts['hashtags'])) : '';

        $key[] = isset($opts['query']) && $opts['query'] ? md5($this->buildCacheKeyFragment(strtolower($opts['query']))) : '';

        $key[] = isset($opts['filter_hashtags']) && $opts['filter_hashtags'] ? '1' : '0';

        return implode(':', $key);
    }

    protected function buildCacheKeyFragment($value)
    {
        return implode('|', Text::buildArray($value));
    }
}
