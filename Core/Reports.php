<?php
/**
 * Reporting operations
 */
namespace Minds\Core;

use Minds\Core;
use Minds\Core\Data;
use Minds\Entities;
use Minds\Entities\Report;
use Minds\Helpers;

class Reports
{
    public function __construct($db = null)
    {
        $this->db = $db ?: new Data\Call('entities_by_time');
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
        if (!$object || !$reporter || !$subject) {
            return false;
        }

        $report = new Report();
        $report
        ->setEntity($object)
        ->setFrom($reporter)
        ->setSubject($subject)
        ->setTimeCreated(time())
        ->setState('review');

        return (bool) $report->save();
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

        $args = [
            'limit' => $limit,
            'reversed' => true
        ];

        if ($offset) {
            $args['offset'] = $offset;
        }

        $rowKey = 'reports';

        if ($state && ctype_alnum($state)) {
            $rowKey = $rowKey . ':' . $state;
        }

        $rows = $this->db->getRow($rowKey, $args);

        if ($args['offset']) {
            unset($rows[$args['offset']]);
        }

        if (!$rows) {
            return [];
        }

        $result = [];

        foreach ($rows as $key => $value) {
            $result[] = json_decode($value, true);
        }

        return $result;
    }

    /**
     * Gets and individual report
     * @param  mixed $guid
     * @param  array $opts
     * @return array
     */
    public function get($guid)
    {
        if (!$guid) {
            return false;
        }

        return (new Report())->loadFromGuid($guid);
    }

    /**
     * Archives a report for later review
     * @param  mixed   $guid
     * @return boolean
     */
    public function archive($guid)
    {
        if (!$guid) {
            return false;
        }

        $report = $this->get($guid);

        if (!$report) {
            return false;
        }

        $report->setState('archive');

        return (bool) $report->save();
    }

    /**
     * Marks an object as mature and removes its report from queue.
     * @param  string  $guid
     * @return boolean
     */
    public function explicit($guid)
    {
        if (!$guid) {
            return;
        }

        $report = $this->get($guid);

        if (!$report) {
            return false;
        }

        $entity = Entities\Factory::build($report->getEntity()->guid); // Most updated version

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

        $report
        ->setState('history')
        ->setAction('explicit');

        return (bool) $report->save();
    }

    /**
     * Deletes an object and removes its report from queue.
     * @param  string  $guid
     * @return boolean
     */
    public function delete($guid)
    {
        if (!$guid) {
            return;
        }

        $report = $this->get($guid);

        if (!$report) {
            return false;
        }

        try {
            $entity = Entities\Factory::build($report->getEntity()->guid); // Most updated version
        } catch (\Exception $e) {
            // Not found
            $entity = null;
        }

        if ($entity) {
            if (!method_exists($entity, 'delete')) {
                return false;
            }

            $deleted = $entity->delete();

            if (!$deleted) {
                return false;
            }
        }

        $report
        ->setState('history')
        ->setAction('delete')
        ->setReadOnly(true);

        return (bool) $report->save();
    }

    /**
     * Enabled the maturity flag for an entity
     * @param  mixed $entity
     * @return boolean
     */
    protected function enableMatureFlag($entity = null)
    {
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
}
