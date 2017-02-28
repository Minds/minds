<?php
namespace Minds\Core\Monetization;

use Minds\Core;
use Minds\Entities;
use Minds\Core\Di\Di;

class Manager
{
    protected $db;
    protected $timeline;

    public function __construct($db = null, $timeline = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');

        // @todo: migrate to CQLv3 (when PR is merged)
        $this->timeline = $timeline ?: new Core\Data\Call('entities_by_time');
    }

    public function get(array $options = [])
    {
        $options = array_merge([
            'limit' => 5000,
            'offset' => '',
            'order' => 'ASC',
        ], $options);

        if ($options['limit'] > 5000) {
            throw new \Exception('Limit cannot be greater than 5000');
        }

        if (isset($options['user_guid'])) {
            $index = "user:{$options['user_guid']}";
        } else {
            $index = 'admin';
        }

        if (isset($options['type'])) {
            $index .= ":{$options['type']}";

            if (isset($options['status'])) {
                $index .= ":{$options['status']}";
            }
        }

        $timelineOptions = [
            'limit' => $options['limit'],
            'reversed' => false,
        ];

        if ($options['offset']) {
            $timelineOptions['offset'] = $options['offset'];
        }

        if ($options['order'] == 'DESC') {
            $timelineOptions['reversed'] = true;
        }

        $guids = $this->timeline->getRow($this->getIndexName($index), $timelineOptions);

        if ($guids && $options['offset'] && $options['limit'] > 1 && isset($guids[$options['offset']])) {
            unset($guids[$options['offset']]);
        }

        if (!$guids) {
            return [];
        }

        $rows = $this->fetch(array_keys($guids));

        // @note: client-side re-ordering because cassandra sorts by TOKEN hash
        usort($rows, function ($a, $b) use ($options) {
            if (!$a['ts'] || !$b['ts'] || ($a['ts'] == $b['ts'])) {
                return 0;
            }

            if ($options['order'] == 'DESC') {
                return $a['ts'] > $b['ts'] ? -1 : 1;
            }

            return $a['ts'] > $b['ts'] ? 1 : -1;
        });

        return $rows;
    }

    public function fetch($guids)
    {
        if (!$guids) {
            return [];
        }

        if (!is_array($guids)) {
            $guids = [ $guids ];
        }

        $prepared = Di::_()->get('Prepared\MonetizationLedger');
        $collection = $this->db->request($prepared->get([
            'guid' => $guids,
            'limit' => count($guids),
        ]));

        if (!$collection) {
            return [];
        }

        $items = [];
        foreach ($collection as $item) { // Collection is an iterable object
            $items[] = $item;
        }

        return $items;
    }

    public function resolve($guid)
    {
        if (!$guid) {
            return false;
        }

        $prepared = Di::_()->get('Prepared\MonetizationLedger');
        $collection = $this->db->request($prepared->get([
            'guid' => (string) $guid,
            'limit' => 1,
        ]));

        if (!$collection) {
            return false;
        }

        return $collection[0];
    }

    public function insert(array $data = [])
    {
        if (!$data) {
            return false;
        }

        foreach ([ 'guid', 'type', 'user_guid', 'status' ] as $field) {
            if (!isset($data[$field])) {
                throw new \Exception("Missing `{$field}` key");
            }
        }

        $guid = $data['guid'];
        unset($data['guid']);

        $prepared = Di::_()->get('Prepared\MonetizationLedger');
        $success = $this->db->request($prepared->upsert($guid, $data), true);

        if ($success) {
            $this->index($guid, [
                "user:{$data['user_guid']}",
                "user:{$data['user_guid']}:{$data['type']}",
                "user:{$data['user_guid']}:{$data['type']}:{$data['status']}",
                "admin",
                "admin:{$data['type']}",
                "admin:{$data['type']}:{$data['status']}",
            ], []);
        }

        return $success;
    }

    public function update($guid, array $data = [], array $oldData = [])
    {
        if (!$data) {
            return true;
        }

        foreach ([ 'guid', 'type', 'user_guid' ] as $field) {
            if (isset($data[$field])) {
                throw new \Exception("Cannot update `{$field}` key");
            }
        }

        foreach ([ 'status' ] as $field) {
            if (isset($data[$field]) && !isset($oldData[$field])) {
                throw new \Exception("Cannot update `{$field}` key if not providing previous data");
            }
        }

        $prepared = Di::_()->get('Prepared\MonetizationLedger');
        $success = $this->db->request($prepared->upsert($guid, $data), true);

        if ($success && $oldData) {
            $indexes = [];
            $removeIndexes = [];

            if (isset($data['status'])) {
                $indexes[] = "user:{$oldData['user_guid']}:{$oldData['type']}:{$data['status']}";
                $indexes[] = "admin:{$oldData['type']}:{$data['status']}";

                $removeIndexes[] = "user:{$oldData['user_guid']}:{$oldData['type']}:{$oldData['status']}";
                $removeIndexes[] = "admin:{$oldData['type']}:{$oldData['status']}";
            }

            $this->index($guid, $indexes, $removeIndexes);
        }

        return $success;
    }

    // @todo: migrate to CQLv3 (when PR is merged)
    public function index($guid, array $indexes = [], array $removeIndexes = [])
    {
        if (!$guid) {
            return false;
        }

        foreach ($indexes as $index) {
            $this->timeline->insert($this->getIndexName($index), [ (string) $guid => time() ]);
        }

        foreach ($removeIndexes as $index) {
            $this->timeline->removeAttributes($this->getIndexName($index), [ (string) $guid ]);
        }
    }

    protected function getIndexName($index)
    {
        return 'monetization_ledger:' . $index;
    }
}
