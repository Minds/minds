<?php
namespace Minds\Core\Reports;

use Cassandra;

use Minds\Core;
use Minds\Core\Di\Di;
use Minds\Core\Data;
use Minds\Core\Data\Cassandra\Prepared;
use Minds\Entities;
use Minds\Entities\DenormalizedEntity;
use Minds\Entities\NormalizedEntity;

class Repository
{
    /**
     * @var Data\Cassandra\Client
     */
    protected $db;

    public function __construct($db = null)
    {
        $this->db = $db ?: Di::_()->get('Database\Cassandra\Cql');
    }

    /**
     * @param array $options 'limit', 'offset', 'state'
     * @return array
     */
    public function getAll(array $options = [])
    {
        $options = array_merge([
            'limit' => 12,
            'offset' => '',
            'state' => '',
            'owner' => null
        ], $options);

        $template = "SELECT * FROM reports";
        $values = [];
        $cqlOpts = [];
        $allowFiltering = false;

        if ($options['owner']) {
            $owner_guid = $options['owner'];

            if (is_object($owner_guid)) {
                $owner_guid = $owner_guid->guid;
            } elseif (is_array($owner_guid)) {
                $owner_guid = $owner_guid['guid'];
            }

            $template = "SELECT * FROM reports_by_owner WHERE owner_guid = ?";
            $values = [ new Cassandra\Varint($owner_guid) ];

            if ($options['state']) {
                $template .= " AND state = ?";
                $values[] = (string) $options['state'];
                $allowFiltering = true;
            }
        } elseif ($options['state']) {
            $template = "SELECT * FROM reports_by_state WHERE state = ?";
            if ($options['state'] === 'archived') {
                $template .= ' ORDER BY guid DESC';
            }
            $values = [(string)$options['state']];
        }

        if ($allowFiltering) {
            $template .= " ALLOW FILTERING";
        }

        if ($options['offset']) {
            $cqlOpts['paging_state_token'] = base64_decode($options['offset']);
        }

        if ($options['limit']) {
            $cqlOpts['page_size'] = (int) $options['limit'];
        }

        $query = new Prepared\Custom();
        $query->query($template, $values);
        $query->setOpts($cqlOpts);

        $reports = [];
        $pagingToken = '';

        try {
            $result = $this->db->request($query);
            $pagingToken = base64_encode($result->pagingStateToken());

            foreach ($result as $row) {
                $report = new Entities\Report();
                $report->loadFromArray($row);
                $reports[] = $report;
            }
        } catch (\Exception $e) {
            // TODO: Log or warning
        }

        return [
            'data' => $reports,
            'next' => $pagingToken
        ];
    }

    /**
     * @param string|int $guid
     * @return Entities\Report|false
     */
    public function getRow($guid)
    {
        if (!$guid) {
            return false;
        }

        $template = "SELECT * FROM reports WHERE guid = ? LIMIT ?";
        $values = [
            new Cassandra\Varint($guid),
            1
        ];

        $query = new Prepared\Custom();
        $query->query($template, $values);

        $entity = false;

        try {
            $result = $this->db->request($query);

            if (isset($result[0]) && $result[0]) {
                $entity = new Entities\Report();
                $entity->loadFromArray($result[0]);
            }
        } catch (\Exception $e) {
            // TODO: Log or warning
        }

        return $entity;
    }

    /**
     * @param \ElggEntity|NormalizedEntity|DenormalizedEntity|string|integer $entity
     * @param Entities\User|string|integer $reporter
     * @param string|integer $reason
     * @param string $reason_note
     * @return bool
     * @throws \Exception
     */
    public function create($entity, $reporter, $reason, $reason_note = '')
    {
        if (
            !$entity ||
            !$reporter
        ) {
            return false;
        }

        if (is_numeric($entity) || Core\Luid::isValid($entity)) {
            $entity = Entities\Factory::build($entity);
        }

        if (is_object($entity)) {
            $entity_guid = $entity->guid;
            $owner_guid = $entity->owner_guid;
        } elseif (is_array($entity)) {
            $entity_guid = $entity['guid'];
            $owner_guid = $entity['owner_guid'];
        } else {
            throw new \Exception('Missing entity');
        }

        if (is_object($reporter)) {
            $reporter = $reporter->guid;
        } elseif (is_array($reporter)) {
            $reporter = $reporter['guid'];
        }

        $guid = Core\Guid::build();

        $template = "INSERT INTO reports (
            guid,
            entity_guid,
            time_created,
            reporter_guid,
            entity_luid,
            owner_guid,
            state,
            reason,
            reason_note
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $values = [
            new Cassandra\Varint($guid),
            new Cassandra\Varint($entity_guid),
            new Cassandra\Timestamp(time()),
            new Cassandra\Varint($reporter),
            method_exists($entity, 'getLuid') ? (string) $entity->getLuid() : '',
            new Cassandra\Varint($owner_guid),
            'review',
            (string) $reason,
            (string) $reason_note
        ];

        $query = new Prepared\Custom();
        $query->query($template, $values);

        try {
            $success = $this->db->request($query);
        } catch (\Exception $e) {
            $success = false;
        }

        return (bool) $success;
    }

    /**
     * @param string|int $guid
     * @param array $set values to be set as field => value. Some fields will be auto-casted to Cassandra's types
     * @return bool
     */
    public function update($guid, array $set)
    {
        if (!$guid) {
            return false;
        }

        if (isset($set['guid'])) {
            unset($set['guid']);
        }

        if (!$set) {
            return true;
        }

        $template = "UPDATE reports SET";
        $values = [];

        $updates = [];
        foreach ($set as $key => $value) {
            if (in_array($key, [ 'reporter_guid', 'entity_guid', 'owner_guid' ])) {
                $value = new Cassandra\Varint($value);
            } elseif (in_array($key, [ 'time_created' ])) {
                $values = new Cassandra\Timestamp($value);
            }

            $updates[] = "{$key} = ?";
            $values[] = $value;
        }

        $template .= ' ' . implode(", ", $updates);

        $template .= " WHERE guid = ?";
        $values[] = new Cassandra\Varint($guid);

        $query = new Prepared\Custom();
        $query->query($template, $values);

        try {
            $success = $this->db->request($query);
        } catch (\Exception $e) {
            return false;
        }

        return (bool) $success;
    }

    /**
     * @param string|int $guid
     * @return bool
     */
    public function delete($guid)
    {
        if (!$guid) {
            return false;
        }

        $template = "DELETE FROM reports WHERE guid = ? LIMIT ?";
        $values = [
            new Cassandra\Varint($guid),
            1
        ];

        $query = new Prepared\Custom();
        $query->query($template, $values);

        try {
            $success = $this->db->request($query);
        } catch (\Exception $e) {
            return false;
        }

        return (bool) $success;
    }
}
