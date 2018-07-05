<?php

namespace Minds\Core\Media;

use Minds\Core;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Core\Di\Di;

class Recommended
{
    /** @var Core\Data\Cassandra\Client */
    protected $db;
    /** @var Core\EntitiesBuilder */
    protected $entitiesBuilder;

    public function __construct($db = null, $entitiesBuilder = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
        $this->entitiesBuilder = $entitiesBuilder ?: Di::_()->get('EntitiesBuilder');
    }

    public function getByOwner($limit, $user, $type)
    {
        $options = [
            'owner_guids' => [$user],
            'type' => 'object',
            'subtype' => $type,
            'limit' => $limit
        ];

        $entities = $this->entitiesBuilder->get($options);

        return $entities;
    }

    public function getFeatured($limit, $type = null)
    {
        $key = 'object:featured';

        if ($type) {
            $key = "object:{$type}:featured";
        }

        $prepared = new Prepared\Custom();
        $prepared->query("SELECT * FROM entities_by_time WHERE key = ? LIMIT ?", [
            $key,
            (int) $limit
        ]);

        $result = $this->db->request($prepared);
        $guids = [];

        foreach ($result as $row) {
            $guids[] = $row['value'];
        }

        if (!$guids) {
            return [];
        }

        return $this->entitiesBuilder->get(['guids' => $guids]);
    }
}
