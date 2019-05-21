<?php
/**
 * Cohort
 *
 * @author edgebal
 */

namespace Minds\Core\Reports\Summons;

use Exception;

class Cohort
{
    /** @var Repository */
    protected $repository;

    /** @var Pool */
    protected $pool;

    /** @var int */
    protected $poolSize;

    /** @var int */
    protected $maxPages;

    /**
     * Cohort constructor.
     * @param Repository $repository
     * @param Pool $pool
     * @param int $poolSize
     * @param int $maxPages
     */
    public function __construct(
        $repository = null,
        $pool = null,
        $poolSize = null,
        $maxPages = null
    )
    {
        $this->repository = $repository ?: new Repository();
        $this->pool = $pool ?: new Pool();
        $this->poolSize = $poolSize ?: 400;
        $this->maxPages = $maxPages ?: 2; // NOTE: Normally capped to 20.
    }

    /**
     * @param array $opts
     * @return string[]
     * @throws Exception
     */
    public function pick($opts)
    {
        $opts = array_merge([
            'size' => 0,
            'for' => null,
            'except' => [],
            'except_hashes' => [],
            'include_only' => null,
            'active_threshold' => null,
        ], $opts);

        $cohort = [];

        $page = 0;

        while (true) {
            if ($page >= $this->maxPages) {
                // Max = PoolSize * MaxPages
                error_log("Warning: Cannot gather a full cohort on {$this->maxPages} partitions");
                break;
            }

            $pool = $this->pool->getList([
                'active_threshold' => $opts['active_threshold'],
                'platform' => 'browser',
                'for' => $opts['for'],
                'except' => $opts['except'],
                'except_hashes' => $opts['except_hashes'],
                'include_only' => $opts['include_only'],
                'validated' => true,
                'size' => $this->poolSize,
                'page' => $page,
                'max_pages' => $this->maxPages,
            ]);

            foreach ($pool as $userGuid) {
                // TODO: Check subs
                $cohort[] = $userGuid;

                if (count($cohort) >= $opts['size']) {
                    break;
                }
            }

            if (count($cohort) >= $opts['size']) {
                break;
            }

            $page++;
        }

        return $cohort;
    }
}
