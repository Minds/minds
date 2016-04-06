<?php
/**
 * Reporting operations
 */
namespace Minds\Core;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Helpers;

class Reports
{
    protected $mongo;

    public function __construct(Data\Interfaces\ClientInterface $mongo = null)
    {
        $this->mongo = $mongo ?: Data\Client::build('MongoDB');
    }

    /**
     * Creates a new report for an object
     * @param  mixed   $object
     * @param  User    $reporter
     * @param  string  $subject
     * @return boolean
     */
    public function insert($object, $reporter, $subject)
    {
        $object_guid = $this->extractGuid($object);
        $reporter_guid = $this->extractGuid($reporter);

        if (!$object_guid || !$reporter_guid || !$subject) {
            return false;
        }

        $saved = $this->mongo->insert('report', [
            'object' => $object_guid,
            'reporter' => $reporter_guid,
            'reporter_name' => is_object($reporter) ? $reporter->username : $user_guid,
            'subject' => $subject,
            'date' => time(),
            'state' => 'review'
        ]);

        return (bool) $saved;
    }

    /**
     * Gets the reporting queue
     * @param  integer $limit
     * @param  string  $offset
     * @param  string  $state  Optional
     * @return array
     */
    public function getQueue($limit = 12, $offset = '', $state = null)
    {
        $query = [];

        if ($state) {
            $query['state'] = $state;
        }

        if ($offset) {
            $query['_id'] = [ '$gt' => $offset ];
        }

        $data = $this->mongo->find('report', $query);
        $data->limit($limit);
        $data->sort([ '_id' => 1 ]);

        if (!$data) {
            return [];
        }

        $queue = [];

        foreach ($data as $row) {
            $queue[] = $this->export($row);
        }

        return $queue;
    }

    public function get($id, array $opts = []) {
        if (!$id) {
            return false;
        }

        $opts = array_merge([
            'export' => []
        ], $opts);

        // NOTE: We NEED to implement findOne() in Mongo's client
        $reports = iterator_to_array($this->mongo->find('report', [ '_id' => $id ])->limit(1));

        if (!$reports) {
            return false;
        }

        return $this->export($reports[$id], $opts['export']);
    }

    /**
     * Archives a report for later review
     * @param  string  $id
     * @return boolean
     */
    public function archive($id) {
        if (!$id) {
            return false;
        }

        return (bool) $this->mongo->update('report', [
            '_id' => $id
        ], [
            'state'=>'archive'
        ]);
    }

    /**
     * Ignores a report by removing it from queue.
     * @param  string  $id
     * @return boolean
     */
    public function ignore($id) {
        if (!$id) {
            return false;
        }

        return (bool) $this->mongo->remove('report', [
            '_id' => $id
        ]);
    }

    /**
     * Marks an object as mature and removes its report from queue.
     * @param  string  $id
     * @return boolean
     */
    public function explicit($id) {
        if (!$id) {
            return;
        }

        $report = $this->get($id, [ 'export' => [ 'getEntity' => true ] ]);

        if (!$report || !$report['entity']) {
            return false;
        }

        $entity = $report['entity'];

        // Main
        $dirty = $this->enableMatureFlag($entity);

        if ($dirty) {
            $entity->save();
        }

        // Attachment and/or embedded entity
        $props = [ 'attachment_guid', 'entity_guid' ];

        foreach ($props as $prop) {
            if ($entity->{$prop}) {
                $rel = Entities\Factory::build($entity->{$prop});

                if ($rel) {
                    $dirty = $this->enableMatureFlag($rel);

                    if ($dirty) {
                        $rel->save();
                    }
                }
            }
        }

        return (bool) $this->mongo->remove('report', [
            '_id' => $id
        ]);
    }

    /**
     * Deletes an object and removes its report from queue.
     * @param  string  $id
     * @return boolean
     */
    public function delete($id) {
        if (!$id) {
            return;
        }

        $report = $this->get($id, [ 'export' => [ 'getEntity' => true ] ]);

        if (!$report || !$report['entity']) {
            return false;
        }

        if (!method_exists($report['entity'], 'delete')) {
            return false;
        }

        $deleted = $report['entity']->delete();

        if (!$deleted) {
            return false;
        }

        return (bool) $this->mongo->remove('report', [
            '_id' => $id
        ]);
    }

    /**
     * Exports a report row from MongoDB
     * @param  mixed $row
     * @return array
     */
    protected function export($row = null, array $opts = []) {
        if (!$row) {
            return false;
        }

        $opts = array_merge([
            'getEntity' => false
        ], $opts);

        $entity = Entities\Factory::build($row['object']);

        $object = null;
        $object_type = '';
        $object_subtype = '';

        if ($entity) {
            $object = $entity->export();
            $object_type = $object['type'];
            $object_subtype = isset($object['subtype']) ? $object['subtype'] : '';
        }

        $result = [
            '_id' => $row['_id']->{'$id'},
            'object' => $object,
            'object_type' => $object_type,
            'object_subtype' => $object_subtype,
            'reporter' => $row['reporter'],
            'reporter_name' => $row['reporter_name'],
            'state' => $row['state'],
            'subject' => $row['subject'],
            'date' => $row['date'],
        ];

        if ($opts['getEntity']) {
            $result['entity'] = $entity;
        }

        return $result;
    }

    protected function enableMatureFlag($entity = null) {
        if (!$entity || !is_object($entity)) {
            return false;
        }

        $dirty = false;

        // Main mature flag
        if (method_exists($entity, 'setMature')) {
            $entity->setMature(true);
            $dirty = true;
        } elseif (method_exists($entity, 'setFlag')) {
            $entity->setFlag('mature', true);
            $dirty = true;
        } elseif (property_exists($entity, 'mature')) {
            $entity->mature = true;
            $dirty = true;
        }

        // Custom Data
        if (method_exists($entity, 'setCustom') && $report['object']['custom_data'] && is_array($report['object']['custom_data'])) {
            $custom_data = $report['object']['custom_data'];

            if (isset($custom_data[0])) {
                $custom_data[0]['mature'] = true;
            } else {
                $custom_data['mature'] = true;
            }

            $entity->setCustom($report['object']['custom_type'], $custom_data);
            $dirty = true;
        }

        return $dirty;
    }

    /**
     * Marks an object as mature and removes it from queue.
     * @param  mixed  $object
     * @return mixed
     */
    protected function extractGuid($object = null)
    {
        if (!$object) {
            return '';
        }

        if (is_object($object) && property_exists($object, 'getGuid')) {
            return $object->getGuid();
        } elseif (is_object($object) && $object->guid) {
            return $object->guid;
        } elseif (is_array($object) && isset($object['guid'])) {
            return $object['guid'];
        } elseif (is_numeric($object) || is_string($object)) {
            return $object;
        }

        return '';
    }
}
