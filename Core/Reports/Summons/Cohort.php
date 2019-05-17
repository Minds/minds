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

    /**
     * Cohort constructor.
     * @param Repository $repository
     * @param Pool $pool
     */
    public function __construct(
        $repository = null,
        $pool = null
    )
    {
        $this->repository = $repository ?: new Repository();
        $this->pool = $pool ?: new Pool();
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
            'active_threshold' => null,
        ], $opts);

        $cohort = [];

        // Uncomment below to scale
        // $poolSize = $opts['size'] * 5;
        // $max_pages = 20; // NOTE: Normally capped to 20.

        $poolSize = 400;
        $max_pages = 1; // NOTE: Normally capped to 20.
        $page = 0;

        while (true) {
            if ($page > $max_pages) {
                // Max = PoolSize * MaxPages
                error_log('Cannot gather a cohort');
                break;
            }

            $pool = $this->pool->getList([
                'active_threshold' => $opts['active_threshold'],
                'platform' => 'browser',
                'for' => $opts['for'],
                'except' => $opts['except'],
                'validated' => false,
                'size' => $poolSize,
                'page' => $page,
                'max_pages' => $max_pages,
            ]);

            $j = 0;
            foreach ($pool as $userGuid) {
                $j++;

                // TODO: Check subs
                $cohort[] = $userGuid;

                if (count($cohort) >= $opts['size']) {
                    break;
                }
            }

            if ($j === 0 || count($cohort) >= $opts['size']) {
                break;
            }

            $page++;
        }

        return $cohort;
    }
}
