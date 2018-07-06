<?php
namespace Minds\Core\Media;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Entities;

class Recommended
{
    protected $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    public function getByOwner($limit, $user, $type)
    {
        $options = [
            'owner_guids' => [ $user ],
            'type' => 'object',
            'subtype' => $type,
            'limit' => $limit
        ];

        $entities = Core\Entities::get($options);

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

        return Core\Entities::get([ 'guids' => $guids ]);
    }
}
