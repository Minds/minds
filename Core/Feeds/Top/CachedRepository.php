<?php
/**
 * CachedRepository
 *
 * @author: Emiliano Balbuena <edgebal>
 */

namespace Minds\Core\Feeds\Top;


use Minds\Core\Data\SortedSet;

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
     */
    public function setKey($key)
    {
        $this->key = (string) $key;
    }

    /**
     * @param array $opts
     * @return array
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

        if (!$opts['offset'] && !$this->sortedSet->isThrottled()) {
            $repoOpts = $opts; // Clone

            $repoOpts['limit'] = 10000; // Batches of 10000
            $repoOpts['offset'] = 0;

            // Initialize
            $this->sortedSet->clean();

            $index = -1;
            foreach ($this->repository->getList($repoOpts) as list($guid, $score)) {
                $this->sortedSet->add(++$index, implode(':', [$guid, $score]));
            }

            $this->sortedSet->expiresIn(14400); // 4 hours
        }

        // Read from cache

        $response = $this->sortedSet->fetch($opts['limit'], $opts['offset']);

        return array_map(function ($row) {
            $items = explode(':', $row);

            return [$items[0], (float) $items[1]];
        }, $response->toArray());
    }

    protected function buildCacheKeyBasedOnOpts(array $opts)
    {
        $key = ['sortedset', $this->key, $opts['type'], $opts['period'], $opts['algorithm']];

        if (isset($opts['container_guid']) && $opts['container_guid']) {
            $key[] = (string) $opts['container_guid'];
        }

        if (isset($opts['hashtags']) && $opts['hashtags']) {
            // TODO: Find a better way to hash (sort array + map to lowercase?)
            $key[] = md5(json_encode($opts['hashtags']));
        }

        return implode(':', $key);
    }
}
