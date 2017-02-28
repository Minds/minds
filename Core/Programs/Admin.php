<?php
namespace Minds\Core\Programs;

use Minds\Core;
use Minds\Entities;
use Minds\Core\Di\Di;

class Admin
{
    protected $timeline;

    public function __construct($timeline = null)
    {
        // @todo: migrate to CQLv3 (when PR is merged)
        $this->timeline = $timeline ?: new Core\Data\Call('entities_by_time');
    }

    public function getQueue($limit = 50, $offset = '')
    {
        $rows = $this->timeline->getRow('opt_in_requests:queue', [
            'limit' => $limit,
            'offset' => $offset,
            'reversed' => true,
        ]);

        if (!$rows) {
            return [];
        }

        if ($offset && $limit > 1 && isset($rows[$offset])) {
            unset($rows[$offset]);
        }

        $rows = array_values($rows);

        array_walk($rows, function (&$item) {
            if (is_string($item)) {
                $item = json_decode($item, true);
            }
        });

        return $rows;
    }
}
