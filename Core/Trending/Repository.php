<?php
namespace Minds\Core\Trending;

use Minds\Core;

class Repository
{
    private $indexDb;

    public function __construct($indexDb)
    {
        $this->indexDb = $indexDb;
    }

    public function store($key, array $guids)
    {
        $rows = [];
        $g = new \GUID();
        foreach ($guids as $i => $guid) {
            $i = $g->migrate($i);
            $rows[$i] = $guid;
        }

        $this->indexDb->insert("trending:$key", $rows);

        return $this;
    }

    public function fetch($key, $limit = 12, $offset = '')
    {
        $rows = $this->indexDb->getRow("trending:$key", [
            'reversed' => false,
            'limit' => $limit,
            'offset' => $offset
        ]);

        if (!$rows) {
            return [];
        }

        return array_values($rows);
    }
}
