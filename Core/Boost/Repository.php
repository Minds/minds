<?php
namespace Minds\Core\Boost;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Entities;

use Cassandra;

/**
 * Repository for boosts
 * @package Minds\Core\Boost
 */
class Repository
{
    /**
     * @var Core\Data\Cassandra\Client
     */
    protected $db;

    /**
     * Repository constructor.
     * @param null $db
     */
    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param string $type
     * @param array $options
     * @return array
     * @throws \Exception
     */
    public function getAll($type, array $options = [])
    {
        $options = array_merge([
            'limit' => 12,
            'offset' => '',
            'guids' => null,
            'owner_guid' => null,
            'destination_guid' => null,
            'order' => null
        ], $options);

        if (!$type) {
            throw new \Exception('Type is required');
        }

        $template = "SELECT * FROM boosts WHERE type = ?";
        $values = [ (string) $type ];
        $allowFiltering = false;
        $order = null;

        if ($options['order']) {
            $order = [ 'guid ' . ($options['order'] == 'asc' ? 'ASC' : 'DESC') ];
        }

        if ($options['owner_guid']) {
            $owner_guid = $options['owner_guid'];

            if (is_object($owner_guid)) {
                $owner_guid = $owner_guid->guid;
            } elseif (is_array($owner_guid)) {
                $owner_guid = $owner_guid['guid'];
            }

            $template = "SELECT * FROM boosts_by_owner WHERE type = ? AND owner_guid = ?";
            $values = [
                (string) $type,
                new Cassandra\Varint($owner_guid)
            ];

            if ($options['order']) {
                $order = [
                    'owner_guid ' . ($options['order'] == 'asc' ? 'DESC' : 'ASC'),
                    'guid ' . ($options['order'] == 'asc' ? 'ASC' : 'DESC'),
                ];
            }

            if ($options['destination_guid']) {
                $destination_guid = $options['destination_guid'];

                if (is_object($destination_guid)) {
                    $destination_guid = $destination_guid->guid;
                } elseif (is_array($destination_guid)) {
                    $destination_guid = $destination_guid['guid'];
                }

                $template .= " AND destination_guid = ?";
                $values[] = new Cassandra\Varint($destination_guid);
                $allowFiltering = true;
            }
        } elseif ($options['destination_guid']) {
            $destination_guid = $options['destination_guid'];

            if (is_object($destination_guid)) {
                $destination_guid = $destination_guid->guid;
            } elseif (is_array($destination_guid)) {
                $destination_guid = $destination_guid['guid'];
            }

            $template = "SELECT * FROM boosts_by_destination WHERE type = ? AND destination_guid = ?";
            $values = [
                (string) $type,
                new Cassandra\Varint($destination_guid)
            ];

            if ($options['order']) {
                $order = [
                    'destination_guid ' . ($options['order'] == 'asc' ? 'DESC' : 'ASC'),
                    'guid ' . ($options['order'] == 'asc' ? 'ASC' : 'DESC'),
                ];
            }
        }

        if ($options['guids']) {
            $collection = Cassandra\Type::collection(Cassandra\Type::varint())->create(...array_values(array_map(function ($guid) {
                return new Cassandra\Varint($guid);
            }, $options['guids'])));

            $template .= " AND guid IN ?";
            $values[] = $collection;
            $order = null;
        }

        if ($order) {
            $template .= " ORDER BY " . implode(', ', $order);
        }

        if ($allowFiltering) {
            $template .= " ALLOW FILTERING";
        }

        $query = new Prepared\Custom();
        $query->query($template, $values);

        $query->setOpts([
            'page_size' => (int) $options['limit'],
            'paging_state_token' => base64_decode($options['offset'])
        ]);

        $boosts = [];
        $token = '';

        try {
            $result = $this->db->request($query);

            foreach ($result as $row) {
                $boost = (new Entities\Boost\Factory())->build($row['type']);
                $boost->loadFromArray($row['data']);

                $boosts[] = $boost;
            }

            $token = base64_encode($result->pagingStateToken());
        } catch (\Exception $e) {
            // TODO: Log or warning
        }

        return [
            'data' => $boosts,
            'next' => $token
        ];
    }

    /**
     * @param string $type
     * @param string|int $guid
     * @return Entities\Boost\BoostEntityInterface
     */
    public function getEntity($type, $guid)
    {
        if (!$type || !$guid) {
            return false;
        }

        $template = "SELECT * FROM boosts WHERE type = ? AND guid = ? LIMIT ?";
        $values = [
            (string) $type,
            new Cassandra\Varint($guid),
            1
        ];

        $query = new Prepared\Custom();
        $query->query($template, $values);

        $boost = false;

        try {
            $result = $this->db->request($query);

            if (isset($result[0]) && $result[0]) {
                $boost = (new Entities\Boost\Factory())->build($result[0]['type']);
                $boost->loadFromArray($result[0]['data']);
            }
        } catch (\Exception $e) {
            // TODO: Log or warning
        }

        return $boost;
    }

    /**
     * @param string $type
     * @param string $mongo_id
     * @return Entities\Boost\BoostEntityInterface|false
     */
    public function getEntityById($type, $mongo_id)
    {
        if (!$type || !$mongo_id) {
            return false;
        }

        $template = "SELECT * FROM boosts_by_mongo_id WHERE type = ? AND mongo_id = ? LIMIT ?";
        $values = [
            (string) $type,
            (string) $mongo_id,
            1
        ];

        $query = new Prepared\Custom();
        $query->query($template, $values);

        $boost = false;

        try {
            $result = $this->db->request($query);

            if (isset($result[0]) && $result[0]) {
                $boost = (new Entities\Boost\Factory())->build($result[0]['type']);
                $boost->loadFromArray($result[0]['data']);
            }
        } catch (\Exception $e) {
            // TODO: Log or warning
        }

        return $boost;
    }

    /**
     * Insert or update a boost
     * @param string $type
     * @param array $data
     * @return bool
     * @throws \Exception
     */
    public function upsert($type, array $data)
    {
        if (!$type) {
            throw new \Exception('Type is required');
        }

        if (!isset($data['guid']) || !$data['guid']) {
            throw new \Exception('GUID is required');
        }

        if (!isset($data['owner']['guid']) || !$data['owner']['guid']) {
            throw new \Exception('Owner is required');
        }

        if (!isset($data['state']) || !$data['state']) {
            throw new \Exception('State is required');
        }

        $template = "INSERT INTO boosts (type, guid, owner_guid, destination_guid, mongo_id, state, data) VALUES (?, ?, ?, ?, ?, ?, ?)";

        $destination = null;

        if (isset($data['destination']['guid']) && $data['destination']['guid']) {
            $destination = new Cassandra\Varint($data['destination']['guid']);
        }

        $values = [
            (string) $type,
            new Cassandra\Varint($data['guid']),
            new Cassandra\Varint($data['owner']['guid']),
            $destination,
            (string) $data['_id'],
            (string) $data['state'],
            json_encode($data)
        ];

        $query = new Prepared\Custom();
        $query->query($template, $values);

        try {
            $success = $this->db->request($query);
        } catch (\Exception $e) {
            return false;
        }

        return $success;
    }
}

