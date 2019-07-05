<?php
/**
 * Scroll
 * @author edgebal
 */

namespace Minds\Core\Data\Cassandra;

use Cassandra as Driver;
use Minds\Core\Data\Interfaces\PreparedInterface;
use Minds\Core\Di\Di;

class Scroll
{
    /** @var Client */
    protected $db;

    /**
     * Scroll constructor.
     * @param Client $db
     */
    public function __construct(
        $db = null
    )
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param PreparedInterface $prepared
     * @return \Generator
     */
    public function request(PreparedInterface $prepared)
    {
        $request = clone $prepared;
        $cqlOpts = $request->getOpts() ?: [];

        if (!isset($cqlOpts['page_size']) || !$cqlOpts['page_size']) {
            $cqlOpts['page_size'] = 500;
        }

        while (true) {
            $request->setOpts($cqlOpts);

            /** @var Driver\Rows $rows */
            $rows = $this->db->request($request);

            foreach ($rows as $row) {
                yield $row;
            }

            if ($rows->isLastPage()) {
                break;
            }

            $cqlOpts['paging_state_token'] = $rows->pagingStateToken();
        }
    }
}
